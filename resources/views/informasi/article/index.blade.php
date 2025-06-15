@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24">
    <!-- Hero Section with Red Theme -->
    <section class="bg-gradient-to-r from-red-800 to-red-600 text-white py-20" style="background: linear-gradient(135deg, #B31312 0%, #8B0000 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Artikel RedWave</h1>
                <p class="text-xl md:text-2xl mb-8">Temukan wawasan, tips, dan cerita dari para ahli kami</p>
                <div class="flex justify-center">
                    <div class="relative max-w-md w-full">
                        <form action="{{ route('article.index') }}" method="GET" class="flex">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Cari artikel..." 
                                   class="px-6 py-3 rounded-l-full text-gray-800 w-full focus:outline-none focus:ring-2 focus:ring-white">
                            <button type="submit" class="px-6 py-3 text-white rounded-r-full hover:bg-red-700 transition-colors" style="background-color: #B31312;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Categories -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('article.index', ['category' => 'all']) }}" 
                   class="px-6 py-2 rounded-full transition-colors {{ request('category', 'all') === 'all' ? 'text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                   @if(request('category', 'all') === 'all') style="background-color: #B31312;" @endif>
                    Semua
                </a>
                @if(isset($categories))
                    @foreach($categories as $category)
                        <a href="{{ route('article.index', ['category' => $category]) }}" 
                           class="px-6 py-2 rounded-full transition-colors {{ request('category') === $category ? 'text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                           @if(request('category') === $category) style="background-color: #B31312;" @endif>
                            {{ ucfirst($category) }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Article Section -->
    @if(isset($featuredArticle) && $featuredArticle)
    <section class="py-12" id="highlight">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: #B31312;">Artikel Unggulan</h2>
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden border-t-4" style="border-top-color: #B31312;">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <img src="{{ $featuredArticle->featured_image_url ?? 'https://via.placeholder.com/600x400' }}" alt="{{ $featuredArticle->title }}" class="w-full h-64 md:h-full object-cover">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center mb-4">
                            <span class="text-white text-xs font-semibold px-2.5 py-0.5 rounded" style="background-color: #B31312;">{{ ucfirst($featuredArticle->category) }}</span>
                            <span class="text-gray-500 text-sm ml-4">{{ $featuredArticle->formatted_date ?? $featuredArticle->created_at->format('d F Y') }}</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">{{ $featuredArticle->title }}</h3>
                        <p class="text-gray-600 mb-6">{{ $featuredArticle->excerpt }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ $featuredArticle->author_avatar_url ?? 'https://via.placeholder.com/40x40' }}" alt="{{ $featuredArticle->author_name }}" class="w-10 h-10 rounded-full mr-3 object-cover">
                                <div>
                                    <p class="font-semibold text-sm">{{ $featuredArticle->author_name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $featuredArticle->author_title }}</p>
                                </div>
                            </div>
                            <a href="{{ route('article.show', $featuredArticle->slug) }}" class="text-white px-4 py-2 rounded hover:bg-red-700 transition-colors" style="background-color: #B31312;">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Articles Grid -->
    <section class="py-12 bg-gray-50" id="articles">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: #B31312;">
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @elseif(request('category') && request('category') !== 'all')
                    Artikel {{ ucfirst(request('category')) }}
                @else
                    Artikel Terbaru
                @endif
            </h2>
            
            @if(isset($articles) && $articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border-t-4" style="border-top-color: #B31312;">
                        <img src="{{ $article->featured_image_url ?? 'https://via.placeholder.com/400x200' }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="text-white text-xs font-semibold px-2.5 py-0.5 rounded" style="background-color: #B31312;">{{ ucfirst($article->category) }}</span>
                                <span class="text-gray-500 text-sm ml-3">{{ $article->formatted_date ?? $article->created_at->format('d F Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ $article->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($article->excerpt, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $article->author_avatar_url ?? 'https://via.placeholder.com/32x32' }}" alt="{{ $article->author_name }}" class="w-8 h-8 rounded-full mr-2 object-cover">
                                    <span class="text-sm font-semibold">{{ $article->author_name }}</span>
                                </div>
                                <a href="{{ route('article.show', $article->slug) }}" class="font-semibold hover:text-red-800 transition-colors" style="color: #B31312;">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-4">
                        @if(request('search'))
                            Tidak ada artikel yang ditemukan untuk pencarian "{{ request('search') }}"
                        @else
                            Belum ada artikel yang tersedia
                        @endif
                    </div>
                    <a href="{{ route('article.index') }}" class="text-white px-6 py-3 rounded-full hover:bg-red-700 transition-colors" style="background-color: #B31312;">
                        Lihat Semua Artikel
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
