@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pt-24">
    <!-- Article Header -->
    <section class="bg-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('article.index') }}" class="hover:text-red-600">Artikel</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-900">{{ $article->title ?? 'Detail Artikel' }}</li>
                </ol>
            </nav>

            <!-- Article Meta -->
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <span class="text-white text-sm font-semibold px-3 py-1 rounded" style="background-color: #B31312;">
                        {{ ucfirst($article->category ?? 'Artikel') }}
                    </span>
                    <span class="text-gray-500 text-sm ml-4">
                        {{ isset($article->formatted_date) ? $article->formatted_date : (isset($article->created_at) ? $article->created_at->format('d F Y') : date('d F Y')) }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $article->title ?? 'Judul Artikel' }}
                </h1>
                <p class="text-xl text-gray-600 mb-6">
                    {{ $article->excerpt ?? 'Deskripsi artikel...' }}
                </p>
            </div>

            <!-- Author Info -->
            <div class="flex items-center mb-8 pb-8 border-b border-gray-200">
                <img src="{{ $article->author_avatar_url ?? 'https://via.placeholder.com/60x60' }}" 
                     alt="{{ $article->author_name ?? 'Author' }}" 
                     class="w-12 h-12 rounded-full mr-4 object-cover">
                <div>
                    <p class="font-semibold text-gray-900">{{ $article->author_name ?? 'Penulis' }}</p>
                    <p class="text-gray-600 text-sm">{{ $article->author_title ?? 'Kontributor' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if(isset($article->featured_image_url) || isset($article->featured_image))
    <section class="mb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <img src="{{ $article->featured_image_url ?? $article->featured_image ?? 'https://via.placeholder.com/800x400' }}" 
                 alt="{{ $article->title ?? 'Article Image' }}" 
                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
        </div>
    </section>
    @endif

    <!-- Article Content -->
    <section class="pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="prose prose-lg max-w-none">
                    @if(isset($article->content))
                        {!! nl2br(e($article->content)) !!}
                    @else
                        <p>Konten artikel tidak tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Related Articles -->
    @if(isset($relatedArticles) && $relatedArticles->count() > 0)
    <section class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: #B31312;">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedArticles as $related)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border-t-4" style="border-top-color: #B31312;">
                    <img src="{{ $related->featured_image_url ?? 'https://via.placeholder.com/400x200' }}" 
                         alt="{{ $related->title }}" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <span class="text-white text-xs font-semibold px-2.5 py-0.5 rounded" style="background-color: #B31312;">
                                {{ ucfirst($related->category) }}
                            </span>
                            <span class="text-gray-500 text-sm ml-3">
                                {{ $related->formatted_date ?? $related->created_at->format('d F Y') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold mb-3">{{ $related->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($related->excerpt, 100) }}</p>
                        <a href="{{ route('article.show', $related->slug) }}" 
                           class="font-semibold hover:text-red-800 transition-colors" 
                           style="color: #B31312;">
                            Baca Selengkapnya
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Back to Articles -->
    <section class="py-8 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('article.index') }}" 
               class="inline-flex items-center px-6 py-3 text-white rounded-full hover:bg-red-700 transition-colors" 
               style="background-color: #B31312;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Artikel
            </a>
        </div>
    </section>
</div>
@endsection
