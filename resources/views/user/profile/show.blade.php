@extends('dashboardlayout.app')

@section('page-title', 'Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg p-6 mb-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Profile Donor Darah</h1>
                    <p class="text-red-100">Informasi lengkap profil donor darah Anda</p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                    @if($profile)
                        <a href="{{ route('profile.form') }}" 
                           class="bg-white text-red-600 px-6 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium flex items-center">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    @else
                        <a href="{{ route('profile.form') }}" 
                           class="bg-white text-red-600 px-6 py-2 rounded-lg hover:bg-gray-100 transition-colors font-medium flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($profile)
            <!-- Profile Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Donor Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Info Donor</h3>
                        <i class="fas fa-tint text-red-500 text-xl"></i>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">KDD</p>
                            <p class="font-semibold text-gray-800">{{ $profile->donor_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ID Donor</p>
                            <p class="font-semibold text-gray-800">{{ $profile->donor_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Blood Type Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Golongan Darah</h3>
                        <i class="fas fa-heartbeat text-blue-500 text-xl"></i>
                    </div>
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-3">
                            <span class="text-2xl font-bold text-red-600">
                                {{ $profile->blood_type }}{{ $profile->rhesus == 'POSITIF' ? '+' : '-' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $profile->rhesus }}</p>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Status</h3>
                        <i class="fas fa-user-check text-green-500 text-xl"></i>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-600">Profile Lengkap</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            <span class="text-sm text-gray-600">Siap Donor</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="showTab('personal')" 
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active"
                                id="personal-tab">
                            <i class="fas fa-user mr-2"></i>
                            Data Pribadi
                        </button>
                        <button onclick="showTab('address')" 
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                id="address-tab">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Alamat
                        </button>
                        <button onclick="showTab('medical')" 
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                id="medical-tab">
                            <i class="fas fa-notes-medical mr-2"></i>
                            Info Medis
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Personal Data Tab -->
                    <div id="personal-content" class="tab-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">No. KTP</label>
                                <p class="text-gray-900 font-medium">{{ $profile->ktp_number }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                <p class="text-gray-900 font-medium">{{ $profile->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $profile->gender == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        <i class="fas {{ $profile->gender == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                        {{ $profile->gender }}
                                    </span>
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Tanggal Lahir</label>
                                <p class="text-gray-900">{{ $profile->birth_date->format('d F Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Tempat Lahir</label>
                                <p class="text-gray-900">{{ $profile->birth_place }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Umur</label>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $profile->birth_date->age }} tahun
                                    </span>
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Telepon</label>
                                <p class="text-gray-900">
                                    <a href="tel:{{ $profile->telephone }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-phone mr-1"></i>
                                        {{ $profile->telephone }}
                                    </a>
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                                <p class="text-gray-900">{{ $profile->occupation }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address Tab -->
                    <div id="address-content" class="tab-content hidden">
                        <div class="space-y-6">
                            <!-- Full Address -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Alamat Lengkap</label>
                                <p class="text-gray-900">{{ $profile->address }}</p>
                            </div>

                            <!-- Address Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">Provinsi</label>
                                    <p class="text-gray-900 font-medium">{{ $profile->province->name ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">Kota/Kabupaten</label>
                                    <p class="text-gray-900 font-medium">{{ $profile->city->name ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">Kecamatan</label>
                                    <p class="text-gray-900">{{ $profile->district->name ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">Desa/Kelurahan</label>
                                    <p class="text-gray-900">{{ $profile->village->name ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">RT/RW</label>
                                    <p class="text-gray-900">{{ $profile->rt_rw }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-500">Kode Pos</label>
                                    <p class="text-gray-900">{{ $profile->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Info Tab -->
                    <div id="medical-content" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Blood Type Info -->
                            <div class="bg-red-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                                    <i class="fas fa-tint mr-2"></i>
                                    Informasi Darah
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Golongan Darah:</span>
                                        <span class="font-semibold text-red-600 text-xl">{{ $profile->blood_type }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Rhesus:</span>
                                        <span class="font-semibold {{ $profile->rhesus == 'POSITIF' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $profile->rhesus == 'POSITIF' ? 'Positif (+)' : 'Negatif (-)' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Tipe Lengkap:</span>
                                        <span class="font-bold text-lg px-3 py-1 bg-red-100 text-red-800 rounded-full">
                                            {{ $profile->blood_type }}{{ $profile->rhesus == 'POSITIF' ? '+' : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Compatibility Info -->
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <i class="fas fa-exchange-alt mr-2"></i>
                                    Kompatibilitas
                                </h4>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Dapat menerima dari:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $canReceiveFrom = [];
                                                switch($profile->blood_type . $profile->rhesus) {
                                                    case 'APOSITIF': $canReceiveFrom = ['A+', 'A-', 'O+', 'O-']; break;
                                                    case 'ANEGATIF': $canReceiveFrom = ['A-', 'O-']; break;
                                                    case 'BPOSITIF': $canReceiveFrom = ['B+', 'B-', 'O+', 'O-']; break;
                                                    case 'BNEGATIF': $canReceiveFrom = ['B-', 'O-']; break;
                                                    case 'ABPOSITIF': $canReceiveFrom = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; break;
                                                    case 'ABNEGATIF': $canReceiveFrom = ['A-', 'B-', 'AB-', 'O-']; break;
                                                    case 'OPOSITIF': $canReceiveFrom = ['O+', 'O-']; break;
                                                    case 'ONEGATIF': $canReceiveFrom = ['O-']; break;
                                                }
                                            @endphp
                                            @foreach($canReceiveFrom as $type)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $type }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Dapat memberikan kepada:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $canGiveTo = [];
                                                switch($profile->blood_type . $profile->rhesus) {
                                                    case 'APOSITIF': $canGiveTo = ['A+', 'AB+']; break;
                                                    case 'ANEGATIF': $canGiveTo = ['A+', 'A-', 'AB+', 'AB-']; break;
                                                    case 'BPOSITIF': $canGiveTo = ['B+', 'AB+']; break;
                                                    case 'BNEGATIF': $canGiveTo = ['B+', 'B-', 'AB+', 'AB-']; break;
                                                    case 'ABPOSITIF': $canGiveTo = ['AB+']; break;
                                                    case 'ABNEGATIF': $canGiveTo = ['AB+', 'AB-']; break;
                                                    case 'OPOSITIF': $canGiveTo = ['A+', 'B+', 'AB+', 'O+']; break;
                                                    case 'ONEGATIF': $canGiveTo = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']; break;
                                                }
                                            @endphp
                                            @foreach($canGiveTo as $type)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $type }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline/History Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-purple-600"></i>
                    Riwayat Profile
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm text-gray-900">Profile dibuat</p>
                            <p class="text-xs text-gray-500">{{ $profile->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($profile->updated_at != $profile->created_at)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm text-gray-900">Profile terakhir diupdate</p>
                                <p class="text-xs text-gray-500">{{ $profile->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        @else
            <!-- No Profile State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <div class="mx-auto h-24 w-24 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-red-600 text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Profile Belum Dibuat</h3>
                    <p class="text-gray-600 mb-8">
                        Anda belum memiliki profile donor darah. Silakan buat profile terlebih dahulu untuk dapat menggunakan layanan donor darah.
                    </p>
                    
                    <div class="space-y-4">
                        <a href="{{ route('profile.form') }}" 
                           class="w-full bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition-colors inline-flex items-center justify-center font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Profile Sekarang
                        </a>
                        
                        <div class="text-sm text-gray-500">
                            <p>Dengan membuat profile, Anda dapat:</p>
                            <ul class="mt-2 space-y-1">
                                <li>• Mendaftar sebagai donor darah</li>
                                <li>• Melihat jadwal donor</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- JavaScript for Tabs -->
<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active', 'border-red-500', 'text-red-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById(tabName + '-tab');
    activeButton.classList.add('active', 'border-red-500', 'text-red-600');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('personal');
});
</script>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
        color: black !important;
    }
    
    .container {
        max-width: none;
        margin: 0;
        padding: 0;
    }
    
    .shadow-md, .shadow-lg {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .bg-gradient-to-r {
        background: #dc2626 !important;
    }
    
    .tab-content {
        display: block !important;
    }
    
    .hidden {
        display: block !important;
    }
    
    .grid {
        page-break-inside: avoid;
    }
}
</style>
@endsection
