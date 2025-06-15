@extends('dashboardlayout.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Artikel
            </h2>
            <p class="mt-1 text-sm text-gray-500">{{ $article->title }}</p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.articles.show', $article) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View
            </a>
            <a href="{{ route('admin.articles.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $article->title) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700">Ringkasan</label>
                <textarea name="excerpt" 
                          id="excerpt" 
                          rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('excerpt') border-red-500 @enderror"
                          placeholder="Ringkasan singkat artikel...">{{ old('excerpt', $article->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                <textarea name="content" 
                          id="content" 
                          rows="15"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('content') border-red-500 @enderror"
                          required>{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Row: Category and Author -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" 
                            id="category"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-500 @enderror"
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

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Nama Penulis</label>
                    <input type="text" 
                           name="author" 
                           id="author" 
                           value="{{ old('author', $article->author) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('author') border-red-500 @enderror"
                           required>
                    @error('author')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Featured Image -->
            @if($article->featured_image)
            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                <div class="mt-1">
                    <img src="{{ asset('storage/' . $article->featured_image) }}" 
                         alt="{{ $article->title }}"
                         class="h-32 w-auto object-cover rounded-lg">
                </div>
            </div>
            @endif

            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700">
                    {{ $article->featured_image ? 'Ganti Gambar Utama' : 'Gambar Utama' }}
                </label>
                <input type="file" 
                       name="featured_image" 
                       id="featured_image" 
                       accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('featured_image') border-red-500 @enderror">
                @if($article->featured_image)
                    <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengganti gambar</p>
                @endif
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                <input type="text" 
                       name="tags" 
                       id="tags" 
                       value="{{ old('tags', is_array($article->tags) ? implode(', ', $article->tags) : '') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Pisahkan dengan koma (contoh: laravel, php, web development)">
                <p class="mt-1 text-sm text-gray-500">Pisahkan tags dengan koma</p>
            </div>

            <!-- Row: Status and Featured -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" 
                            id="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring
