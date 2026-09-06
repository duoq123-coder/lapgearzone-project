<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $news = $query->latest('id')->paginate(10)->withQueryString();
        
        // Lấy danh sách thể loại để lọc
        $categories = News::select('category')->distinct()->pluck('category');

        return view('admin.news.index', compact('news', 'categories'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        $categories = News::select('category')->distinct()->pluck('category');
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'summary'      => 'required|string|max:600',
            'content'      => 'required|string',
            'author_name'  => 'nullable|string|max:100',
            'read_time'    => 'nullable|string|max:50',
            'image'        => 'nullable|image|max:5120',
            'image_base64' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required'    => 'Vui lòng nhập tiêu đề bài viết.',
            'category.required' => 'Vui lòng chọn hoặc nhập thể loại tin tức.',
            'summary.required'  => 'Vui lòng nhập đoạn tóm tắt ngắn.',
            'content.required'  => 'Vui lòng nhập nội dung bài viết.',
            'image.image'       => 'Tệp tải lên phải là hình ảnh (jpg, png, webp, v.v.).',
            'image.max'         => 'Dung lượng ảnh tối đa 5MB.',
        ]);

        // Tạo slug duy nhất
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $news = new News();
        $news->title        = $validated['title'];
        $news->slug         = $slug;
        $news->category     = $validated['category'];
        $news->summary      = $validated['summary'];
        $news->content      = $validated['content'];
        $news->author_name  = $validated['author_name'] ?: (Auth::user()->name ?? 'Ban Biên Tập LapGearZone');
        $news->read_time    = $validated['read_time'] ?: '5 phút đọc';
        $news->is_published = $request->has('is_published');
        $news->published_at = $news->is_published ? now() : null;

        // Xử lý lưu ảnh: ưu tiên ảnh crop (base64) nếu có
        if ($request->filled('image_base64')) {
            $base64 = $request->input('image_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $ext = (strpos($type, 'jpeg') !== false || strpos($type, 'jpg') !== false) ? 'jpg' : 'png';
            $imageName = 'news_' . time() . '_' . Str::random(6) . '.' . $ext;
            Storage::disk('public')->put('news/' . $imageName, base64_decode($file_data));
            $news->image = 'news/' . $imageName;
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Đã tạo và đăng bài viết mới thành công!');
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news)
    {
        $categories = News::select('category')->distinct()->pluck('category');
        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'summary'      => 'required|string|max:600',
            'content'      => 'required|string',
            'author_name'  => 'nullable|string|max:100',
            'read_time'    => 'nullable|string|max:50',
            'image'        => 'nullable|image|max:5120',
            'image_base64' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required'    => 'Vui lòng nhập tiêu đề bài viết.',
            'category.required' => 'Vui lòng chọn hoặc nhập thể loại tin tức.',
            'summary.required'  => 'Vui lòng nhập đoạn tóm tắt ngắn.',
            'content.required'  => 'Vui lòng nhập nội dung bài viết.',
            'image.image'       => 'Tệp tải lên phải là hình ảnh.',
            'image.max'         => 'Dung lượng ảnh tối đa 5MB.',
        ]);

        // Cập nhật slug nếu tiêu đề thay đổi
        if ($news->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $news->slug = $slug;
        }

        $news->title        = $validated['title'];
        $news->category     = $validated['category'];
        $news->summary      = $validated['summary'];
        $news->content      = $validated['content'];
        $news->author_name  = $validated['author_name'] ?: $news->author_name;
        $news->read_time    = $validated['read_time'] ?: $news->read_time;
        
        $wasPublished = $news->is_published;
        $news->is_published = $request->has('is_published');
        if ($news->is_published && !$wasPublished) {
            $news->published_at = now();
        }

        // Xử lý thay đổi ảnh đại diện (ưu tiên ảnh crop base64)
        if ($request->filled('image_base64')) {
            // Xóa ảnh cũ nếu nó nằm trong storage
            if ($news->image && !Str::startsWith($news->image, ['images/', 'http://', 'https://'])) {
                Storage::disk('public')->delete($news->image);
            }
            $base64 = $request->input('image_base64');
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data)      = explode(',', $file_data);
            $ext = (strpos($type, 'jpeg') !== false || strpos($type, 'jpg') !== false) ? 'jpg' : 'png';
            $imageName = 'news_' . time() . '_' . Str::random(6) . '.' . $ext;
            Storage::disk('public')->put('news/' . $imageName, base64_decode($file_data));
            $news->image = 'news/' . $imageName;
        } elseif ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu nó nằm trong storage
            if ($news->image && !Str::startsWith($news->image, ['images/', 'http://', 'https://'])) {
                Storage::disk('public')->delete($news->image);
            }
            $path = $request->file('image')->store('news', 'public');
            $news->image = $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật bài viết thành công!');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(News $news)
    {
        // Xóa ảnh nếu có trong storage
        if ($news->image && !Str::startsWith($news->image, ['images/', 'http://', 'https://'])) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Đã xóa bài viết thành công!');
    }

    /**
     * Quick toggle published status
     */
    public function togglePublish(News $news)
    {
        $news->is_published = !$news->is_published;
        if ($news->is_published && !$news->published_at) {
            $news->published_at = now();
        }
        $news->save();

        $statusText = $news->is_published ? 'Hiển thị' : 'Ẩn';
        return redirect()->back()->with('success', "Đã chuyển trạng thái bài viết sang: {$statusText}!");
    }
}
