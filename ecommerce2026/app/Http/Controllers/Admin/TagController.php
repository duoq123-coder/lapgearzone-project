<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $tags = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tags,name',
            'type' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Vui lòng nhập tên tag.',
            'name.unique'   => 'Tag này đã tồn tại trong hệ thống.',
        ]);

        $name = trim($request->name);
        $slug = Str::slug($name);

        // Ensure unique slug
        $count = Tag::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        Tag::create([
            'name' => $name,
            'slug' => $slug,
            'type' => $request->input('type', 'hardware'),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', "Đã thêm tag '{$name}' thành công!");
    }

    /**
     * Quick store for AJAX call from product create/edit form.
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $name = trim($request->name);
        $slug = Str::slug($name);

        // Check if exists
        $tag = Tag::where('name', $name)->orWhere('slug', $slug)->first();

        if ($tag) {
            return response()->json([
                'success' => true,
                'tag'     => $tag,
                'existed' => true,
                'message' => 'Tag đã tồn tại, đã được chọn.',
            ]);
        }

        $tag = Tag::create([
            'name' => $name,
            'slug' => $slug,
            'type' => $request->input('type', 'hardware'),
        ]);

        return response()->json([
            'success' => true,
            'tag'     => $tag,
            'existed' => false,
            'message' => "Đã tạo tag '{$name}' thành công!",
        ]);
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tags,name,' . $tag->id,
            'type' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Vui lòng nhập tên tag.',
            'name.unique'   => 'Tên tag này đã trùng với tag khác.',
        ]);

        $name = trim($request->name);
        $slug = Str::slug($name);

        $tag->update([
            'name' => $name,
            'slug' => $slug,
            'type' => $request->input('type', $tag->type ?? 'hardware'),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', "Đã cập nhật tag '{$name}' thành công!");
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag)
    {
        $tagName = $tag->name;
        // Detach any products
        $tag->products()->detach();
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', "Đã xóa tag '{$tagName}' thành công!");
    }
}
