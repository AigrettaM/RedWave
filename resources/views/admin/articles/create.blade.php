@extends('dashboardlayout.app')

@section('title', 'Tambah Artikel Baru')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Tambah Artikel Baru
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
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
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title') }}"
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
                          placeholder="Ringkasan singkat artikel...">{{ old('excerpt') }}</textarea>
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
                          required>{{ old('content') }}</textarea>
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
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                           value="{{ old('author') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('author') border-red-500 @enderror"
                           required>
                    @error('author')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                <input type="file" 
                       name="featured_image" 
                       id="featured_image" 
                       accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('featured_image') border-red-500 @enderror">
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
                       value="{{ old('tags') }}"
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
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <!-- Featured -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" 
                           name="is_featured" 
                           id="is_featured" 
                           value="1"
                           {{ old('is_featured') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                        Jadikan artikel unggulan
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('admin.articles.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Simpan Artikel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto generate slug from title
document.getElementById('title').addEventListener('input', function() {
    // You can add slug generation logic here if needed
});

// Preview image
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Add image preview logic here if needed
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
