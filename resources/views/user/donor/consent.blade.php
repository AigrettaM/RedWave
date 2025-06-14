@extends('dashboardlayout.app')

@section('page-title', 'Persetujuan Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Persetujuan Donor Darah</h1>
                <div class="text-sm text-gray-600">
                    Kode Donor: <span class="font-semibold text-red-600">{{ $donor->donor_code }}</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="flex items-center space-x-4 mb-4">
                @for($i = 1; $i <= 6; $i++)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                            @if($i <= 5) bg-red-600 text-white
                            @else bg-gray-200 text-gray-600 @endif">
                            {{ $i }}
                        </div>
                        @if($i < 6)
                            <div class="w-8 h-1 mx-2 
                                @if($i <= 4) bg-red-600
                                @else bg-gray-200 @endif">
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
            
            <div class="text-sm text-gray-600">
                Tahap 5 dari 6: Informed Consent Donor
            </div>
        </div>

        <!-- Eligibility Status -->
        @if($donor->is_eligible)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Selamat! Anda Memenuhi Syarat Donor</h3>
                        <p class="text-green-700">Berdasarkan jawaban kuesioner, Anda dapat melanjutkan proses donor darah.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-red-500 text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Maaf, Anda Belum Dapat Mendonor</h3>
                        <p class="text-red-700 mb-2">Berdasarkan jawaban kuesioner, Anda belum memenuhi syarat untuk donor saat ini.</p>
                        @if($donor->rejection_reason)
                            <div class="text-sm text-red-600 bg-red-100 p-3 rounded mt-2">
                                <strong>Alasan:</strong> {{ $donor->rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Informed Consent -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">📋 INFORMED CONSENT DONOR</h2>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Kepada Yth. PMI Kota Bandung</h3>
                
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        Saya telah membaca dan mendapatkan semua informasi yang diberikan serta menjawab pertanyaan dengan jujur.
                    </p>
                    
                    <p>
                        Saya mengerti dan bersedia menyumbangkan darah sesuai dengan standar yang diberlakukan dan setuju diambil contoh darah untuk keperluan pemeriksaan laboratorium berupa:
                    </p>
                    
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>Uji golongan darah</li>
                        <li>HIV/AIDS</li>
                        <li>Hepatitis B & C</li>
                        <li>Sifilis</li>
                        <li>Infeksi lainnya yang diperlukan</li>
                    </ul>
                    
                    <p>
                        Bila ternyata hasil laboratorium perlu ditindaklanjuti, maka saya setuju untuk diberi kabar dan darah saya tidak ditransfusikan kepada calon pasien.
                    </p>
                    
                    <p>
                        Jika komponen plasma tidak terpakai untuk transfusi, saya setuju dapat dijadikan produk plasma untuk pengobatan.
                    </p>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-800 mb-3">ℹ️ Informasi Tambahan</h3>
                <div class="space-y-2 text-sm text-blue-700">
                    <p>• Proses donor darah aman dan menggunakan alat steril sekali pakai</p>
                    <p>• Volume darah yang diambil: 350-450 ml (tidak membahayakan kesehatan)</p>
                    <p>• Setelah donor, istirahat 10-15 menit dan konsumsi makanan/minuman yang disediakan</p>
                    <p>• Hindari aktivitas berat selama 24 jam setelah donor</p>
                    <p>• Jika merasa tidak nyaman setelah donor, segera hubungi petugas</p>
                </div>
            </div>

            <!-- Consent Form -->
            <form action="{{ route('donor.consent.save') }}" method="POST">
                @csrf
                
                <div class="border-2 border-gray-200 rounded-lg p-6 mb-6">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" id="consent" name="consent" value="1" 
                               class="mt-1 text-red-600 focus:ring-red-500 focus:ring-2" required>
                        <label for="consent" class="text-gray-800 font-medium leading-relaxed">
                            Saya telah membaca, memahami, dan menyetujui semua informasi yang diberikan dalam informed consent ini. 
                            Saya dengan sukarela menyetujui untuk mendonorkan darah saya sesuai dengan ketentuan yang berlaku.
                        </label>
                    </div>
                    
                    @error('consent')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Personal Information Confirmation -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Konfirmasi Data Donor</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium ml-2">{{ $donor->user->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium ml-2">{{ $donor->user->email }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Kode Donor:</span>
                            <span class="font-medium ml-2 text-red-600">{{ $donor->donor_code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium ml-2">{{ now()->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('donor.questions', 3) }}" 
                       class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Kuesioner
                    </a>
                    
                    <button type="submit" 
                            class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 flex items-center font-semibold">
                        <i class="fas fa-check mr-2"></i>
                        {{ $donor->is_eligible ? 'Setuju & Lanjutkan' : 'Setuju & Selesai' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="bg-yellow-50 rounded-lg p-6 mt-6">
            <h3 class="font-semibold text-yellow-800 mb-3">📞 Informasi Kontak</h3>
            <div class="space-y-2 text-sm text-yellow-700">
                <p><strong>PMI Kota Bandung</strong></p>
                <p>📍 Jl. Aceh No. 79, Bandung</p>
                <p>📞 Telepon: (022) 4233714</p>
                <p>🕐 Jam Operasional: Senin-Jumat 08:00-15:00</p>
                <p>📧 Email: info@pmibandung.or.id</p>
            </div>
        </div>
    </div>
</div>
@endsection
