<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of all published news articles.
     */
    public function index(Request $request)
    {
        $query = News::published();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $newsList = $query->latest('published_at')->latest('id')->paginate(8)->withQueryString();
        
        $categories = News::published()
            ->select('category')
            ->distinct()
            ->pluck('category');

        $recentNews = News::published()
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('news.index', compact('newsList', 'categories', 'recentNews'));
    }

    /**
     * Display the specified news article.
     */
    public function show($slug)
    {
        $news = News::published()
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug);
                if (is_numeric($slug)) {
                    $query->orWhere('id', $slug);
                }
            })
            ->firstOrFail();

        // Tăng lượt xem (tránh tăng lặp nếu cùng session)
        $viewedKey = 'viewed_news_' . $news->id;
        if (!session()->has($viewedKey)) {
            $news->increment('views_count');
            session()->put($viewedKey, true);
        }

        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->where(function ($q) use ($news) {
                $q->where('category', $news->category)
                  ->orWhere('id', '!=', $news->id);
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
