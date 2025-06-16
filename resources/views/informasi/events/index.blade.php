{{-- resources/views/informasi/events/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Events - HMTI UNKHAIR')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Events Redwave</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Ikuti berbagai kegiatan menarik 
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <h2 class="text-2xl font-bold text-gray-800">Upcoming Events</h2>
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $events->total() }} Events
                </span>
            </div>
            
            <a href="{{ route('informasi.events.create') }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>
                Ajukan Event
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
<!-- Event Image -->
<div class="relative h-48 overflow-hidden">
    @if($event->image)
        <img src="{{ asset('storage/events/' . $event->image) }}" 
             alt="{{ $event->title }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
    @else
        <!-- Gunakan warna sebagai background -->
        <div class="w-full h-full flex items-center justify-center text-white group-hover:scale-105 transition-transform duration-300"
             style="background: linear-gradient(135deg, {{ $event->background_color }}, {{ $event->background_color }}dd);">
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">{{ $event->initials }}</div>
                <p class="text-sm font-medium opacity-90 px-4">{{ Str::limit($event->title, 30) }}</p>
            </div>
        </div>
    @endif
    
    <!-- Event Date Overlay -->
    <div class="absolute top-4 left-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-2 text-center shadow-lg">
        <div class="text-xs font-medium text-gray-600 uppercase">
            {{ \Carbon\Carbon::parse($event->event_date)->format('M') }}
        </div>
        <div class="text-lg font-bold text-gray-800">
            {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
        </div>
    </div>
</div>


                        <!-- Event Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                {{ $event->title }}
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-calendar mr-2 text-red-500"></i>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d F Y') }}
                                </div>
                                
                                @if($event->start_time)
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class="fas fa-clock mr-2 text-red-500"></i>
                                        {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                        @if($event->end_time)
                                            - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex items-center text-gray-600 text-sm">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    {{ Str::limit($event->location, 30) }}
                                </div>
                                
                                @if($event->max_participants)
                                    <div class="flex items-center text-gray-600 text-sm">
                                        <i class="fas fa-users mr-2 text-red-500"></i>
                                        Max {{ $event->max_participants }} peserta
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('informasi.events.show', $event) }}" 
                               class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-3 rounded-lg font-medium transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-lg shadow-lg p-4">
                        {{ $events->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="bg-gray-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Belum Ada Event</h3>
                    <p class="text-gray-600 mb-8">Saat ini belum ada event yang tersedia. Silakan ajukan event Anda atau periksa kembali nanti.</p>
                    <a href="{{ route('events.create') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-medium transition-colors inline-flex items-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Ajukan Event Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
