<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller 
{ 
    // ========================================== 
    // KHU VỰC QUẢN LÝ DÀNH CHO ADMIN (Resource Methods) 
    // ========================================== 

    public function index(Request $request) 
    { 
        $query = Product::with(['category', 'tags']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tag')) {
            $tagFilter = $request->tag;
            $query->whereHas('tags', function($q) use ($tagFilter) {
                $q->where('slug', $tagFilter)->orWhere('tags.id', $tagFilter);
            });
        }

        // Sắp xếp ID tăng dần từ nhỏ đến lớn
        $products = $query->orderBy('id', 'asc')->paginate(10)->withQueryString(); 
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::withCount('products')->orderBy('name', 'asc')->get();

        return view('admin.products.index', compact('products', 'categories', 'tags')); 
    } 

    public function create() 
    { 
        $categories = Category::orderBy('name', 'asc')->get(); 
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'tags')); 
    } 

    public function store(Request $request) 
    { 
        $validatedData = $request->validate([ 
            'name'        => 'required|string|max:255', 
            'description' => 'nullable|string', 
            'price'       => 'required|numeric|min:0', 
            'category_id' => 'required|exists:categories,id', 
            'image'       => 'nullable|image|max:2048',
            // Thêm validate cho file 3D (giới hạn 50MB)
            'model_3d'    => 'nullable|file|max:51200', 
            'is_featured' => 'nullable|boolean',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]); 

        // Khởi tạo số lượng tồn kho ban đầu bằng 0
        $product = new Product($validatedData);
        $product->quantity = 0;
        $product->is_featured = $request->boolean('is_featured');

        if ($request->filled('image_base64')) {
            $base64 = $request->input('image_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $ext = (strpos($type, 'jpeg') !== false || strpos($type, 'jpg') !== false) ? 'jpg' : 'png';
            $imageName = 'product_' . time() . '.' . $ext;
            Storage::disk('public')->put('products/' . $imageName, base64_decode($file_data));
            $product->image = 'products/' . $imageName;
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        // Xử lý lưu file 3D
        if ($request->hasFile('model_3d')) {
            $path3d = $request->file('model_3d')->store('models', 'public');
            $product->model_3d = $path3d;
        }

        $product->save();

        if ($request->has('tags')) {
            $product->tags()->sync($request->input('tags', []));
        }

        return redirect()->route('admin.products.index') 
            ->with('success', 'Thêm sản phẩm thành công! Hãy tạo phiếu Nhập kho để cập nhật số lượng tồn.'); 
    } 

    public function show(Product $product) 
    { 
        $product->load('tags');
        return view('admin.products.show', compact('product')); 
    } 

    public function edit(Product $product) 
    { 
        $categories = Category::orderBy('name', 'asc')->get(); 
        $tags = Tag::orderBy('name', 'asc')->get();
        $product->load('tags');
        return view('admin.products.edit', compact('product', 'categories', 'tags')); 
    } 

    public function update(Request $request, Product $product) 
    { 
        $validatedData = $request->validate([ 
            'name'        => 'required|string|max:255', 
            'description' => 'nullable|string', 
            'price'       => 'required|numeric|min:0', 
            'category_id' => 'required|exists:categories,id', 
            'image'       => 'nullable|image|max:2048',
            // Thêm validate cho file 3D
            'model_3d'    => 'nullable|file|max:51200',
            'is_featured' => 'nullable|boolean',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]); 

        if ($request->filled('image_base64')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $base64 = $request->input('image_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $ext = (strpos($type, 'jpeg') !== false || strpos($type, 'jpg') !== false) ? 'jpg' : 'png';
            $imageName = 'product_' . time() . '.' . $ext;
            Storage::disk('public')->put('products/' . $imageName, base64_decode($file_data));
            $validatedData['image'] = 'products/' . $imageName;
        } elseif ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $path;
        }

        // Xử lý cập nhật file 3D
        if ($request->hasFile('model_3d')) {
            if ($product->model_3d) {
                Storage::disk('public')->delete($product->model_3d);
            }
            $path3d = $request->file('model_3d')->store('models', 'public');
            $validatedData['model_3d'] = $path3d;
        }

        $validatedData['is_featured'] = $request->boolean('is_featured');
        $product->update($validatedData); 

        $product->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.products.index') 
            ->with('success', 'Cập nhật thông tin sản phẩm thành công!'); 
    } 

    public function destroy(Product $product) 
    { 
        // Xóa file 3D nếu có khi xóa sản phẩm
        if ($product->model_3d) {
            Storage::disk('public')->delete($product->model_3d);
        }
        $product->delete();
        return redirect()->back() 
            ->with('success', 'Xóa sản phẩm thành công!'); 
    } 

    public function destroyImage(Product $product)
    {
        if (!$product->image) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không có ảnh để xóa.'], 400);
            }
            return redirect()->back()->with('error', 'Sản phẩm không có ảnh để xóa.');
        }

        Storage::disk('public')->delete($product->image);
        $product->image = null;
        $product->save();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã xóa ảnh sản phẩm.']);
        }
        return redirect()->back()->with('success', 'Đã xóa ảnh sản phẩm.');
    }

    public function editProductImages(Product $product)
    {
        $images = $product->images()->orderBy('order')->get();
        return view('admin.products.edit-images', compact('product', 'images'));
    }

    public function uploadProductImages(Request $request, Product $product)
    {
        try {
            if ($request->filled('image_base64')) {
                $base64 = $request->input('image_base64');
                @list($type, $file_data) = explode(';', $base64);
                @list(, $file_data)      = explode(',', $file_data);
                $ext = (strpos($type, 'jpeg') !== false || strpos($type, 'jpg') !== false) ? 'jpg' : 'png';
                $imageName = 'product_detail_' . time() . '.' . $ext;
                Storage::disk('public')->put('product-details/' . $imageName, base64_decode($file_data));
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'product-details/' . $imageName,
                    'is_primary' => false,
                    'order'      => ProductImage::where('product_id', $product->id)->max('order') + 1,
                ]);
            } elseif ($request->hasFile('images')) {
                $request->validate([
                    'images.*' => 'required|image|max:2048',
                ]);
                foreach ($request->file('images') as $image) {
                    $path = $image->store('product-details', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'order'      => ProductImage::where('product_id', $product->id)->max('order') + 1,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Tải lên ảnh thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroyProductImage(ProductImage $productImage)
    {
        Storage::disk('public')->delete($productImage->image_path);
        $productImage->delete();

        return redirect()->back()->with('success', 'Đã xóa ảnh chi tiết.');
    }

    public function setPrimaryImage(ProductImage $productImage)
    {
        ProductImage::where('product_id', $productImage->product_id)->update(['is_primary' => false]);
        $productImage->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Đã đặt ảnh làm ảnh chính.');
    }

    public function reorderImages(Request $request, Product $product)
    {
        $request->validate([
            'orders' => 'required|array',
        ]);

        try {
            foreach ($request->orders as $imageId => $order) {
                ProductImage::where('id', $imageId)->update(['order' => $order]);
            }

            return response()->json(['success' => true, 'message' => 'Sắp xếp lại thứ tự ảnh thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra.'], 500);
        }
    }

    // ========================================== 
    // KHU VỰC DÀNH CHO NGƯỜI DÙNG THƯỜNG (User Methods) 
    // ========================================== 
    public function userIndex(Request $request) 
    { 
        $query = Product::with(['category', 'images', 'tags']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('tags', function($tq) use ($search) {
                      $tq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('slug', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tag')) {
            $tagFilters = array_filter(explode(',', $request->tag));
            if (count($tagFilters) > 0) {
                foreach ($tagFilters as $tagFilter) {
                    $query->whereHas('tags', function($q) use ($tagFilter) {
                        $q->where('slug', trim($tagFilter))->orWhere('tags.id', trim($tagFilter));
                    });
                }
            }
        }

        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Luôn ưu tiên hiển thị sản phẩm còn hàng lên trước
        $query->orderByRaw('quantity > 0 DESC');

        $hasFilters = $request->filled('search') || $request->filled('category') || $request->filled('tag') || $request->filled('min_price') || $request->filled('max_price');

        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } elseif ($request->sort === 'wishlist_desc') {
                $query->withCount('wishlists')->orderByDesc('wishlists_count');
            } elseif ($request->sort === 'sales_desc') {
                $query->withCount('orderItems')->orderByDesc('order_items_count');
            }
        } else {
            if ($hasFilters) {
                $query->latest();
            } else {
                // Random order with seed to prevent pagination issues
                $seed = session()->get('product_random_seed');
                if (!$seed || $request->get('page', 1) == 1) {
                    $seed = rand();
                    session()->put('product_random_seed', $seed);
                }
                $query->orderByRaw('RAND(' . $seed . ')');
            }
        }

        $products = $query->paginate(16)->withQueryString(); 
        $categories = Category::withCount('products')->orderBy('name', 'asc')->get();
        $allTags = Tag::withCount('products')->orderBy('products_count', 'desc')->orderBy('name', 'asc')->get();

        $wishlistIds = Auth::check() 
            ? Auth::user()->wishlists()->pluck('product_id')->toArray() 
            : [];

        $newsList = \App\Models\News::published()->latest('published_at')->latest('id')->take(3)->get();

        // Lấy đánh giá nổi bật (4-5 sao) mới nhất, kèm user + product
        $featuredReviews = \App\Models\Review::with(['user', 'product'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(4)
            ->get();

        return view('products.index', compact('products', 'categories', 'allTags', 'wishlistIds', 'newsList', 'featuredReviews')); 
    } 

    public function show_normal(Product $product) 
    { 
        $product->load(['category', 'images', 'tags', 'reviews.user']);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'tags'])
            ->limit(4)
            ->get();

        $wishlistIds = Auth::check() 
            ? Auth::user()->wishlists()->pluck('product_id')->toArray() 
            : [];

        return view('products.show', compact('product', 'relatedProducts', 'wishlistIds')); 
    } 
}