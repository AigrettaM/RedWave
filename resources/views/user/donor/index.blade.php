@extends('dashboardlayout.app')

@section('page-title', 'Donor Darah')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg p-8 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">🩸 Donor Darah</h1>
                    <p class="text-red-100">Berbagi kehidupan melalui donor darah</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $profile->donor_code ?? 'N/A' }}</div>
                    <div class="text-red-200 text-sm">Kode Donor</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Donor Anda</h2>
                    
                    @if(!$profile)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-yellow-800">Profile Belum Lengkap</h3>
                                    <p class="text-yellow-700 text-sm">Anda harus melengkapi profile terlebih dahulu sebelum dapat mendonor.</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('profile.create') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                    Lengkapi Profile
                                </a>
                            </div>
                        </div>
                    @elseif(!$canDonate)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-red-500 mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-red-800">Belum Dapat Mendonor</h3>
                                    <p class="text-red-700 text-sm">Anda dapat mendonor lagi pada: <strong>{{ $nextEligibleDate ? $nextEligibleDate->format('d M Y H:i') : 'N/A' }}</strong></p>
                                    <p class="text-red-600 text-xs mt-1">Minimal jarak donor adalah 2 minggu</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <div>
                                    <h3 class="font-semibold text-green-800">Siap untuk Mendonor!</h3>
                                    <p class="text-green-700 text-sm">Anda memenuhi syarat untuk mendonor darah.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Donor Process Steps -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tahapan Donor Darah</h3>
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</div>
                                    <div>
                                        <div class="font-medium">Pendaftaran</div>
                                        <div class="text-sm text-gray-600">Verifikasi data dan kelengkapan</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</div>
                                    <div>
                                        <div class="font-medium">Kuesioner Kesehatan 1</div>
                                        <div class="text-sm text-gray-600">Kondisi hari ini dan minggu terakhir</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</div>
                                    <div>
                                        <div class="font-medium">Kuesioner Kesehatan 2</div>
                                        <div class="text-sm text-gray-600">Riwayat 6-12 minggu terakhir</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</div>
                                    <div>
                                        <div class="font-medium">Kuesioner Kesehatan 3</div>
                                        <div class="text-sm text-gray-600">Riwayat jangka panjang</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</div>
                                    <div>
                                        <div class="font-medium">Persetujuan</div>
                                        <div class="text-sm text-gray-600">Informed consent donor</div>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</div>
                                    <div>
                                        <div class="font-medium">Selesai</div>
                                        <div class="text-sm text-gray-600">Konfirmasi dan jadwal donor</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Start Button -->
                        <form action="{{ route('donor.start') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                                <i class="fas fa-heart mr-2"></i>
                                Mulai Proses Donor Darah
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Summary -->
                @if($profile)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Donor</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium">{{ $profile->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Golongan Darah:</span>
                                <span class="font-medium text-red-600">{{ $profile->blood_type }}{{ $profile->rhesus == 'POSITIF' ? '+' : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Telepon:</span>
                                <span class="font-medium">{{ $profile->telephone }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('profile.show') }}" class="text-red-600 hover:text-red-700 text-sm">
                                Lihat Profile Lengkap →
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Last Donation -->
                @if($lastDonation)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Donor Terakhir</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-medium">{{ $lastDonation->donation_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode:</span>
                                <span class="font-medium">{{ $lastDonation->donor_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($lastDonation->status == 'completed') bg-green-100 text-green-800
                                    @elseif($lastDonation->status == 'approved') bg-blue-100 text-blue-800
                                    @elseif($lastDonation->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($lastDonation->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('donor.history') }}" class="text-red-600 hover:text-red-700 text-sm">
                                Lihat Riwayat Donor →
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Quick Info -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">ℹ️ Info Penting</h3>
                    <ul class="space-y-2 text-sm text-blue-700">
                        <li>• Minimal jarak donor: 2 minggu</li>
                        <li>• Usia donor: 17-65 tahun</li>
                        <li>• Berat badan minimal: 45 kg</li>
                        <li>• Dalam kondisi sehat</li>
                        <li>• Istirahat cukup sebelum donor</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
