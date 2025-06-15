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

        <!-- Informed Consent Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('donor.consent.save') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Header Icon -->
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-signature text-red-600 text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">INFORMED CONSENT DONOR</h2>
                        <p class="text-gray-600 mt-2">Persetujuan dan pernyataan kesediaan donor darah</p>
                    </div>

                    <!-- Informed Consent Content -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                1
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-medium mb-3">Pernyataan Persetujuan</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 mb-3 font-medium">Kepada Yth. PMI Kota Bandung</p>
                                    
                                    <div class="space-y-3 text-sm text-gray-700 leading-relaxed">
                                        <p>
                                            Saya telah membaca dan mendapatkan semua informasi yang diberikan serta menjawab pertanyaan dengan jujur.
                                        </p>
                                        
                                        <p>
                                            Saya mengerti dan bersedia menyumbangkan darah sesuai dengan standar yang diberlakukan dan setuju diambil contoh darah untuk keperluan pemeriksaan laboratorium berupa:
                                        </p>
                                        
                                        <ul class="list-disc list-inside ml-4 space-y-1 text-xs">
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
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                2
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-medium mb-3">
                                    <i class="fas fa-info-circle text-red-600 mr-2"></i>
                                    Informasi Penting
                                </h3>
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <div class="space-y-2 text-sm text-blue-700">
                                        <p>• Proses donor darah aman dan menggunakan alat steril sekali pakai</p>
                                        <p>• Volume darah yang diambil: 350-450 ml (tidak membahayakan kesehatan)</p>
                                        <p>• Setelah donor, istirahat 10-15 menit dan konsumsi makanan/minuman yang disediakan</p>
                                        <p>• Hindari aktivitas berat selama 24 jam setelah donor</p>
                                        <p>• Jika merasa tidak nyaman setelah donor, segera hubungi petugas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Confirmation -->
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                3
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-medium mb-3">
                                    <i class="fas fa-user-check text-red-600 mr-2"></i>
                                    Konfirmasi Data Donor
                                </h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nama:</span>
                                            <span class="font-medium">{{ $donor->user->name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Email:</span>
                                            <span class="font-medium">{{ $donor->user->email }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kode Donor:</span>
                                            <span class="font-medium text-red-600">{{ $donor->donor_code }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tanggal:</span>
                                            <span class="font-medium">{{ now()->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consent Checkbox -->
                    <div class="pb-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                4
                            </div>
                            <div class="flex-1">
                                <h3 class="text-gray-800 font-medium mb-3">
                                    <i class="fas fa-signature text-red-600 mr-2"></i>
                                    Persetujuan
                                </h3>
                                <div class="border-2 border-red-200 rounded-lg p-4 bg-red-50">
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
                                <p class="text-xs text-red-500 mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Wajib dicentang untuk melanjutkan proses donor
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between pt-6">
                        <a href="{{ route('donor.questions', 3) }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Kuesioner
                        </a>
                        
                        <button type="submit" 
                                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            {{ $donor->is_eligible ? 'Setuju & Lanjutkan' : 'Setuju & Selesai' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">💡 Petunjuk Persetujuan</h3>
            <ul class="space-y-2 text-sm text-blue-700">
                <li>• Bacalah seluruh informed consent dengan teliti</li>
                <li>• Pastikan semua data yang ditampilkan sudah benar</li>
                <li>• Persetujuan ini mengikat secara hukum</li>
                <li>• Jika ada pertanyaan, tanyakan kepada petugas PMI saat di lokasi</li>
            </ul>
        </div>
    </div>
</div>
@endsection
