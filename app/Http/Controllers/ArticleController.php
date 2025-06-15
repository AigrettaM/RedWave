<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::published()->orderBy('published_at', 'desc');

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        $articles = $query->paginate(9);
        $featuredArticle = Article::published()->featured()->latest()->first();
        $categories = Article::published()->distinct()->pluck('category');

        return view('informasi.article.index', compact('articles', 'featuredArticle', 'categories'));
    }

    public function show($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();
        $relatedArticles = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->limit(3)
            ->get();

        return view('informasi.article.show', compact('article', 'relatedArticles'));
    }
}
