@extends('dashboardlayout.app')

@section('page-title', 'Edit Artikel')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-edit mr-3 text-blue-600"></i>Edit Artikel
                    </h1>
                    <p class="text-gray-600">{{ $article->title }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                        <span class="flex items-center">
                            <i class="fas fa-user mr-1"></i>{{ $article->author }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>{{ $article->created_at->format('d M Y') }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-eye mr-1"></i>{{ $article->views ?? 0 }} views
                        </span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-600"></i>Judul Artikel
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title', $article->title) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror"
                               placeholder="Masukkan judul artikel yang menarik..."
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-blue-600"></i>Ringkasan Artikel
                        </label>
                        <textarea name="excerpt" 
                                  id="excerpt" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('excerpt') border-red-500 @enderror"
                                  placeholder="Tulis ringkasan singkat yang menarik untuk artikel ini...">{{ old('excerpt', $article->excerpt) }}</textarea>
                        @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Ringkasan akan ditampilkan di halaman daftar artikel dan social media</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt mr-2 text-blue-600"></i>Konten Artikel
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="15"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('content') border-red-500 @enderror"
                                  placeholder="Tulis konten artikel lengkap di sini..."
                                  required>{{ old('content', $article->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Gunakan Markdown untuk formatting yang lebih baik</p>
                    </div>

                    <!-- Category and Author Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-folder mr-2 text-blue-600"></i>Kategori
                            </label>
                            <select name="category" 
                                    id="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category', $article->category) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-edit mr-2 text-blue-600"></i>Penulis
                            </label>
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   value="{{ old('author', $article->author) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('author') border-red-500 @enderror"
                                   placeholder="Nama penulis artikel"
                                   required>
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Author Title and Featured Image Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="author_title" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-badge mr-2 text-blue-600"></i>Jabatan Penulis
                            </label>
                            <input type="text" 
                                   name="author_title" 
                                   id="author_title" 
                                   value="{{ old('author_title', $article->author_title) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Contoh: Senior Web Developer">
                            <p class="mt-1 text-sm text-gray-500">Opsional - akan ditampilkan di bawah nama penulis</p>
                        </div>
                        
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-image mr-2 text-blue-600"></i>Gambar Utama
                            </label>
                            <input type="file" 
                                   name="featured_image" 
                                   id="featured_image" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('featured_image') border-red-500 @enderror"
                                   accept="image/*">
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($article->featured_image)
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Gambar saat ini:</p>
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                             alt="Current featured image" 
                                             class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600">{{ basename($article->featured_image) }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Upload gambar baru untuk mengganti</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tags mr-2 text-blue-600"></i>Tags
                        </label>
                        <input type="text" 
                               name="tags" 
                               id="tags" 
                               value="{{ old('tags', $article->tags ? implode(', ', $article->tags) : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="laravel, php, web development, tutorial">
                        <p class="mt-1 text-sm text-gray-500">Pisahkan tags dengan koma. Tags membantu pembaca menemukan artikel</p>
                        
                        @if($article->tags && is_array($article->tags))
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Tags saat ini:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($article->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Status and Featured Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-2 text-blue-600"></i>Status Publikasi
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>
                                    📝 Draft - Belum dipublikasikan
                                </option>
                                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>
                                    🌐 Published - Dapat dilihat publik
                                </option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Draft hanya bisa dilihat oleh admin</p>
                        </div>
                        
                        <div class="flex items-end">
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-star mr-2 text-blue-600"></i>Pengaturan Khusus
                                </label>
                                <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           id="is_featured" 
                                           value="1"
                                           {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}
                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                    <label for="is_featured" class="ml-3 text-sm font-medium text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Jadikan artikel unggulan
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Artikel unggulan akan ditampilkan di halaman utama</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center font-medium">
                                <i class="fas fa-save mr-2"></i>Update Artikel
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.articles.index') }}" 
                               class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-eye text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total Views</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $article->views ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-calendar text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Dibuat</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $article->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-edit text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Terakhir Edit</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $article->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center">
                    <div class="p-2 {{ $article->status === 'published' ? 'bg-green-100' : 'bg-gray-100' }} rounded-lg">
                        <i class="fas {{ $article->status === 'published' ? 'fa-check-circle text-green-600' : 'fa-clock text-gray-600' }}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="text-lg font-semibold {{ $article->status === 'published' ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $article->status === 'published' ? 'Published' : 'Draft' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-save draft functionality
let autoSaveTimeout;
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input, textarea, select');

inputs.forEach(input => {
    input.addEventListener('input', () => {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic here
            console.log('Auto-saving draft...');
        }, 3000);
    });
});

// Character counter for excerpt
const excerptTextarea = document.getElementById('excerpt');
if (excerptTextarea) {
    const maxLength = 200;
    const counter = document.createElement('div');
    counter.className = 'text-sm text-gray-500 mt-1';
    excerptTextarea.parentNode.appendChild(counter);
    
    function updateCounter() {
        const remaining = maxLength - excerptTextarea.value.length;
        counter.textContent = `${excerptTextarea.value.length}/${maxLength} karakter`;
        counter.className = remaining < 20 ? 'text-sm text-red-500 mt-1' : 'text-sm text-gray-500 mt-1';
    }
    
    excerptTextarea.addEventListener('input', updateCounter);
    updateCounter();
}
</script>
@endsection
