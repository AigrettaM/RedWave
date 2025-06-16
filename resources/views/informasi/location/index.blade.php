@extends('layouts.app')

@section('title', 'Lokasi Donor Darah')

@section('content')
<div class="container mx-auto px-4 py-20">
  <!-- Header -->
  <div class="text-center mb-8">
      <h1 class="text-4xl font-bold text-gray-800 mb-4">Lokasi Donor Darah</h1>
      <p class="text-lg text-gray-600">Temukan lokasi donor darah terdekat di sekitar Anda</p>
  </div>

  <!-- Filter dan Pencarian -->
  <div class="bg-white rounded-lg shadow-md p-6 mb-8">
      <form method="GET" action="{{ route('location.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Pencarian -->
          <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cari Lokasi</label>
              <input type="text" 
                     name="search" 
                     value="{{ request('search') }}"
                     placeholder="Nama lokasi, alamat, atau kota..."
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
          </div>

          <!-- Filter Kota -->
          <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
              <select name="kota" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                  <option value="">Semua Kota</option>
                  @foreach($kotas as $kota)
                      <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                          {{ $kota }}
                      </option>
                  @endforeach
              </select>
          </div>

          <!-- Filter Jenis -->
          <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
              <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                  <option value="">Semua Jenis</option>
                  @foreach($jenisOptions as $jenis)
                      <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                          {{ ucfirst($jenis) }}
                      </option>
                  @endforeach
              </select>
          </div>

          <!-- Tombol -->
          <div class="flex items-end">
              <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                  Cari
              </button>
          </div>
      </form>
  </div>

  <!-- Grid Lokasi -->
  @if($lokasis->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          @foreach($lokasis as $lokasi)
              <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                  <!-- Gambar -->
                  <div class="h-48 bg-gray-200 overflow-hidden">
                      @if($lokasi->gambar && $lokasi->gambar_exists)
                          <img src="{{ $lokasi->gambar_url }}" 
                               alt="{{ $lokasi->nama }}"
                               class="w-full h-full object-cover">
                      @else
                          <div class="w-full h-full flex items-center justify-center bg-gray-100">
                              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                              </svg>
                          </div>
                      @endif
                  </div>

                  <!-- Konten -->
                  <div class="p-6">
                      <!-- Badge Jenis -->
                      <div class="mb-2">
                          <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                              {{ $lokasi->jenis == 'provinsi' ? 'bg-blue-100 text-blue-800' : 
                                 ($lokasi->jenis == 'kota' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                              {{ ucfirst($lokasi->jenis) }}
                          </span>
                      </div>

                      <!-- Nama -->
                      <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $lokasi->nama }}</h3>

                      <!-- Alamat -->
                      <div class="flex items-start mb-2">
                          <svg class="w-4 h-4 text-gray-500 mt-1 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <p class="text-sm text-gray-600">{{ $lokasi->alamat_lengkap ?? $lokasi->alamat . ', ' . $lokasi->kota }}</p>
                      </div>

                      <!-- Jam Operasional -->
                      <div class="flex items-center mb-2">
                          <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <p class="text-sm text-gray-600">
                              {{ $lokasi->jam_operasional ?? ($lokasi->jam_buka && $lokasi->jam_tutup ? $lokasi->jam_buka . ' - ' . $lokasi->jam_tutup : 'Jam operasional tidak tersedia') }}
                          </p>
                      </div>

                      <!-- Kontak -->
                      @if($lokasi->kontak)
                          <div class="flex items-center mb-4">
                              <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                              </svg>
                              <p class="text-sm text-gray-600">{{ $lokasi->kontak }}</p>
                          </div>
                      @endif

                      <!-- Kapasitas -->
                      @if($lokasi->kapasitas)
                          <div class="flex items-center mb-4">
                              <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                              </svg>
                              <p class="text-sm text-gray-600">Kapasitas: {{ $lokasi->kapasitas }} orang</p>
                          </div>
                      @endif

                      <!-- Tombol Detail -->
                      <div class="mt-4">
                          <a href="{{ route('location.show', $lokasi->id) }}" 
                             class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-block text-center">
                              Lihat Detail
                          </a>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>

      <!-- Pagination -->
      <div class="flex justify-center">
          {{ $lokasis->withQueryString()->links() }}
      </div>
  @else
      <!-- Empty State -->
      <div class="text-center py-12">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada lokasi ditemukan</h3>
          <p class="text-gray-600 mb-4">Coba ubah filter pencarian atau kata kunci Anda.</p>
          <a href="{{ route('location.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
              Reset Filter
          </a>
      </div>
  @endif
</div>
@endsection