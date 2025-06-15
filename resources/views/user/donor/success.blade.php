@extends('dashboardlayout.app')

@section('page-title', 'Hasil Pendaftaran Donor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header dengan Status -->
        <div class="text-center mb-8">
            @if($donor->is_eligible && in_array($donor->status, ['approved', 'completed']))
                <!-- BERHASIL DONOR -->
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-green-800 mb-3">🎉 Pendaftaran Donor Berhasil!</h1>
                <p class="text-green-600 text-xl mb-2">Terima kasih atas kerelaan Anda untuk mendonorkan darah</p>
                <p class="text-gray-600">Kode Donor: <span class="font-bold text-green-700">{{ $donor->donor_code }}</span></p>
            @else
                <!-- TIDAK LAYAK DONOR -->
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-times-circle text-red-500 text-5xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-red-800 mb-3">😔 Maaf, Anda Belum Dapat Mendonor</h1>
                <p class="text-red-600 text-xl mb-2">Berdasarkan jawaban kuesioner, Anda belum memenuhi syarat untuk donor saat ini</p>
                <p class="text-gray-600">Kode Donor: <span class="font-bold text-red-700">{{ $donor->donor_code }}</span></p>
            @endif
        </div>
        
        <!-- Progress Bar Completed -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Progress Pendaftaran</h3>
                <span class="text-sm text-gray-500">Selesai</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
                    <span class="ml-2 text-sm text-gray-600">Lokasi</span>
                </div>
                <div class="flex-1 h-1 bg-green-500"></div>
                
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">2</div>
                    <span class="ml-2 text-sm text-gray-600">Kuesioner</span>
                </div>
                
                @if($donor->is_eligible)
                    <div class="flex-1 h-1 bg-green-500"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">3</div>
                        <span class="ml-2 text-sm text-gray-600">Persetujuan</span>
                    </div>
                @endif
                
                <div class="flex-1 h-1 bg-green-500"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        <i class="fas fa-check text-xs"></i>
                    </div>
                    <span class="ml-2 text-sm text-gray-600">Selesai</span>
                </div>
            </div>
        </div>

        @if($donor->is_eligible && in_array($donor->status, ['approved', 'completed']))
            <!-- HASIL POSITIF -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Info Donor -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-check text-green-500 mr-2"></i>
                        Informasi Donor
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">{{ $donor->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode Donor:</span>
                            <span class="font-bold text-green-700">{{ $donor->donor_code }}</span>
                        </div>
                        
                        {{-- Info Lokasi --}}
                        @if($donor->lokasi)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lokasi Donor:</span>
                            <span class="font-medium">{{ $donor->lokasi->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kota:</span>
                            <span class="font-medium">{{ $donor->lokasi->kota }}</span>
                        </div>
                        @endif
                        @if($donor->alamat)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Alamat Donor:</span>
                            <span class="font-medium">{{ Str::limit($donor->alamat, 30) }}</span>
                        </div>
                        @endif
                        {{-- End Info Lokasi --}}
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                ✅ Disetujui
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Daftar:</span>
                            <span class="font-medium">{{ $donor->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($donor->donation_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jadwal Donor:</span>
                                <span class="font-medium">{{ $donor->donation_date->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Langkah Selanjutnya -->
                <div class="bg-green-50 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list text-green-500 mr-2"></i>
                        Langkah Selanjutnya
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
                            <div>
                                <p class="font-medium text-green-800">Datang ke PMI</p>
                                <p class="text-sm text-green-600">Bawa KTP dan kode donor Anda</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">2</div>
                            <div>
                                <p class="font-medium text-green-800">Pemeriksaan Fisik</p>
                                <p class="text-sm text-green-600">Cek tekanan darah, hemoglobin, dll</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">3</div>
                            <div>
                                <p class="font-medium text-green-800">Proses Donor</p>
                                <p class="text-sm text-green-600">Proses pengambilan darah ±10-15 menit</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">4</div>
                            <div>
                                <p class="font-medium text-green-800">Istirahat & Konsumsi</p>
                                <p class="text-sm text-green-600">Makan dan minum yang disediakan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Penting -->
            <div class="bg-blue-50 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Informasi Penting
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h4 class="font-medium text-blue-800 mb-2">Sebelum Donor:</h4>
                        <ul class="space-y-1 text-blue-700">
                            <li>• Tidur cukup (minimal 5 jam)</li>
                            <li>• Makan 3-4 jam sebelum donor</li>
                            <li>• Minum air putih yang cukup</li>
                            <li>• Hindari makanan berlemak</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-blue-800 mb-2">Setelah Donor:</h4>
                        <ul class="space-y-1 text-blue-700">
                            <li>• Istirahat 10-15 menit</li>
                            <li>• Minum banyak air putih</li>
                            <li>• Hindari aktivitas berat 24 jam</li>
                            <li>• Jaga kebersihan bekas suntikan</li>
                        </ul>
                    </div>
                </div>
            </div>

        @else
            <!-- HASIL NEGATIF -->
            <div class="bg-red-50 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    Alasan Tidak Dapat Mendonor
                </h3>
                
                @if($donor->rejection_reason)
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-700 leading-relaxed">
                            {{ $donor->rejection_reason }}
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-red-800 mb-2">Kapan Bisa Donor Lagi?</h4>
                        <div class="text-sm text-red-700 space-y-1">
                            <p>• Setelah kondisi kesehatan membaik</p>
                            <p>• Konsultasi dengan dokter jika perlu</p>
                            <p>• Coba daftar lagi setelah 3-6 bulan</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-red-800 mb-2">Cara Mempersiapkan Diri:</h4>
                        <div class="text-sm text-red-700 space-y-1">
                            <p>• Jaga pola hidup sehat</p>
                            <p>• Konsumsi makanan bergizi</p>
                            <p>• Olahraga teratur</p>
                            <p>• Istirahat yang cukup</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Donor Berikutnya -->
            <div class="bg-yellow-50 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i>
                    Donor di Lain Waktu
                </h3>
                <p class="text-yellow-700 mb-3">
                    Jangan berkecil hati! Anda masih bisa berkontribusi dengan cara lain dan mencoba donor di waktu yang tepat.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <i class="fas fa-heart text-yellow-500 text-2xl mb-2"></i>
                        <p class="font-medium text-yellow-800">Jaga Kesehatan</p>
                        <p class="text-yellow-700">Persiapkan diri untuk donor berikutnya</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-share-alt text-yellow-500 text-2xl mb-2"></i>
                        <p class="font-medium text-yellow-800">Ajak Teman</p>
                        <p class="text-yellow-700">Undang teman untuk ikut donor darah</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-bullhorn text-yellow-500 text-2xl mb-2"></i>
                        <p class="font-medium text-yellow-800">Sosialisasi</p>
                        <p class="text-yellow-700">Sebarkan informasi pentingnya donor darah</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            {{-- Print button hanya untuk yang completed --}}
            @if($donor->status === 'completed')
                <button onclick="printCertificate()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Sertifikat
                </button>
            @elseif($donor->status === 'approved')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <i class="fas fa-info-circle text-blue-500 text-xl mb-2"></i>
                    <p class="text-blue-800 font-medium">Sertifikat akan tersedia setelah proses donor selesai di PMI</p>
                </div>
            @endif
            
            <a href="{{ route('donor.history') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                <i class="fas fa-history mr-2"></i>
                Lihat Riwayat Donor
            </a>
            
            <a href="{{ route('donor.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                <i class="fas fa-home mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
function printCertificate() {
    window.open('{{ route("donor.certificate", $donor->id) }}', '_blank');
}

// Auto scroll to top
window.scrollTo(0, 0);
</script>
@endsection
