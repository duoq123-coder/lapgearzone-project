<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // ========================================== 
    // GIỎ HÀNG - CART MANAGEMENT
    // ========================================== 

    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $cartCount = $cartItems->sum('quantity');

        // Tính giảm giá nếu có Coupon trong Session
        $discountAmount = 0;
        $couponCode = Session::get('coupon_code');
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();
        }

        // Tự động tìm voucher tốt nhất nếu chưa áp dụng hoặc voucher hiện tại không hợp lệ
        if (!Session::get('coupon_removed') && (!$couponCode || !$coupon || $total < $coupon->min_order_value)) {
            $userRole = Auth::user()->role;
            $roleWeights = [
                'customer' => 0,
                'customer_bronze' => 1,
                'customer_silver' => 2,
                'customer_gold' => 3,
                'customer_diamond' => 4,
                'customer_emerald' => 5,
            ];
            
            $userWeight = $roleWeights[$userRole] ?? 0;

            // Tìm tất cả các auto coupon có thể áp dụng
            $autoCoupons = Coupon::where('is_active', true)
                ->where('is_auto_apply', true)
                ->where('min_order_value', '<=', $total)
                ->where(function($query) {
                    $query->whereNull('usage_limit')
                          ->orWhereColumn('used', '<', 'usage_limit');
                })
                ->where(function($q) {
                    $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                })
                ->get()
                ->filter(function($c) use ($userWeight, $roleWeights) {
                    if (empty($c->required_tier)) return true;
                    $reqWeight = $roleWeights[$c->required_tier] ?? 99;
                    return $userWeight >= $reqWeight;
                });

            if ($autoCoupons->count() > 0) {
                // Tính xem cái nào giảm nhiều nhất
                $bestCoupon = null;
                $maxDiscount = 0;

                foreach ($autoCoupons as $c) {
                    if ($c->type === 'fixed') {
                        $disc = min($total, $c->value);
                    } else {
                        $disc = ($total * $c->value) / 100;
                        if ($c->max_discount_amount && $c->max_discount_amount > 0) {
                            $disc = min($disc, $c->max_discount_amount);
                        }
                    }

                    if ($disc > $maxDiscount) {
                        $maxDiscount = $disc;
                        $bestCoupon = $c;
                    }
                }

                if ($bestCoupon) {
                    $coupon = $bestCoupon;
                    $couponCode = $coupon->code;
                    Session::put('coupon_code', $couponCode);
                }
            }
        }

        if ($coupon && $total >= $coupon->min_order_value && $coupon->isValidNow()) {
            if ($coupon->type === 'fixed') {
                $discountAmount = min($total, $coupon->value); // Không giảm quá tổng tiền
            } else {
                $discountAmount = ($total * $coupon->value) / 100;
                if ($coupon->max_discount_amount && $coupon->max_discount_amount > 0) {
                    $discountAmount = min($discountAmount, $coupon->max_discount_amount);
                }
            }
        } else {
            // Xóa coupon nếu không còn đủ điều kiện và không có auto coupon thay thế
            Session::forget(['coupon_code', 'discount_amount']);
            $couponCode = null;
        }

        // Lấy danh sách tất cả các voucher khả dụng cho user (để hiển thị nút chọn)
        $userRole = Auth::user()->role;
        $roleWeights = [
            'customer' => 0,
            'customer_bronze' => 1,
            'customer_silver' => 2,
            'customer_gold' => 3,
            'customer_diamond' => 4,
            'customer_emerald' => 5,
        ];
        $userWeight = $roleWeights[$userRole] ?? 0;

        $availableCoupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereColumn('used', '<', 'usage_limit');
            })
            ->where(function($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('min_order_value', 'asc')
            ->get()
            ->filter(function($c) use ($userWeight, $roleWeights) {
                if (empty($c->required_tier)) return true;
                $reqWeight = $roleWeights[$c->required_tier] ?? 99;
                return $userWeight >= $reqWeight;
            })->values();

        Session::put('discount_amount', $discountAmount);
        $finalTotal = max(0, $total - $discountAmount);

        return view('cart.index', compact('cartItems', 'total', 'cartCount', 'discountAmount', 'finalTotal', 'couponCode', 'availableCoupons'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
        ]);

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Cập nhật số lượng nếu đã có
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->quantity) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => 'Số lượng vượt quá tồn kho.'], 422);
                }
                return back()->with('error', 'Số lượng vượt quá tồn kho.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Tạo item mới
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        $message = 'Đã thêm ' . $product->name . ' vào giỏ hàng!';

        if ($request->ajax() || $request->wantsJson()) {
            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
            return response()->json([
                'success' => $message,
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request, Cart $cart)
    {
        // Kiểm tra quyền sở hữu
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Không có quyền cập nhật giỏ hàng này.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->quantity,
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(Cart $cart)
    {
        // Kiểm tra quyền sở hữu
        if ($cart->user_id !== Auth::id()) {
            return back()->with('error', 'Không có quyền xóa giỏ hàng này.');
        }

        $productName = $cart->product->name;
        $cart->delete();

        return back()->with('success', 'Đã xóa ' . $productName . ' khỏi giỏ hàng!');
    }

    /**
     * Áp dụng mã giảm giá
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $code = strtoupper($request->coupon_code);
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if (!$coupon->is_active) {
            return back()->with('error', 'Mã giảm giá đã tạm thời bị khóa.');
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return back()->with('error', 'Mã giảm giá chưa đến thời gian áp dụng (bắt đầu lúc ' . $coupon->starts_at->format('H:i d/m/Y') . ').');
        }

        if ($coupon->ends_at && $coupon->ends_at->isPast()) {
            return back()->with('error', 'Mã giảm giá đã hết hạn sử dụng (hết hạn lúc ' . $coupon->ends_at->format('H:i d/m/Y') . ').');
        }

        if ($coupon->usage_limit !== null && $coupon->used >= $coupon->usage_limit) {
            return back()->with('error', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        // Kiểm tra thứ hạng
        if (!empty($coupon->required_tier)) {
            $userRole = Auth::user()->role;
            $roleWeights = [
                'customer' => 0,
                'customer_bronze' => 1,
                'customer_silver' => 2,
                'customer_gold' => 3,
                'customer_diamond' => 4,
                'customer_emerald' => 5,
            ];
            
            $userWeight = $roleWeights[$userRole] ?? 0;
            $reqWeight = $roleWeights[$coupon->required_tier] ?? 99;

            if ($userWeight < $reqWeight) {
                return back()->with('error', 'Hạng thành viên của bạn chưa đủ để áp dụng mã giảm giá này.');
            }
        }

        // Tính tổng tiền giỏ hàng hiện tại
        $total = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

        if ($total < $coupon->min_order_value) {
            return back()->with('error', 'Đơn hàng tối thiểu để áp dụng mã này là ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ.');
        }

        // Lưu vào Session
        Session::put('coupon_code', $code);
        Session::forget('coupon_removed');

        return back()->with('success', 'Đã áp dụng mã giảm giá thành công!');
    }

    /**
     * Bỏ áp dụng mã giảm giá
     */
    public function removeCoupon()
    {
        Session::forget(['coupon_code', 'discount_amount']);
        Session::put('coupon_removed', true);
        return back()->with('success', 'Đã bỏ mã giảm giá.');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
