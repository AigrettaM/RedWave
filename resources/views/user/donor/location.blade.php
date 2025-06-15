@extends('dashboardlayout.app')

@section('page-title', 'Pilih Lokasi Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Pilih Lokasi Donor</h1>
                <div class="text-sm text-gray-600">
                    Kode Donor: <span class="font-semibold text-red-600">{{ $donor->donor_code }}</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="flex items-center space-x-4 mb-4">
                @for($i = 1; $i <= 6; $i++)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                            @if($i == 1) bg-red-600 text-white
                            @else bg-gray-200 text-gray-600 @endif">
                            {{ $i }}
                        </div>
                        @if($i < 6)
                            <div class="w-8 h-1 mx-2 bg-gray-200"></div>
                        @endif
                    </div>
                @endfor
            </div>
            
            <div class="text-sm text-gray-600">
                Tahap 1 dari 6: Pilih Lokasi & Jadwal Donor
            </div>
        </div>

        <!-- Location Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('donor.location.save', $donor->id) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Header Icon -->
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-map-marker-alt text-red-600 text-2xl"></i>
                        </div>
                        <p class="text-gray-600">Tentukan lokasi dan jadwal donor darah Anda</p>
                    </div>

                    <!-- Pilih Lokasi -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                1
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-medium mb-3">
                                    <i class="fas fa-hospital text-red-600 mr-2"></i>
                                    Pilih Lokasi Donor *
                                </label>
                                <select name="lokasi_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 @error('lokasi_id') border-red-500 @enderror" required>
                                    <option value="">-- Pilih Lokasi Donor --</option>
                                    @foreach($lokasis as $lokasi)
                                        <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                            {{ $lokasi->nama }} - {{ $lokasi->kota }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lokasi_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                2
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-medium mb-3">
                                    <i class="fas fa-home text-red-600 mr-2"></i>
                                    Alamat Lengkap Anda *
                                </label>
                                <textarea name="alamat" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 @error('alamat') border-red-500 @enderror" 
                                          placeholder="Masukkan alamat lengkap tempat tinggal Anda" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Alamat digunakan untuk keperluan administrasi dan sertifikat
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Donor -->
                    <div class="pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                3
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-medium mb-3">
                                    <i class="fas fa-calendar text-red-600 mr-2"></i>
                                    Tanggal Donor *
                                </label>
                                <input type="date" name="donation_date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 @error('donation_date') border-red-500 @enderror"
                                       min="{{ date('Y-m-d') }}" value="{{ old('donation_date') }}" required>
                                @error('donation_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Tanggal dapat diubah saat di lokasi donor
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <a href="{{ route('donor.index') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Batal
                        </a>
                        
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 flex items-center">
                            Lanjut ke Kuesioner
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">💡 Informasi Penting</h3>
            <ul class="space-y-2 text-sm text-blue-700">
                <li>• Pastikan lokasi yang dipilih sesuai dengan domisili Anda</li>
                <li>• Bawa KTP dan kode donor saat datang ke lokasi</li>
                <li>• Tanggal donor dapat diubah jika diperlukan saat di PMI</li>
                <li>• Datang 15 menit sebelum jadwal untuk registrasi</li>
                <li>• Pastikan kondisi tubuh fit dan sudah makan sebelum donor</li>
            </ul>
        </div>
    </div>
</div>
@endsection
