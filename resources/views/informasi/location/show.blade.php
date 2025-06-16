@extends('layouts.app')

@section('title', $lokasi->nama)

@section('content')
<div class="container mx-auto px-4 py20">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a></li>
            <li><span>/</span></li>
            <li><a href="{{ route('public.lokasi.index') }}" class="hover:text-red-600">Lokasi Donor</a></li>
            <li><span>/</span></li>
            <li class="text-gray-800">{{ $lokasi->nama }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Konten Utama -->
        <div class="lg:col-span-2">
            <!-- Gambar -->
            <div class="mb-6">
                @if($lokasi->gambar)
                    <img src="{{ $lokasi->gambar_url }}" 
                         alt="{{ $lokasi->nama }}"
                         class="w-full h-64 md:h-80 object-cover rounded-lg shadow-md">
                @else
                    <div class="w-full h-64 md:h-80 bg-gray-100 rounded-lg shadow-md flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Informasi Utama -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $lokasi->nama }}</h1>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                            {{ $lokasi->jenis == 'provinsi' ? 'bg-blue-100 text-blue-800' : 
                               ($lokasi->jenis == 'kota' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($lokasi->jenis) }}
                        </span>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        {{ $lokasi->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($lokasi->status) }}
                    </span>
                </div>

                <!-- Deskripsi -->
                @if($lokasi->deskripsi)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $lokasi->deskripsi }}</p>
                    </div>
                @endif

                <!-- Detail Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Alamat -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Alamat</h3>
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-gray-700">{{ $lokasi->alamat }}</p>
                                    <p class="text-gray-600">{{ $lokasi->kota }}, {{ $lokasi->provinsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Jam -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Kontak & Jam Operasional</h3>
                        <div class="space-y-2">
                            @if($lokasi->kontak)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-700">{{ $lokasi->kontak }}</p>
                                </div>
                            @endif
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-700">{{ $lokasi->jam_operasional }}</p>
                            </div>

                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h.5a1 1 0 011 1v9a1 1 0 01-1-1V8a1 1 0 011-1H8z"></path>
                                </svg>
                                <p class="text-gray-700">Mulai operasional: {{ $lokasi->tanggal_operasional->format('d F Y') }}</p>
                            </div>

                            @if($lokasi->kapasitas)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-700">Kapasitas: {{ $lokasi->kapasitas }} orang</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peta (jika ada koordinat) -->
            @if($lokasi->latitude && $lokasi->longitude)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Lokasi di Peta</h3>
                    <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">Peta akan ditampilkan di sini</p>
                        <p class="text-sm text-gray-400 ml-2">({{ $lokasi->latitude }}, {{ $lokasi->longitude }})</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- CTA Card -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-red-800 mb-3">Siap Donor Darah?</h3>
                <p class="text-red-700 text-sm mb-4">Bergabunglah dengan para pahlawan yang menyelamatkan nyawa melalui donor darah.</p>
                <a href="#" class="inline-block w-full text-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                    Daftar Donor
                </a>
            </div>

            <!-- Info Tambahan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Penting</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p>Bawa KTP/identitas diri</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p>Istirahat cukup sebelum donor</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p>Makan makanan bergizi</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p>Minum air putih yang cukup</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-8">
        <a href="{{ route('public.lokasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar Lokasi
        </a>
    </div>
</div>
@endsection