<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    // Categories untuk dropdown
    private function getCategories()
    {
        return [
            'web-development' => 'Web Development',
            'mobile-development' => 'Mobile Development',
            'data-science' => 'Data Science',
            'artificial-intelligence' => 'Artificial Intelligence',
            'cybersecurity' => 'Cybersecurity',
            'cloud-computing' => 'Cloud Computing',
            'devops' => 'DevOps',
            'programming' => 'Programming',
            'tutorial' => 'Tutorial',
            'news' => 'News'
        ];
    }

    // Admin: Menampilkan daftar artikel untuk admin
    public function adminIndex()
    {
        try {
            // Ambil data langsung dari model tanpa mapping kompleks
            $articles = Article::orderBy('created_at', 'desc')->get();
            
            return view('admin.articles.index', compact('articles'));
        } catch (\Exception $e) {
            Log::error('Error fetching articles: ' . $e->getMessage());
            return view('admin.articles.index', ['articles' => collect()]);
        }
    }

    // Admin: Tampilkan form create
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.articles.create', compact('categories'));
    }

    // Admin: Simpan artikel baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'author' => 'required|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean'
        ]);

        try {
            $data = $request->all();
            
            // Generate slug
            $data['slug'] = Str::slug($request->title);
            
            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
            }
            
            // Process tags
            if ($request->tags) {
                $data['tags'] = array_map('trim', explode(',', $request->tags));
            }
            
            // Set published_at if status is published
            if ($request->status === 'published') {
                $data['published_at'] = now();
            }
            
            // Handle checkbox
            $data['is_featured'] = $request->has('is_featured');
            
            Article::create($data);
            
            return redirect()->route('admin.articles.index')
                           ->with('success', 'Artikel berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            Log::error('Error creating article: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan artikel. Silakan coba lagi.');
        }
    }

    // Admin: Show detail artikel
    public function adminShow(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    // Admin: Tampilkan form edit
    public function edit(Article $article)
    {
        $categories = $this->getCategories();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    // Admin: Update artikel
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'author' => 'required|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean'
        ]);

        try {
            $data = $request->all();
            
            // Generate slug if title changed
            if ($request->title !== $article->title) {
                $data['slug'] = Str::slug($request->title);
            }
            
            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($article->featured_image) {
                    Storage::disk('public')->delete($article->featured_image);
                }
                $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
            }
            
            // Process tags
            if ($request->tags) {
                $data['tags'] = array_map('trim', explode(',', $request->tags));
            } else {
                $data['tags'] = null;
            }
            
            // Set published_at if status changed to published
            if ($request->status === 'published' && $article->status !== 'published') {
                $data['published_at'] = now();
            } elseif ($request->status === 'draft') {
                $data['published_at'] = null;
            }
            
            // Handle checkbox
            $data['is_featured'] = $request->has('is_featured');
            
            $article->update($data);
            
            return redirect()->route('admin.articles.index')
                           ->with('success', 'Artikel berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            Log::error('Error updating article: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui artikel. Silakan coba lagi.');
        }
    }

    // Admin: Hapus artikel
    public function destroy(Article $article)
    {
        try {
            // Delete featured image if exists
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $article->delete();
            
            return redirect()->route('admin.articles.index')
                           ->with('success', 'Artikel berhasil dihapus!');
                           
        } catch (\Exception $e) {
            Log::error('Error deleting article: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }
    }

    // Admin: Toggle status artikel
    public function toggleStatus(Article $article)
    {
        try {
            $newStatus = $article->status === 'published' ? 'draft' : 'published';
            
            $article->update([
                'status' => $newStatus,
                'published_at' => $newStatus === 'published' ? now() : null
            ]);
            
            $message = $newStatus === 'published' ? 'Artikel berhasil dipublikasikan!' : 'Artikel berhasil dijadikan draft!';
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error toggling article status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah status artikel.');
        }
    }

    // Admin: Toggle featured artikel
    public function toggleFeatured(Article $article)
    {
        try {
            $article->update([
                'is_featured' => !$article->is_featured
            ]);
            
            $message = $article->is_featured ? 'Artikel berhasil dijadikan featured!' : 'Artikel berhasil dihapus dari featured!';
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Error toggling article featured: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah status featured artikel.');
        }
    }

    // Frontend: Menampilkan daftar artikel untuk pengunjung
    public function index()
    {
        $articles = Article::published()
                          ->orderBy('published_at', 'desc')
                          ->paginate(12);
        
        return view('articles.index', compact('articles'));
    }

    // Frontend: Menampilkan detail artikel
    public function show($slug)
    {
        $article = Article::where('slug', $slug)
                         ->where('status', 'published')
                         ->firstOrFail();
        
        // Increment views
        $article->increment('views');
        
        return view('articles.show', compact('article'));
    }
}
