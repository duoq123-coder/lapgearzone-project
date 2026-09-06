<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\ProductImport;
use App\Models\ProductExport;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Thống kê số lượng
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalOrdersCount = Order::count();

        // 2. Tính doanh thu thực tế (Bao gồm Đơn hàng đã thanh toán/hoàn tất + Phiếu xuất kho thủ công)
        $today = Carbon::today();
        
        $dailyOrderRevenue = Order::whereIn('status', ['paid', 'completed'])
            ->where('cash_remitted', true)
            ->whereDate('created_at', $today)
            ->sum('total_price');
        $dailyExportRevenue = ProductExport::where('code', 'not like', 'XK-AUTO-%')
            ->where('status', 'Đã giao hàng')
            ->whereDate('created_at', $today)
            ->sum('total');
        $dailyRevenue = $dailyOrderRevenue + $dailyExportRevenue;

        // 3. Danh sách nhân viên (Eloquent)
        $staffList = Staff::orderBy('id', 'desc')->get()->toArray();

        // 4. Lấy danh sách sản phẩm phục vụ form Thêm/Sửa phiếu
        $products = Product::orderBy('name')->get();

        // 5. Lấy dữ liệu thực tế từ DB
        $importsHistory = ProductImport::with('product')->latest()->get();
        $exportsHistory = ProductExport::with('product')->latest()->get();
        // Đơn hàng chờ xử lý: bỏ 'assigned' vì đã giao NV rồi thì không cần hiện ở đây
        // Chỉ giữ: pending (chưa giao NV), customer_confirmed (khách đã nhận), completed (NV báo xong), issue (sự cố)
        $pendingOrders = Order::whereIn('delivery_status', ['pending', 'customer_confirmed', 'completed', 'issue'])->with(['user', 'items.product'])->latest()->get();
        




        // 6. Dữ liệu biểu đồ doanh thu 7 ngày gần nhất (Dữ liệu thực)
        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d/m');

            $orderTotal = Order::whereIn('status', ['paid', 'completed'])
                                  ->where('cash_remitted', true)
                                  ->whereDate('created_at', $date->toDateString())
                                  ->sum('total_price');
            $exportTotal = ProductExport::where('code', 'not like', 'XK-AUTO-%')
                                  ->where('status', 'Đã giao hàng')
                                  ->whereDate('created_at', $date->toDateString())
                                  ->sum('total');
            $realTotal = $orderTotal + $exportTotal;

            $chartValues[] = (float)$realTotal;
        }

        $weeklyRevenue = array_sum($chartValues);

        $activeStaff = Staff::where('status', 'Đang làm việc')->get();
        $activeStaffCccds = $activeStaff->pluck('cccd')->filter(fn($v) => $v && $v !== 'Chưa cập nhật')->toArray();
        $activeStaffPhones = $activeStaff->pluck('phone')->filter(fn($v) => $v && $v !== 'Chưa cập nhật')->toArray();
        $activeStaffNames = $activeStaff->pluck('name')->filter()->toArray();

        $deliveryStaffUsers = User::where('role', 'delivery')
            ->where(function ($query) use ($activeStaffCccds, $activeStaffPhones, $activeStaffNames) {
                $query->whereIn('cccd', $activeStaffCccds)
                      ->orWhereIn('phone', $activeStaffPhones)
                      ->orWhereIn('name', $activeStaffNames);
            })
            ->whereDoesntHave('assignedOrders', function ($query) {
                $query->whereIn('delivery_status', ['assigned', 'issue']);
            })
            ->get();

        $allDeliveryStaff = User::where('role', 'delivery')
            ->where(function ($query) use ($activeStaffCccds, $activeStaffPhones, $activeStaffNames) {
                $query->whereIn('cccd', $activeStaffCccds)
                      ->orWhereIn('phone', $activeStaffPhones)
                      ->orWhereIn('name', $activeStaffNames);
            })
            ->orderBy('name')
            ->get();

        $topProductsStats = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'completed'])
            ->where('orders.cash_remitted', true)
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->select('order_items.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $topProductIds = $topProductsStats->pluck('product_id')->toArray();
        $topSellingProducts = collect();
        if (!empty($topProductIds)) {
            $productsDict = Product::with('category')->whereIn('id', $topProductIds)->get()->keyBy('id');
            foreach ($topProductsStats as $stat) {
                if (isset($productsDict[$stat->product_id])) {
                    $prod = $productsDict[$stat->product_id];
                    $prod->total_sold = $stat->total_sold;
                    $topSellingProducts->push($prod);
                }
            }
        }

        return view('admin.dashboard', compact(
            'totalProducts', 'totalCategories', 'totalUsers', 'totalOrdersCount',
            'dailyRevenue', 'weeklyRevenue', 'staffList', 'products',
            'importsHistory', 'exportsHistory', 'pendingOrders', 'chartLabels', 'chartValues',
            'deliveryStaffUsers', 'allDeliveryStaff', 'topSellingProducts'
        ));
    }

    // --- TOGGLE SẢN PHẨM NỔI BẬT LÊN MỤC TOP LAPTOP ---
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        $status = $product->is_featured ? 'đưa lên' : 'gỡ khỏi';

        // Đồng bộ danh sách sản phẩm nổi bật sang Event Top Laptops nếu có
        $topEvent = \App\Models\Event::where('slug', 'top-laptops')->orWhere('name', 'Top Laptops')->first();
        if ($topEvent) {
            $featuredIds = Product::where('is_featured', true)->pluck('id')->toArray();
            $syncData = [];
            foreach ($featuredIds as $idx => $id) {
                $syncData[$id] = ['sort_order' => $idx + 1];
            }
            $topEvent->products()->sync($syncData);
        }

        return redirect()->back()->with('success', "Đã {$status} mục Top Laptop Nổi Bật trên Trang Cửa Hàng: {$product->name}");
    }

    // --- GÓP Ý KHÁCH HÀNG ---
    public function contacts()
    {
        $contacts = \App\Models\Contact::latest()->paginate(20);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function replyContact(Request $request, \App\Models\Contact $contact)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ], [
            'admin_reply.required' => 'Vui lòng nhập nội dung phản hồi.',
        ]);

        $contact->update([
            'admin_reply' => $request->admin_reply,
            'admin_replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã lưu phản hồi cho góp ý của khách hàng ' . $contact->name . '!');
    }

    // --- HỒ SƠ ADMIN ---
    public function profile()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('admin.profile', compact('user'));
    }

    // --- CRUD NHẬP SẢN PHẨM ---
    public function storeImport(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'supplier'     => 'required|string|max:255',
            'quantity'     => 'required|array',
            'quantity.*'   => 'required|integer|min:1',
            'unit_price'   => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'status'       => 'required|string',
        ]);

        $code = 'NK-' . date('YmdHis') . '-' . rand(10, 99);

        foreach ($request->product_id as $index => $prodId) {
            $qty   = $request->quantity[$index];
            $price = $request->unit_price[$index];
            $total = $qty * $price;

            ProductImport::create([
                'code'       => $code,
                'product_id' => $prodId,
                'supplier'   => $request->supplier,
                'quantity'   => $qty,
                'unit_price' => $price,
                'total'      => $total,
                'status'     => $request->status,
            ]);

            // Chỉ cộng số lượng vào kho nếu trạng thái là Đã hoàn thành
            if ($request->status === 'Đã hoàn thành') {
                Product::find($prodId)->increment('quantity', $qty);
            }
        }

        return redirect()->back()->with('success', 'Nhập sản phẩm mới thành công! Số lượng kho đã được cập nhật.');
    }

    public function updateImport(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier'   => 'required|string|max:255',
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'status'     => 'required|string',
        ]);

        $import = ProductImport::findOrFail($id);
        
        // Hoàn tác số lượng cũ nếu phiếu trước đó đã hoàn thành
        if ($import->status === 'Đã hoàn thành') {
            Product::find($import->product_id)->decrement('quantity', $import->quantity);
        }

        $qty   = $request->quantity;
        $price = $request->unit_price;
        $import->update([
            'product_id' => $request->product_id,
            'supplier'   => $request->supplier,
            'quantity'   => $qty,
            'unit_price' => $price,
            'total'      => $qty * $price,
            'status'     => $request->status,
        ]);

        // Cộng số lượng mới nếu trạng thái mới là Đã hoàn thành
        if ($request->status === 'Đã hoàn thành') {
            Product::find($request->product_id)->increment('quantity', $qty);
        }
        return redirect()->back()->with('success', 'Cập nhật phiếu nhập kho thành công!');
    }

    public function confirmImportBatch($code)
    {
        $imports = ProductImport::where('code', $code)->get();
        if ($imports->isEmpty()) {
            return redirect()->back()->with('error', 'Không tìm thấy mã phiếu nhập này.');
        }

        if ($imports->first()->status === 'Đã hoàn thành') {
            return redirect()->back()->with('error', 'Mã phiếu nhập này đã được xác nhận.');
        }

        // Cập nhật số lượng sản phẩm trong kho (chỉ cập nhật những đơn chưa hoàn thành)
        foreach ($imports as $import) {
            if ($import->status !== 'Đã hoàn thành') {
                Product::find($import->product_id)->increment('quantity', $import->quantity);
            }
        }

        ProductImport::where('code', $code)->update(['status' => 'Đã hoàn thành']);

        return redirect()->back()->with('success', 'Xác nhận toàn bộ đơn nhập hàng thành công!');
    }

    public function destroyImport($id)
    {
        $import = ProductImport::findOrFail($id);
        if ($import->status === 'Đã hoàn thành') {
            Product::find($import->product_id)->decrement('quantity', $import->quantity);
        }
        $import->delete();
        return redirect()->back()->with('success', 'Xóa phiếu nhập kho và hoàn tác số lượng tồn kho thành công.');
    }

    // --- CRUD XUẤT SẢN PHẨM ---
    public function storeExport(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'shipping'      => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'unit_price'    => 'required|numeric|min:0',
            'status'        => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($request->status !== 'Chờ xác nhận') {
            if ($product->quantity < $request->quantity) {
                return redirect()->back()->with('error', 'Lỗi: Số lượng sản phẩm tồn kho hiện tại không đủ để xuất (' . $product->quantity . ' sản phẩm sẵn có).');
            }
            $product->decrement('quantity', $request->quantity);
        }

        $qty   = $request->quantity;
        $price = $request->unit_price;
        $code  = 'XK-' . date('YmdHis') . '-' . rand(10, 99);

        ProductExport::create([
            'code'          => $code,
            'customer_name' => $request->customer_name,
            'product_id'    => $request->product_id,
            'shipping'      => $request->shipping,
            'quantity'      => $qty,
            'unit_price'    => $price,
            'total'         => $qty * $price,
            'status'        => $request->status,
        ]);

        return redirect()->back()->with('success', 'Xuất sản phẩm thành công!');
    }



    public function destroyExport($id)
    {
        $export = ProductExport::findOrFail($id);
        if ($export->status !== 'Chờ xác nhận') {
            Product::find($export->product_id)->increment('quantity', $export->quantity);
        }
        $export->delete();
        return redirect()->back()->with('success', 'Xóa phiếu xuất kho thành công.');
    }

    public function completeExport($id)
    {
        $export = ProductExport::findOrFail($id);
        if ($export->status === 'Chờ xác nhận') {
            Product::find($export->product_id)->decrement('quantity', $export->quantity);
        }
        $export->update(['status' => 'Hoàn thành']);
        return redirect()->back()->with('success', 'Đã đánh dấu phiếu xuất kho là Hoàn thành.');
    }

    public function printAllExports()
    {
        $exports = ProductExport::with('product')->orderBy('id', 'desc')->get();
        ProductExport::where('is_printed', false)->update(['is_printed' => true]);
        return view('admin.exports.print-all', compact('exports'));
    }

    // --- QUẢN LÝ ĐƠN HÀNG (TÍCH HỢP TẠO PHIẾU XUẤT) ---
    public function receivePayment(Request $request, Order $order)
    {
        $prevDeliveryStatus = $order->delivery_status;
        $updateData = [
            'status' => 'completed',
            'delivery_status' => 'done' // Admin đã xác nhận hoàn thành
        ];
        
        // Nếu admin check "cash_remitted" hoặc đơn thanh toán online, đánh dấu đã nộp đủ tiền mặt
        if ($order->payment_method !== 'cod_install' || $request->has('cash_remitted')) {
            $updateData['cash_remitted'] = true;
        } else {
            $updateData['cash_remitted'] = false;
        }
        
        $order->update($updateData);
        
        $order->load(['items.product', 'deliveryStaff']);
        foreach ($order->items as $item) {
            if ($item->product) {
                // Kiểm tra xem đã có phiếu xuất kho cho item này chưa (tránh tạo trùng lặp phiếu & trừ kho 2 lần)
                $exportCode = 'XK-AUTO-ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '-' . $item->id;
                $existingExport = ProductExport::where('code', $exportCode)->first();

                if (!$existingExport) {
                    $staffInfo = $order->deliveryStaff ? ($order->deliveryStaff->name . ($order->deliveryStaff->phone ? ' (' . $order->deliveryStaff->phone . ')' : '')) : 'Chưa phân bổ';
                    ProductExport::create([
                        'code'          => $exportCode,
                        'customer_name' => $order->name,
                        'product_id'    => $item->product_id,
                        'shipping'      => $staffInfo,
                        'quantity'      => $item->quantity,
                        'unit_price'    => $item->price,
                        'total'         => $item->price * $item->quantity,
                        'status'        => 'Hoàn thành',
                    ]);
                    $item->product->decrement('quantity', $item->quantity);
                }
            }
        }

        // Cập nhật hạng thành viên (chỉ cộng nếu chưa được cộng tại bước nhân viên báo cáo giao hàng)
        if ($order->user_id && !in_array($prevDeliveryStatus, ['completed', 'done'])) {
            $user = \App\Models\User::find($order->user_id);
            if ($user && in_array($user->role, ['customer', 'customer_bronze', 'customer_silver', 'customer_gold', 'customer_diamond', 'customer_emerald'])) {
                $user->increment('total_spent', $order->total_price);
                
                $spent = $user->total_spent;
                $newRole = 'customer_bronze';
                
                if ($spent >= 100000000) {
                    $newRole = 'customer_emerald';
                } elseif ($spent >= 50000000) {
                    $newRole = 'customer_diamond';
                } elseif ($spent >= 20000000) {
                    $newRole = 'customer_gold';
                } elseif ($spent >= 5000000) {
                    $newRole = 'customer_silver';
                }
                
                if ($user->role !== $newRole) {
                    $user->update(['role' => $newRole]);
                }
            }
        }

        return redirect()->back()->with('success', 'Đã xác nhận hoàn thành đơn hàng thành công!');
    }

    public function assignDelivery(Request $request, Order $order)
    {
        $request->validate([
            'delivery_staff_id' => 'required|exists:users,id',
        ]);

        $deliveryStaff = \App\Models\User::find($request->delivery_staff_id);

        $updateData = [
            'delivery_staff_id' => $request->delivery_staff_id,
            'delivery_status' => 'assigned'
        ];

        // Nếu đơn hàng thanh toán online (không phải COD), tự động cộng vào doanh thu (chuyển trạng thái sang paid)
        if ($order->payment_method !== 'cod_install') {
            $updateData['status'] = 'paid';
        }

        $order->update($updateData);

        // Tự động tạo phiếu xuất kho & trừ số lượng tồn kho khi bàn giao máy cho nhân viên giao hàng
        $order->load('items.product');
        foreach ($order->items as $item) {
            if ($item->product) {
                $exportCode = 'XK-AUTO-ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '-' . $item->id;
                $existingExport = ProductExport::where('code', $exportCode)->first();

                $staffInfo = $deliveryStaff ? ($deliveryStaff->name . ($deliveryStaff->phone ? ' (' . $deliveryStaff->phone . ')' : '')) : 'Chưa phân bổ';

                if (!$existingExport) {
                    ProductExport::create([
                        'code'          => $exportCode,
                        'customer_name' => $order->name,
                        'product_id'    => $item->product_id,
                        'shipping'      => $staffInfo,
                        'quantity'      => $item->quantity,
                        'unit_price'    => $item->price,
                        'total'         => $item->price * $item->quantity,
                        'status'        => 'Hoàn thành',
                    ]);
                    $item->product->decrement('quantity', $item->quantity);
                } else {
                    // Nếu điều phối lại nhân viên giao hàng
                    $existingExport->update([
                        'shipping' => $staffInfo,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Đã giao nhiệm vụ cho nhân viên giao hàng và tạo phiếu xuất kho thành công!');
    }

    public function updateOrder(Request $request, Order $order)
    {
        if (in_array($order->status, ['paid', 'completed'])) {
            return redirect()->back()->with('error', 'Đơn hàng đã thanh toán/hoàn tất không thể chỉnh sửa thông tin!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'items' => 'nullable|array',
            'items.*.quantity' => 'nullable|integer|min:0',
        ]);

        $order->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Cập nhật sản phẩm trong đơn hàng
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $itemData) {
                $orderItem = OrderItem::find($itemId);
                if ($orderItem && $orderItem->order_id == $order->id) {
                    $qty = (int) $itemData['quantity'];
                    if ($qty <= 0) {
                        $orderItem->delete();
                    } else {
                        $orderItem->update(['quantity' => $qty]);
                    }
                }
            }

            // Tính toán lại tổng tiền đơn hàng
            $subtotal = $order->items()->sum(\DB::raw('price * quantity'));
            
            // Nếu không còn sản phẩm nào, tự động hủy đơn
            if ($subtotal == 0) {
                $order->update([
                    'total_price' => 0,
                    'status' => 'cancelled'
                ]);
                return redirect()->back()->with('success', 'Cập nhật thành công. Đơn hàng đã tự động chuyển sang Hủy vì không còn sản phẩm nào!');
            }

            $discount = $order->discount_amount ?? 0;
            $newTotal = max(0, $subtotal - $discount);
            
            $order->update(['total_price' => $newTotal]);
        }

        return redirect()->back()->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function resolveDeliveryIssue(Order $order)
    {
        $order->update([
            'delivery_status' => 'assigned',
            'delivery_issue' => null,
            'delivery_proof' => null,
        ]);
        
        return redirect()->back()->with('success', 'Đã yêu cầu nhân viên giao lại đơn hàng.');
    }

    public function confirmCodRemittance(Order $order)
    {
        $order->update([
            'cash_remitted' => true,
        ]);
        
        return redirect()->back()->with('success', 'Đã xác nhận nhận đủ tiền COD từ nhân viên.');
    }

    public function cancelOrder(Order $order)
    {
        $order->load('items.product');
        foreach ($order->items as $item) {
            $exportCode = 'XK-AUTO-ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '-' . $item->id;
            $export = ProductExport::where('code', $exportCode)->first();
            if ($export) {
                if ($item->product) {
                    $item->product->increment('quantity', $export->quantity);
                }
                $export->delete();
            }
        }

        $order->update([
            'status' => 'cancelled',
            'delivery_status' => 'cancelled'
        ]);
        return redirect()->back()->with('success', 'Đã hủy đơn hàng và hoàn lại số lượng tồn kho!');
    }

    // ==============================================================
    // --- QUẢN LÝ NHÂN VIÊN ---
    // ==============================================================
    
    public function storeStaff(Request $request)
    {
        $avatarPath = null;
        if ($request->filled('avatar_base64')) {
            $base64 = $request->input('avatar_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $imageName = 'staff_' . time() . '.jpg';
            $path = public_path('uploads/staff');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($path . '/' . $imageName, base64_decode($file_data));
            $avatarPath = 'uploads/staff/' . $imageName;
        } elseif ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/staff'), $filename);
            $avatarPath = 'uploads/staff/' . $filename;
        }

        Staff::create([
            'name'    => $request->name,
            'role'    => $request->role,
            'avatar'  => $avatarPath,
            'phone'   => $request->phone ?: 'Chưa cập nhật',
            'cccd'    => $request->cccd ?: 'Chưa cập nhật',
            'address' => $request->address ?: 'Chưa cập nhật',
            'status'  => 'Đang làm việc',
        ]);

        // Create User account if checkbox is checked
        if ($request->has('create_account') && in_array($request->account_role, ['delivery', 'admin'])) {
            $dummyEmail = $request->cccd ? $request->cccd . '@staff.ecommerce.local' : 'staff_' . time() . '@ecommerce.local';
            
            // Check if user account already exists
            $existingUser = \App\Models\User::where('email', $dummyEmail)
                ->orWhere(function ($query) use ($request) {
                    if ($request->phone) {
                        $query->where('phone', $request->phone);
                    }
                })->first();

            if ($existingUser) {
                return redirect()->back()->with('warning', 'Nhân viên đã được thêm nhưng tài khoản đăng nhập đã tồn tại (Email: ' . $existingUser->email . '). Không tạo tài khoản trùng.');
            }

            \App\Models\User::create([
                'name' => $request->name,
                'email' => $dummyEmail,
                'password' => '12345678',
                'role' => $request->account_role,
                'phone' => $request->phone,
                'address' => $request->address,
                'avatar' => $avatarPath,
                'cccd' => $request->cccd,
                'must_change_password' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm nhân viên mới & tạo tài khoản thành công!');
    }

    public function updateStaff(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'cccd'    => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $staff = Staff::findOrFail($id);
        
        if ($request->filled('avatar_base64')) {
            $base64 = $request->input('avatar_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $imageName = 'staff_' . time() . '.jpg';
            $path = public_path('uploads/staff');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($path . '/' . $imageName, base64_decode($file_data));
            
            if ($staff->avatar && file_exists(public_path($staff->avatar))) {
                @unlink(public_path($staff->avatar));
            }
            $staff->avatar = 'uploads/staff/' . $imageName;
        } elseif ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/staff'), $filename);
            
            if ($staff->avatar && file_exists(public_path($staff->avatar))) {
                @unlink(public_path($staff->avatar));
            }
            $staff->avatar = 'uploads/staff/' . $filename;
        }

        $updateData = [
            'name'    => $request->name,
            'role'    => $request->role,
            'phone'   => $request->phone ?: 'Chưa cập nhật',
            'cccd'    => $request->cccd ?: 'Chưa cập nhật',
            'address' => $request->address ?: 'Chưa cập nhật',
        ];

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $staff->update($updateData);

        // Đồng bộ sang tài khoản User tương ứng nếu có
        $userAccount = \App\Models\User::where(function ($query) use ($staff) {
            if (!empty($staff->cccd) && $staff->cccd !== 'Chưa cập nhật') {
                $query->orWhere('cccd', $staff->cccd);
            }
            if (!empty($staff->phone) && $staff->phone !== 'Chưa cập nhật') {
                $query->orWhere('phone', $staff->phone);
            }
            if (!empty($staff->name)) {
                $query->orWhere('name', $staff->name);
            }
        })->first();

        if ($userAccount) {
            $userUpdateData = [
                'name' => $staff->name,
            ];
            if ($staff->phone && $staff->phone !== 'Chưa cập nhật') {
                $userUpdateData['phone'] = $staff->phone;
            }
            if ($staff->cccd && $staff->cccd !== 'Chưa cập nhật') {
                $userUpdateData['cccd'] = $staff->cccd;
            }
            if ($staff->address && $staff->address !== 'Chưa cập nhật') {
                $userUpdateData['address'] = $staff->address;
            }
            if ($staff->avatar) {
                $userUpdateData['avatar'] = $staff->avatar;
            }
            $userAccount->update($userUpdateData);
        }

        return redirect()->back()->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }


    public function destroyStaff($id)
    {
        $staff = Staff::findOrFail($id);

        // Đồng bộ xóa tài khoản User liên kết nếu có
        $userAccount = User::where(function ($query) use ($staff) {
            if (!empty($staff->cccd) && $staff->cccd !== 'Chưa cập nhật') {
                $query->orWhere('cccd', $staff->cccd);
            }
            if (!empty($staff->phone) && $staff->phone !== 'Chưa cập nhật') {
                $query->orWhere('phone', $staff->phone);
            }
            if (!empty($staff->name)) {
                $query->orWhere('name', $staff->name);
            }
        })->first();

        if ($userAccount) {
            $userAccount->delete();
        }

        $staff->delete();
        return redirect()->route('admin.attendance')->with('success', 'Đã xóa nhân viên và tài khoản liên kết thành công!');
    }

    public function showStaffProfile($id)
    {
        $staff = Staff::with('attendances')->findOrFail($id);

        // Tìm tài khoản User liên kết nếu có
        $userAccount = \App\Models\User::where(function ($query) use ($staff) {
            if (!empty($staff->cccd) && $staff->cccd !== 'Chưa cập nhật') {
                $query->orWhere('cccd', $staff->cccd);
            }
            if (!empty($staff->phone) && $staff->phone !== 'Chưa cập nhật') {
                $query->orWhere('phone', $staff->phone);
            }
            if (!empty($staff->name)) {
                $query->orWhere('name', $staff->name);
            }
        })->first();

        // Thống kê chấm công
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentMonthDays = now()->daysInMonth;
        
        $attendancesThisMonth = $staff->attendances()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('is_present', true)
            ->count();

        $passedDays = min(now()->day, $currentMonthDays);
        $attendanceRate = $passedDays > 0 ? min(100, round(($attendancesThisMonth / $passedDays) * 100)) : 100;
            
        $totalPresentDays = $staff->attendances()->where('is_present', true)->count();
        $recentAttendances = $staff->attendances()->orderBy('date', 'desc')->take(14)->get();

        // Thống kê đơn hàng nếu là nhân viên giao hàng
        $deliveryStats = [
            'total' => 0,
            'completed' => 0,
            'delivering' => 0,
            'recent_orders' => collect(),
        ];

        if ($userAccount) {
            $deliveryOrders = Order::where('delivery_staff_id', $userAccount->id)->latest()->get();
            $deliveryStats['total'] = $deliveryOrders->count();
            $deliveryStats['completed'] = $deliveryOrders->where('delivery_status', 'completed')->count();
            $deliveryStats['delivering'] = $deliveryOrders->where('delivery_status', 'delivering')->count();
            $deliveryStats['recent_orders'] = $deliveryOrders->take(5);
        }

        return view('admin.staff.profile', compact(
            'staff',
            'userAccount',
            'attendancesThisMonth',
            'attendanceRate',
            'totalPresentDays',
            'recentAttendances',
            'deliveryStats'
        ));
    }

    // --- CHẤM CÔNG NHÂN VIÊN ---
    public function attendance(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        $date  = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        $staffs = Staff::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }])->get();

        $attendanceMatrix = [];
        foreach ($staffs as $staff) {
            $attendanceMatrix[$staff->id] = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $attendanceMatrix[$staff->id][$day] = false;
            }
            foreach ($staff->attendances as $att) {
                $day = Carbon::parse($att->date)->day;
                $attendanceMatrix[$staff->id][$day] = $att->is_present;
            }
        }

        $staffReports = Order::whereIn('delivery_status', ['completed', 'issue'])->with(['user', 'items.product', 'deliveryStaff'])->latest()->get();

        return view('admin.attendance.index', compact('staffs', 'daysInMonth', 'month', 'year', 'attendanceMatrix', 'date', 'staffReports'));
    }

    public function toggleAttendance(Request $request)
    {
        $request->validate([
            'staff_id'   => 'required|exists:staffs,id',
            'date'       => 'required|date',
            'is_present' => 'required|boolean',
        ]);

        Attendance::updateOrCreate(
            ['staff_id' => $request->staff_id, 'date' => $request->date],
            ['is_present' => $request->is_present]
        );

        return response()->json(['success' => true]);
    }

    public function updateStaffStatus(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $staff->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái làm việc!');
    }

    // --- IN HÓA ĐƠN XUẤT KHO ---
    public function printInvoice($id)
    {
        $export = ProductExport::with('product.category')->findOrFail($id);
        return view('admin.exports.invoice', compact('export'));
    }
}