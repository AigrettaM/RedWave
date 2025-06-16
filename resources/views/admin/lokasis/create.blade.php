@extends('dashboardlayout.app')

@section('page-title', 'Tambah Lokasi Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('admin.lokasis.index') }}" 
                   class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Tambah Lokasi Donor Baru</h1>
                    <p class="text-gray-600">Tambahkan lokasi PMI untuk donor darah</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-red-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">Informasi Lokasi Donor</h2>
            </div>
            
            <form action="{{ route('admin.lokasis.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
                @csrf
                
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('nama') border-red-500 @enderror" 
                                   placeholder="Contoh: PMI Cabang Jakarta Pusat">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <select id="kota" 
                                    name="kota" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kota') border-red-500 @enderror">
                                <option value="">Pilih Kota</option>
                                <option value="Jakarta Pusat" {{ old('kota') == 'Jakarta Pusat' ? 'selected' : '' }}>Jakarta Pusat</option>
                                <option value="Jakarta Utara" {{ old('kota') == 'Jakarta Utara' ? 'selected' : '' }}>Jakarta Utara</option>
                                <option value="Jakarta Selatan" {{ old('kota') == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                                <option value="Jakarta Barat" {{ old('kota') == 'Jakarta Barat' ? 'selected' : '' }}>Jakarta Barat</option>
                                <option value="Jakarta Timur" {{ old('kota') == 'Jakarta Timur' ? 'selected' : '' }}>Jakarta Timur</option>
                            </select>
                            @error('kota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" 
                                  name="alamat" 
                                  rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('alamat') border-red-500 @enderror" 
                                  placeholder="Masukkan alamat lengkap lokasi donor">{{ old('alamat') }}</textarea>
                                @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Operational Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock text-red-600 mr-2"></i>
                        Informasi Operasional
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="tanggal_operasional" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Operasional <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tanggal_operasional" 
                                   name="tanggal_operasional" 
                                   value="{{ old('tanggal_operasional') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('tanggal_operasional') border-red-500 @enderror">
                            @error('tanggal_operasional')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="jam_buka" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Buka
                            </label>
                            <input type="time" 
                                   id="jam_buka" 
                                   name="jam_buka" 
                                   value="{{ old('jam_buka', '08:00') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('jam_buka') border-red-500 @enderror">
                            @error('jam_buka')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="jam_tutup" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Tutup
                            </label>
                            <input type="time" 
                                   id="jam_tutup" 
                                   name="jam_tutup" 
                                   value="{{ old('jam_tutup', '15:00') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('jam_tutup') border-red-500 @enderror">
                            @error('jam_tutup')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact & Capacity -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-phone text-red-600 mr-2"></i>
                        Kontak & Kapasitas
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="kontak" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Kontak
                            </label>
                            <input type="text" 
                                   id="kontak" 
                                   name="kontak" 
                                   value="{{ old('kontak') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kontak') border-red-500 @enderror" 
                                   placeholder="Contoh: 021-3190223">
                            @error('kontak')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                                Kapasitas Donor
                            </label>
                            <input type="number" 
                                   id="kapasitas" 
                                   name="kapasitas" 
                                   value="{{ old('kapasitas') }}"
                                   min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('kapasitas') border-red-500 @enderror" 
                                   placeholder="Contoh: 100">
                            @error('kapasitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Lokasi
                            </label>
                            <select id="jenis" 
                                    name="jenis" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('jenis') border-red-500 @enderror">
                                <option value="kota" {{ old('jenis', 'kota') == 'kota' ? 'selected' : '' }}>PMI Kota</option>
                                <option value="provinsi" {{ old('jenis') == 'provinsi' ? 'selected' : '' }}>PMI Provinsi</option>
                                <option value="cabang" {{ old('jenis') == 'cabang' ? 'selected' : '' }}>PMI Cabang</option>
                            </select>
                            @error('jenis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Coordinates -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Koordinat Lokasi (Opsional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Latitude
                            </label>
                            <input type="number" 
                                   id="latitude" 
                                   name="latitude" 
                                   value="{{ old('latitude') }}"
                                   step="any"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('latitude') border-red-500 @enderror" 
                                   placeholder="Contoh: -6.1751">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                Longitude
                            </label>
                            <input type="number" 
                                   id="longitude" 
                                   name="longitude" 
                                   value="{{ old('longitude') }}"
                                   step="any"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('longitude') border-red-500 @enderror" 
                                   placeholder="Contoh: 106.8650">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" 
                                onclick="getCurrentLocation()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-location-arrow mr-2"></i>
                            Gunakan Lokasi Saat Ini
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-red-600 mr-2"></i>
                        Deskripsi
                    </h3>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Lokasi (Opsional)
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('deskripsi') border-red-500 @enderror" 
                                  placeholder="Tambahkan deskripsi atau informasi tambahan tentang lokasi ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-image text-red-600 mr-2"></i>
                        Gambar Lokasi
                    </h3>
                    
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Gambar (Opsional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg mb-4">
                                </div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                        <span>Upload gambar</span>
                                        <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        </div>
                        @error('gambar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-toggle-on text-red-600 mr-2"></i>
                        Status
                    </h3>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Lokasi
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('status') border-red-500 @enderror">
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.lokasis.index') }}" 
                       class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Get current location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                
                // Show success message
                showNotification('Lokasi berhasil didapatkan!', 'success');
            },
            function(error) {
                let message = 'Gagal mendapatkan lokasi: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message += 'Akses lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message += 'Informasi lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        message += 'Waktu habis';
                        break;
                    default:
                        message += 'Error tidak dikenal';
                        break;
                }
                showNotification(message, 'error');
            }
        );
    } else {
        showNotification('Geolocation tidak didukung browser ini', 'error');
    }
}

// Show notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Auto-generate nama based on kota selection
document.getElementById('kota').addEventListener('change', function() {
    const kota = this.value;
    const namaInput = document.getElementById('nama');
    
    if (kota && !namaInput.value) {
        namaInput.value = `PMI Kota ${kota}`;
    }
});

// Validate time inputs
document.getElementById('jam_buka').addEventListener('change', validateTime);
document.getElementById('jam_tutup').addEventListener('change', validateTime);

function validateTime() {
    const jamBuka = document.getElementById('jam_buka').value;
    const jamTutup = document.getElementById('jam_tutup').value;
    
    if (jamBuka && jamTutup && jamBuka >= jamTutup) {
        showNotification('Jam tutup harus lebih besar dari jam buka', 'error');
        document.getElementById('jam_tutup').value = '';
    }
}

// Preview image function
function previewImage(input) {
    const file = input.files[0];
    const previewDiv = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.classList.remove('hidden');
        }
        
        reader.readAsDataURL(file);
    } else {
        previewDiv.classList.add('hidden');
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('gambar');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-red-400', 'bg-red-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-red-400', 'bg-red-50');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        previewImage(fileInput);
    }
}
</script>
@endsection