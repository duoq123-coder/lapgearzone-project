<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $events = Event::with(['products' => function ($q) {
            $q->select('products.id', 'products.name', 'products.price', 'products.image', 'products.quantity', 'products.category_id')
              ->with('category');
        }])
        ->withCount('products')
        ->orderBy('sort_order', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();

        // Đồng bộ danh sách sản phẩm nổi bật cho mục Top Laptops
        $featuredProducts = Product::where('is_featured', true)
            ->select('products.id', 'products.name', 'products.price', 'products.image', 'products.quantity', 'products.category_id')
            ->with('category')
            ->get();

        foreach ($events as $event) {
            if ($event->slug === 'top-laptops' || Str::slug($event->name) === 'top-laptops') {
                $event->setRelation('products', $featuredProducts);
                $event->products_count = $featuredProducts->count();
            }
        }

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $products = Product::with('category')
            ->orderByRaw('quantity > 0 DESC')
            ->orderBy('name', 'asc')
            ->get();

        $maxSort = Event::max('sort_order') ?? 0;
        $nextSort = $maxSort + 1;

        return view('admin.events.create', compact('products', 'nextSort'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events,slug',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_type' => 'nullable|in:slider,banner,both',
            'banner_link' => 'nullable|string|max:500',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $uploadDir = public_path('uploads/events');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file = $request->file('banner_image');
            $fileName = 'event_banner_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $bannerPath = 'uploads/events/' . $fileName;
        }

        $displayType = $validated['display_type'] ?? 'slider';
        // Tự động gán là banner nếu người dùng upload banner mà chưa chọn type
        if ($bannerPath && empty($validated['display_type'])) {
            $displayType = !empty($request->product_ids) ? 'both' : 'banner';
        }

        $event = Event::create([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : null,
            'headline' => $validated['headline'] ?: $validated['name'],
            'description' => $validated['description'] ?? null,
            'display_type' => $displayType,
            'banner_link' => $validated['banner_link'] ?? null,
            'banner_image' => $bannerPath,
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->filled('product_ids')) {
            $syncData = [];
            foreach ($request->product_ids as $index => $prodId) {
                $syncData[$prodId] = ['sort_order' => $index + 1];
            }
            $event->products()->sync($syncData);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Đã tạo sự kiện "' . $event->name . '" thành công' . ($bannerPath ? ' với Banner riêng biệt!' : '!'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        if ($event->slug === 'top-laptops' || Str::slug($event->name) === 'top-laptops') {
            return redirect()->route('admin.events.index')
                ->with('warning', 'Mục Top Laptops là mục cố định tự động đồng bộ từ các Sản phẩm Nổi Bật, không thể chỉnh sửa thủ công.');
        }

        $event->load(['products' => function ($q) {
            $q->with('category');
        }]);

        $products = Product::with('category')
            ->orderByRaw('quantity > 0 DESC')
            ->orderBy('name', 'asc')
            ->get();

        $selectedProductIds = $event->products->pluck('id')->toArray();

        return view('admin.events.edit', compact('event', 'products', 'selectedProductIds'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->slug === 'top-laptops' || Str::slug($event->name) === 'top-laptops') {
            return redirect()->route('admin.events.index')
                ->with('warning', 'Mục Top Laptops là mục cố định tự động đồng bộ từ các Sản phẩm Nổi Bật, không thể chỉnh sửa thủ công.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events,slug,' . $event->id,
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'display_type' => 'nullable|in:slider,banner,both',
            'banner_link' => 'nullable|string|max:500',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'remove_banner' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $bannerPath = $event->banner_image;

        if ($request->boolean('remove_banner')) {
            if ($bannerPath && File::exists(public_path($bannerPath))) {
                File::delete(public_path($bannerPath));
            }
            $bannerPath = null;
        }

        if ($request->hasFile('banner_image')) {
            if ($bannerPath && File::exists(public_path($bannerPath))) {
                File::delete(public_path($bannerPath));
            }
            $uploadDir = public_path('uploads/events');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file = $request->file('banner_image');
            $fileName = 'event_banner_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $bannerPath = 'uploads/events/' . $fileName;
        }

        $displayType = $validated['display_type'] ?? $event->display_type ?? 'slider';
        if ($bannerPath && empty($validated['display_type'])) {
            $displayType = !empty($request->product_ids) ? 'both' : 'banner';
        }

        $event->update([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : $event->slug,
            'headline' => $validated['headline'] ?: $validated['name'],
            'description' => $validated['description'] ?? null,
            'display_type' => $displayType,
            'banner_link' => $validated['banner_link'] ?? null,
            'banner_image' => $bannerPath,
            'sort_order' => $request->input('sort_order', $event->sort_order),
            'is_active' => $request->boolean('is_active'),
        ]);

        $syncData = [];
        if ($request->filled('product_ids')) {
            foreach ($request->product_ids as $index => $prodId) {
                $syncData[$prodId] = ['sort_order' => $index + 1];
            }
        }
        $event->products()->sync($syncData);

        return redirect()->route('admin.events.index')
            ->with('success', 'Đã cập nhật sự kiện "' . $event->name . '" thành công!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->slug === 'top-laptops' || Str::slug($event->name) === 'top-laptops') {
            return redirect()->route('admin.events.index')
                ->with('error', 'Không thể xóa mục Top Laptops vì đây là mục mặc định tự đồng bộ từ các Sản phẩm Nổi Bật.');
        }

        $name = $event->name;
        if ($event->banner_image && File::exists(public_path($event->banner_image))) {
            File::delete(public_path($event->banner_image));
        }

        $event->products()->detach();
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Đã xóa sự kiện "' . $name . '" thành công!');
    }

    /**
     * Quick toggle active status.
     */
    public function toggleActive(Event $event)
    {
        if ($event->slug === 'top-laptops' || Str::slug($event->name) === 'top-laptops') {
            return back()->with('warning', 'Mục Top Laptops là mục cố định luôn luôn bật để chiếu sản phẩm nổi bật.');
        }

        $event->is_active = !$event->is_active;
        $event->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $event->is_active,
                'message' => 'Đã ' . ($event->is_active ? 'bật' : 'tắt') . ' hiển thị sự kiện "' . $event->name . '" trên cửa hàng.',
            ]);
        }

        return back()->with('success', 'Đã ' . ($event->is_active ? 'bật' : 'tắt') . ' hiển thị sự kiện "' . $event->name . '" trên cửa hàng.');
    }
}
