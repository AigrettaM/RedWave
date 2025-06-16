@extends('dashboardlayout.app')

@section('page-title', 'Tambah Artikel Baru')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="text-center md:text-left">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto md:mx-0 mb-4">
                        <i class="fas fa-plus-circle text-green-600 text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Artikel Baru</h1>
                    <p class="text-gray-600">Buat artikel baru untuk dipublikasikan di website</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-edit text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-800 font-medium">
                            <i class="fas fa-newspaper text-blue-600 mr-2"></i>
                            Form Artikel Baru
                        </h3>
                        <p class="text-sm text-gray-600">Lengkapi semua informasi artikel dengan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informasi Dasar
                    </h4>
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading text-blue-600 mr-1"></i>
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 ring-2 ring-red-200 @enderror"
                               placeholder="Masukkan judul artikel yang menarik..."
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Gunakan judul yang menarik dan SEO-friendly (maksimal 60 karakter)
                        </p>
                    </div>

                    <!-- Slug Preview -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-link text-blue-600 mr-1"></i>
                            URL Slug (Preview)
                        </label>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-center text-sm">
                                <span class="text-gray-500">{{ url('/') }}/artikel/</span>
                                <span id="slugPreview" class="text-blue-600 font-medium">judul-artikel</span>
                            </div>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-blue-600 mr-1"></i>
                            Ringkasan Artikel
                        </label>
                        <textarea name="excerpt" 
                                  id="excerpt" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('excerpt') border-red-500 ring-2 ring-red-200 @enderror"
                                  placeholder="Tulis ringkasan singkat yang menggambarkan isi artikel...">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Ringkasan akan ditampilkan di halaman daftar artikel
                            </p>
                            <span id="excerptCount" class="text-xs text-gray-400">0/300</span>
                        </div>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                        Konten Artikel
                    </h4>
                    
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-paragraph text-blue-600 mr-1"></i>
                            Isi Artikel <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Editor Toolbar -->
                        <div class="border border-gray-300 rounded-t-lg bg-white p-2 flex flex-wrap gap-1" id="editorToolbar">
                            <button type="button" onclick="formatText('bold')" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Underline">
                                <i class="fas fa-underline"></i>
                            </button>
                            <div class="w-px bg-gray-300 mx-1"></div>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Bullet List">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" onclick="formatText('insertOrderedList')" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Numbered List">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div class="w-px bg-gray-300 mx-1"></div>
                            <button type="button" onclick="insertHeading()" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Heading">
                                <i class="fas fa-heading"></i>
                            </button>
                            <button type="button" onclick="insertLink()" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        
                        <textarea name="content" 
                                  id="content" 
                                  rows="20"
                                  class="w-full px-4 py-3 border border-gray-300 border-t-0 rounded-b-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 ring-2 ring-red-200 @enderror"
                                  placeholder="Tulis konten artikel di sini... Anda dapat menggunakan HTML tags untuk formatting."
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-code mr-1"></i>
                                Mendukung HTML tags untuk formatting
                            </p>
                            <span id="contentCount" class="text-xs text-gray-400">0 kata</span>
                        </div>
                    </div>
                </div>

                <!-- Category and Author Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Kategori & Penulis
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tags text-blue-600 mr-1"></i>
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category" 
                                    id="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 ring-2 ring-red-200 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $key => $value)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600 mr-1"></i>
                                Nama Penulis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   value="{{ old('author') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('author') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="Nama lengkap penulis"
                                   required>
                            @error('author')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Author Title -->
                    <div class="mt-6">
                        <label for="author_title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-badge text-blue-600 mr-1"></i>
                            Jabatan/Posisi Penulis
                        </label>
                        <input type="text" 
                               name="author_title" 
                               id="author_title" 
                               value="{{ old('author_title') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: Web Developer, Content Writer, CEO">
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Opsional - akan ditampilkan di bawah nama penulis
                        </p>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Media & Gambar
                    </h4>
                    
                    <!-- Featured Image -->
                    <div class="mb-6">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image text-blue-600 mr-1"></i>
                            Gambar Utama
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors" id="imageDropZone">
                            <input type="file" 
                                   name="featured_image" 
                                   id="featured_image" 
                                   class="hidden @error('featured_image') border-red-500 @enderror"
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            
                            <div id="imagePreview" class="hidden">
                                <img id="previewImg" class="max-w-full h-48 object-cover rounded-lg mx-auto mb-4" alt="Preview">
                                <button type="button" onclick="removeImage()" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Hapus Gambar
                                </button>
                            </div>
                            
                            <div id="imageUploadPrompt">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 mb-2">Klik untuk upload atau drag & drop gambar</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                                <button type="button" onclick="document.getElementById('featured_image').click()" 
                                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Pilih Gambar
                                </button>
                            </div>
                        </div>
                        @error('featured_image')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-hashtag text-blue-600 mr-2"></i>
                        Tags & Label
                    </h4>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tags text-blue-600 mr-1"></i>
                            Tags Artikel
                        </label>
                        <input type="text" 
                               name="tags" 
                               id="tags" 
                               value="{{ old('tags') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="laravel, php, web development, tutorial">
                        
                        <!-- Tags Preview -->
                        <div id="tagsPreview" class="mt-3 flex flex-wrap gap-2"></div>
                        
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pisahkan tags dengan koma. Tags membantu kategorisasi dan pencarian artikel.
                        </p>
                    </div>
                </div>

                <!-- Publishing Options -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Pengaturan Publikasi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on text-blue-600 mr-1"></i>
                                Status Publikasi <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                    📝 Draft - Simpan sebagai draft
                                </option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                    🌐 Published - Publikasikan sekarang
                                </option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Draft dapat diedit kapan saja, Published akan langsung tampil di website
                            </p>
                        </div>

                        <!-- Featured -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-star text-blue-600 mr-1"></i>
                                Pengaturan Khusus
                            </label>
                            <div class="bg-white border border-gray-300 rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           id="is_featured" 
                                           value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    <label for="is_featured" class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">Artikel Unggulan</span>
                                        <p class="text-xs text-gray-500 mt-1">Artikel akan ditampilkan di halaman utama dan mendapat prioritas lebih tinggi</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white border-t border-gray-200 px-6 py-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="w-full sm:w-auto bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    
                    <button type="submit" 
                            name="action" 
                            value="draft"
                            class="w-full sm:w-auto bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan sebagai Draft
                    </button>
                    
                    <button type="submit" 
                            name="action" 
                            value="publish"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Publikasikan Artikel
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mt-6 border border-blue-100">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-question-circle text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">💡 Tips Menulis Artikel</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-blue-700">
                        <div>
                            <h4 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-lightbulb mr-2"></i>
                                Judul yang Baik:
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Gunakan kata kunci yang relevan</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Maksimal 60 karakter untuk SEO</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Buat menarik dan informatif</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-3 flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Konten Berkualitas:
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Struktur yang jelas dengan heading</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Gunakan paragraf pendek</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 mt-2 flex-shrink-0"></span>
                                    <span>Sertakan gambar yang relevan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugPreview = document.getElementById('slugPreview');
    
    titleInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        slugPreview.textContent = slug || 'judul-artikel';
    });

    // Character counter for excerpt
    const excerptTextarea = document.getElementById('excerpt');
    const excerptCount = document.getElementById('excerptCount');
    
    excerptTextarea.addEventListener('input', function() {
        const count = this.value.length;
        excerptCount.textContent = `${count}/300`;
        
        if (count > 300) {
            excerptCount.classList.add('text-red-500');
            excerptCount.classList.remove('text-gray-400');
        } else {
            excerptCount.classList.remove('text-red-500');
            excerptCount.classList.add('text-gray-400');
        }
    });

    // Word counter for content
    const contentTextarea = document.getElementById('content');
    const contentCount = document.getElementById('contentCount');
    
    contentTextarea.addEventListener('input', function() {
        const words = this.value.trim().split(/\s+/).filter(word => word.length > 0).length;
        contentCount.textContent = `${words} kata`;
        
        // Color coding based on word count
        if (words < 300) {
            contentCount.classList.add('text-yellow-500');
            contentCount.classList.remove('text-green-500', 'text-gray-400');
        } else if (words >= 300) {
            contentCount.classList.add('text-green-500');
            contentCount.classList.remove('text-yellow-500', 'text-gray-400');
        }
    });

    // Tags preview and formatting
    const tagsInput = document.getElementById('tags');
    const tagsPreview = document.getElementById('tagsPreview');
    
    tagsInput.addEventListener('input', function() {
        const tags = this.value.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0);
        
        tagsPreview.innerHTML = '';
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            tagElement.innerHTML = `<i class="fas fa-tag mr-1"></i>${tag}`;
            tagsPreview.appendChild(tagElement);
        });
        
        if (tags.length === 0) {
            tagsPreview.innerHTML = '<span class="text-gray-400 text-xs">Tags akan muncul di sini...</span>';
        }
    });

    // Image drag and drop functionality
    const imageDropZone = document.getElementById('imageDropZone');
    const imageInput = document.getElementById('featured_image');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageDropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        imageDropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        imageDropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        imageDropZone.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    function unhighlight(e) {
        imageDropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }
    
    imageDropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            imageInput.files = files;
            previewImage(imageInput);
        }
    }

    // Auto-save functionality (draft)
    let autoSaveTimer;
    const formInputs = document.querySelectorAll('input, textarea, select');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSaveDraft, 30000); // Auto-save every 30 seconds
        });
    });

    // Form validation before submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const category = document.getElementById('category').value;
        const author = document.getElementById('author').value.trim();
        
        if (!title || !content || !category || !author) {
            e.preventDefault();
            showNotification('Harap lengkapi semua field yang wajib diisi!', 'error');
            return false;
        }
        
        // Show loading state
        const submitButtons = document.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(button => {
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 5000);
        });
    });
});

// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('File harus berupa gambar!', 'error');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('Ukuran file maksimal 2MB!', 'error');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('imageUploadPrompt').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Remove image function
function removeImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imageUploadPrompt').classList.remove('hidden');
}

// Text formatting functions for simple editor
function formatText(command) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = '';
    
    switch(command) {
        case 'bold':
            formattedText = `<strong>${selectedText || 'teks tebal'}</strong>`;
            break;
        case 'italic':
            formattedText = `<em>${selectedText || 'teks miring'}</em>`;
            break;
        case 'underline':
            formattedText = `<u>${selectedText || 'teks bergaris bawah'}</u>`;
            break;
        case 'insertUnorderedList':
            formattedText = `<ul>\n<li>${selectedText || 'item pertama'}</li>\n<li>item kedua</li>\n</ul>`;
            break;
        case 'insertOrderedList':
            formattedText = `<ol>\n<li>${selectedText || 'item pertama'}</li>\n<li>item kedua</li>\n</ol>`;
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + formattedText.length, start + formattedText.length);
}

function insertHeading() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    const heading = `<h2>${selectedText || 'Judul Bagian'}</h2>\n`;
    textarea.value = textarea.value.substring(0, start) + heading + textarea.value.substring(end);
    textarea.focus();
}

function insertLink() {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    const url = prompt('Masukkan URL:');
    if (url) {
        const link = `<a href="${url}" target="_blank">${selectedText || 'teks link'}</a>`;
        textarea.value = textarea.value.substring(0, start) + link + textarea.value.substring(end);
        textarea.focus();
    }
}

// Auto-save draft function
function autoSaveDraft() {
    const formData = new FormData();
    const form = document.querySelector('form');
    
    // Get form data
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        if (input.type !== 'file' && input.name) {
            formData.append(input.name, input.value);
        }
    });
    
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('auto_save', 'true');
    
    // Show auto-save indicator
    showNotification('Menyimpan draft otomatis...', 'info');
    
    // You can implement actual auto-save to server here
    // fetch('/admin/articles/auto-save', {
    //     method: 'POST',
    //     body: formData
    // }).then(response => {
    //     if (response.ok) {
    //         showNotification('Draft tersimpan otomatis', 'success');
    //     }
    // }).catch(error => {
    //     console.error('Auto-save failed:', error);
    // });
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white',
        warning: 'bg-yellow-500 text-white'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle',
        warning: 'fas fa-exclamation-triangle'
    };
    
    notification.className += ` ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icons[type]} mr-2"></i>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 4000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + S = Save as draft
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        document.querySelector('button[value="draft"]').click();
    }
    
    // Ctrl + Enter = Publish
    if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('button[value="publish"]').click();
    }
    
    // Ctrl + B = Bold
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        formatText('bold');
    }
    
    // Ctrl + I = Italic
    if (e.ctrlKey && e.key === 'i') {
        e.preventDefault();
        formatText('italic');
    }
    
    // Ctrl + U = Underline
    if (e.ctrlKey && e.key === 'u') {
        e.preventDefault();
        formatText('underline');
    }
});

// Form dirty state tracking
let formDirty = false;
const formInputs = document.querySelectorAll('input, textarea, select');

formInputs.forEach(input => {
    input.addEventListener('change', function() {
        formDirty = true;
    });
});

// Warn before leaving if form has unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (formDirty) {
        e.preventDefault();
        e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        return e.returnValue;
    }
});

// Clear dirty state when form is submitted
document.querySelector('form').addEventListener('submit', function() {
    formDirty = false;
});

// Initialize character counters on page load
document.getElementById('excerpt').dispatchEvent(new Event('input'));
document.getElementById('content').dispatchEvent(new Event('input'));
document.getElementById('tags').dispatchEvent(new Event('input'));
</script>

<style>
/* Custom scrollbar */
textarea::-webkit-scrollbar {
    width: 8px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Editor toolbar styling */
#editorToolbar button:hover {
    background-color: #f3f4f6;
    transform: translateY(-1px);
}

#editorToolbar button:active {
    transform: translateY(0);
}

/* Image drop zone animation */
#imageDropZone {
    transition: all 0.3s ease;
}

#imageDropZone:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

/* Form section animations */
.bg-gray-50 {
    transition: all 0.3s ease;
}

.bg-gray-50:hover {
    background-color: #f8fafc;
}

/* Focus ring improvements */
input:focus, textarea:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Loading state for buttons */
button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Responsive improvements */
@media (max-width: 768px) {
    #editorToolbar {
        flex-wrap: wrap;
        gap: 4px;
    }
    
    #editorToolbar button {
        padding: 8px;
        font-size: 12px;
    }
    
    .container {
        padding-left: 16px;
        padding-right: 16px;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
}

/* Animation for form sections */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-gray-50 {
    animation: slideIn 0.5s ease-out;
}

/* Tag preview animation */
#tagsPreview span {
    animation: slideIn 0.3s ease-out;
}

/* Character counter color transitions */
#excerptCount, #contentCount {
    transition: color 0.3s ease;
}
</style>
@endsection
