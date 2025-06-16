@extends('layouts.app')

@section('title', $lokasi->nama)

@section('content')
<div class="container mx-auto px-4 py-20">
  <!-- Breadcrumb -->
  <nav class="mb-6">
      <ol class="flex items-center space-x-2 text-sm text-gray-600">
          <li><a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a></li>
          <li><span class="mx-2">/</span></li>
          <li><a href="{{ route('location.index') }}" class="hover:text-red-600">Lokasi</a></li>
          <li><span class="mx-2">/</span></li>
          <li class="text-gray-800 font-medium">{{ $lokasi->nama }}</li>
      </ol>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Kolom Utama -->
      <div class="lg:col-span-2">
          <!-- Gambar Utama -->
          <div class="mb-6">
              @php
                  $hasImage = !empty($lokasi->gambar);
                  $imageExists = $hasImage ? $lokasi->gambar_exists : false;
                  $imageUrl = $hasImage ? $lokasi->gambar_url : null;
              @endphp
              
              @if($hasImage && $imageExists && $imageUrl)
                  <img src="{{ $imageUrl }}" 
                       alt="{{ $lokasi->nama }}"
                       class="w-full h-64 md:h-80 object-cover rounded-lg shadow-lg"
                       onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                  <!-- Fallback jika gambar gagal load -->
                  <div class="w-full h-64 md:h-80 flex items-center justify-center bg-gray-100 rounded-lg shadow-lg" style="display: none;">
                      <div class="text-center">
                          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                          <p class="text-gray-500">Gambar tidak dapat dimuat</p>
                      </div>
                  </div>
              @else
                  <!-- Default placeholder -->
                  <div class="w-full h-64 md:h-80 flex items-center justify-center bg-gray-100 rounded-lg shadow-lg">
                      <div class="text-center">
                          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                          <p class="text-gray-500">Tidak ada gambar tersedia</p>
                      </div>
                  </div>
              @endif
          </div>

          <!-- Header -->
          <div class="mb-6">
              <div class="flex items-center justify-between mb-4">
                  <div>
                      <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $lokasi->nama }}</h1>
                      <div class="flex items-center space-x-3">
                          <!-- Badge Jenis -->
                          <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                              {{ $lokasi->jenis == 'provinsi' ? 'bg-blue-100 text-blue-800' : 
                                 ($lokasi->jenis == 'kota' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                              {{ ucfirst($lokasi->jenis) }}
                          </span>
                          <!-- Badge Status -->
                          <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                              {{ $lokasi->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                              {{ ucfirst($lokasi->status) }}
                          </span>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Deskripsi -->
          @if($lokasi->deskripsi)
              <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                  <h2 class="text-xl font-semibold text-gray-800 mb-3">Tentang Lokasi</h2>
                  <p class="text-gray-600 leading-relaxed">{{ $lokasi->deskripsi }}</p>
              </div>
          @endif

          <!-- Peta Google Maps -->
          @if($lokasi->latitude && $lokasi->longitude)
              <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                  <h2 class="text-xl font-semibold text-gray-800 mb-4">Lokasi di Peta</h2>
                  <div id="map" class="h-64 w-full rounded-lg"></div>
                  <p class="text-xs text-gray-500 mt-2">Koordinat: {{ $lokasi->latitude }}, {{ $lokasi->longitude }}</p>
              </div>
          @endif
      </div>

      <!-- Sidebar Informasi -->
      <div class="lg:col-span-1">
          <!-- Informasi Kontak -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kontak</h3>
              
              <!-- Alamat -->
              <div class="flex items-start mb-4">
                  <svg class="w-5 h-5 text-gray-500 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  <div>
                      <p class="text-sm font-medium text-gray-800">Alamat</p>
                      <p class="text-sm text-gray-600">{{ $lokasi->alamat_lengkap }}</p>
                  </div>
              </div>

              <!-- Kontak -->
              @if($lokasi->kontak)
                  <div class="flex items-center mb-4">
                      <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      <div>
                          <p class="text-sm font-medium text-gray-800">Telepon</p>
                          <a href="tel:{{ $lokasi->kontak }}" class="text-sm text-red-600 hover:text-red-700">{{ $lokasi->kontak }}</a>
                      </div>
                  </div>
              @endif

              <!-- Jam Operasional -->
              <div class="flex items-start mb-4">
                  <svg class="w-5 h-5 text-gray-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <div>
                      <p class="text-sm font-medium text-gray-800">Jam Operasional</p>
                      <p class="text-sm text-gray-600">{{ $lokasi->jam_operasional }}</p>
                  </div>
              </div>

              <!-- Kapasitas -->
              @if($lokasi->kapasitas)
                  <div class="flex items-center mb-4">
                      <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                      <div>
                          <p class="text-sm font-medium text-gray-800">Kapasitas</p>
                          <p class="text-sm text-gray-600">{{ $lokasi->kapasitas }} orang</p>
                      </div>
                  </div>
              @endif

              <!-- Tanggal Operasional -->
              @if($lokasi->tanggal_operasional)
                  <div class="flex items-center">
                      <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      <div>
                          <p class="text-sm font-medium text-gray-800">Beroperasi Sejak</p>
                          <p class="text-sm text-gray-600">{{ $lokasi->tanggal_operasional->format('d F Y') }}</p>
                      </div>
                  </div>
              @endif
          </div>

          <!-- Tombol Aksi -->
          <div class="bg-white rounded-lg shadow-md p-6 mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>

              <!-- Tombol Arah -->
              @if($lokasi->latitude && $lokasi->longitude)
                  <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lokasi->latitude }},{{ $lokasi->longitude }}" 
                     target="_blank"
                     class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center mb-3">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"></path>
                      </svg>
                      Petunjuk Arah
                  </a>
              @endif

              <!-- Tombol Telepon -->
              @if($lokasi->kontak)
                  <a href="tel:{{ $lokasi->kontak }}" 
                     class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                      </svg>
                      Hubungi
                  </a>
              @endif
          </div>

          <!-- Informasi Tambahan -->
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
              <div class="flex items-start">
                  <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <div>
                      <h4 class="text-sm font-medium text-yellow-800 mb-1">Informasi Penting</h4>
                      <p class="text-sm text-yellow-700">
                          Pastikan Anda memenuhi syarat donor darah sebelum datang. 
                          Bawa identitas diri yang masih berlaku.
                      </p>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Tombol Kembali -->
  <div class="mt-8">
      <a href="{{ route('location.index') }}" 
         class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Kembali ke Daftar Lokasi
      </a>
  </div>
</div>

<!-- Google Maps Script -->
@if($lokasi->latitude && $lokasi->longitude)
<script>
function initMap() {
  // Koordinat lokasi
  const lokasi = { 
      lat: {{ $lokasi->latitude }}, 
      lng: {{ $lokasi->longitude }} 
  };
  
  // Buat peta
  const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: lokasi,
      mapTypeControl: true,
      streetViewControl: true,
      fullscreenControl: true,
  });
  
  // Tambahkan marker
  const marker = new google.maps.Marker({
      position: lokasi,
      map: map,
      title: "{{ $lokasi->nama }}",
      animation: google.maps.Animation.DROP,
  });
  
  // Info window
  const infoWindow = new google.maps.InfoWindow({
      content: `
          <div style="padding: 10px; max-width: 250px;">
              <h3 style="margin: 0 0 8px 0; font-weight: bold; color: #1f2937;">{{ $lokasi->nama }}</h3>
              <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">{{ $lokasi->alamat_lengkap }}</p>
              <div style="margin-top: 8px;">
                  <span style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                      {{ ucfirst($lokasi->jenis) }}
                  </span>
              </div>
          </div>
      `,
  });
  
  // Event listener untuk marker
  marker.addListener("click", () => {
      infoWindow.open(map, marker);
  });
  
  // Buka info window secara default
  infoWindow.open(map, marker);
}

// Error handling jika Google Maps gagal load
window.gm_authFailure = function() {
  document.getElementById('map').innerHTML = `
      <div class="h-64 flex items-center justify-center bg-red-50 border border-red-200 rounded-lg">
          <div class="text-center">
              <svg class="w-12 h-12 text-red-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-red-600 font-medium">Google Maps API Key diperlukan</p>
              <p class="text-red-500 text-sm">Silakan konfigurasi API key di file .env</p>
          </div>
      </div>
  `;
};
</script>

<!-- Load Google Maps API -->
<script async defer 
  src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}&callback=initMap&libraries=geometry">
</script>
@endif
@endsection