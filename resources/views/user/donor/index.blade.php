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
              <div class="text-4xl font-bold">{{ $currentDonor->donor_code ?? ($lastDonation->donor_code ?? 'N/A') }}</div>
              <div class="text-red-200 text-sm">{{ $currentDonor ? 'Kode Donor Aktif' : 'Donor Terakhir' }}</div>
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

  @if(session('info'))
      <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
          {{ session('info') }}
      </div>
  @endif

  <!-- Progress Indicator untuk donor yang sedang berlangsung -->
  @if($currentDonor)
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
          <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-blue-800">
                  <i class="fas fa-clock mr-2"></i>
                  Proses Donor Sedang Berlangsung
              </h3>
              <span class="text-sm text-blue-600">{{ $currentDonor->donor_code }}</span>
          </div>
          
          <!-- Progress Bar -->
          <div class="mb-4">
              <div class="flex justify-between text-sm text-blue-700 mb-2">
                  <span>Progress</span>
                  <span>{{ $progressPercentage }}%</span>
              </div>
              <div class="w-full bg-blue-200 rounded-full h-2">
                  <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
              </div>
          </div>

          <!-- Current Step -->
          <div class="flex items-center justify-between">
              <div>
                  <p class="text-blue-800 font-medium">{{ $currentStepName }}</p>
                  <p class="text-blue-600 text-sm">{{ $currentStepDescription }}</p>
              </div>
              <div class="flex space-x-2">
                  @if($nextStepRoute)
                      <a href="{{ $nextStepRoute }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                          <i class="fas fa-arrow-right mr-1"></i>
                          Lanjutkan
                      </a>
                  @endif
                  <form action="{{ route('donor.cancel') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan proses donor ini?')">
                      @csrf
                      <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                          <i class="fas fa-times mr-1"></i>
                          Batalkan
                      </button>
                  </form>
              </div>
          </div>
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
                          <a href="{{ route('profile.form') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                              Lengkapi Profile
                          </a>
                      </div>
                  </div>
              @elseif($profile && !$isAgeEligible)
                  <!-- Age Check Alert -->
                  <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                      <div class="flex items-center">
                          <i class="fas fa-birthday-cake text-red-500 mr-3"></i>
                          <div>
                              <h3 class="font-semibold text-red-800">Tidak Memenuhi Syarat Umur</h3>
                              <p class="text-red-700 text-sm">
                                  @if($userAge < 17)
                                      Usia Anda saat ini {{ $userAge }} tahun. Minimal usia untuk donor darah adalah 17 tahun.
                                  @elseif($userAge > 65)
                                      Usia Anda saat ini {{ $userAge }} tahun. Maksimal usia untuk donor darah adalah 65 tahun.
                                  @endif
                              </p>
                              <p class="text-red-600 text-xs mt-1">Syarat usia donor: 17-65 tahun</p>
                          </div>
                      </div>
                      @if($userAge < 17)
                          <div class="mt-3 p-3 bg-blue-50 rounded border-l-4 border-blue-400">
                              <p class="text-blue-700 text-sm">
                                  <i class="fas fa-info-circle mr-1"></i>
                                  Anda dapat mendonor pada tanggal: <strong>{{ $eligibleDate->format('d M Y') }}</strong>
                              </p>
                          </div>
                      @endif
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
                              @if($profile && $userAge)
                                  <p class="text-green-600 text-xs mt-1">Usia Anda: {{ $userAge }} tahun ✓</p>
                              @endif
                          </div>
                      </div>
                  </div>

                  <!-- Donor Process Steps -->
                  <div class="mb-6">
                      <h3 class="text-lg font-semibold text-gray-800 mb-4">Tahapan Donor Darah</h3>
                      <div class="space-y-3">
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep >= 1 ? 'border-l-4 border-blue-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 1 ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep > 1)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      1
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Pilih Lokasi & Jadwal</div>
                                  <div class="text-sm text-gray-600">Tentukan lokasi dan tanggal donor</div>
                              </div>
                          </div>
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep == 2 ? 'border-l-4 border-blue-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 2 ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep > 2)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      2
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Kuesioner Kesehatan 1</div>
                                  <div class="text-sm text-gray-600">Kondisi hari ini dan minggu terakhir</div>
                              </div>
                          </div>
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep == 3 ? 'border-l-4 border-blue-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 3 ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep > 3)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      3
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Kuesioner Kesehatan 2</div>
                                  <div class="text-sm text-gray-600">Riwayat 6-12 minggu terakhir</div>
                              </div>
                          </div>
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep == 4 ? 'border-l-4 border-blue-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 4 ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep > 4)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      4
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Kuesioner Kesehatan 3</div>
                                  <div class="text-sm text-gray-600">Riwayat jangka panjang</div>
                              </div>
                          </div>
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep == 5 ? 'border-l-4 border-blue-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 5 ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep > 5)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      5
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Persetujuan</div>
                                  <div class="text-sm text-gray-600">Informed consent donor</div>
                              </div>
                          </div>
                          <div class="flex items-center p-3 bg-gray-50 rounded-lg {{ $currentStep == 6 ? 'border-l-4 border-green-500' : '' }}">
                              <div class="w-8 h-8 {{ $currentStep >= 6 ? 'bg-green-600' : 'bg-red-600' }} text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                  @if($currentStep >= 6)
                                      <i class="fas fa-check text-xs"></i>
                                  @else
                                      6
                                  @endif
                              </div>
                              <div>
                                  <div class="font-medium">Selesai</div>
                                  <div class="text-sm text-gray-600">Konfirmasi dan jadwal donor</div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Start Button - hanya tampil jika tidak ada donor yang sedang berlangsung -->
                  @if(!$currentDonor)
                      <form action="{{ route('donor.start') }}" method="POST">
                          @csrf
                          <button type="submit" class="w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                              <i class="fas fa-heart mr-2"></i>
                              Mulai Proses Donor Darah
                          </button>
                      </form>
                  @endif
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
                      @if($userAge)
                          <div class="flex justify-between">
                              <span class="text-gray-600">Umur:</span>
                              <span class="font-medium {{ $isAgeEligible ? 'text-green-600' : 'text-red-600' }}">
                                  {{ $userAge }} tahun
                                  @if($isAgeEligible)
                                      <i class="fas fa-check-circle text-xs ml-1"></i>
                                  @else
                                      <i class="fas fa-times-circle text-xs ml-1"></i>
                                  @endif
                              </span>
                          </div>
                      @endif
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

          <!-- Donor Statistics -->
          @if($profile && $donorStats)
              <div class="bg-white rounded-lg shadow-md p-6">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">
                      <i class="fas fa-chart-bar mr-2 text-red-600"></i>
                      Statistik Donor
                  </h3>
                  <div class="grid grid-cols-2 gap-4">
                      <div class="text-center">
                          <div class="text-2xl font-bold text-red-600">{{ $donorStats['total'] }}</div>
                          <div class="text-sm text-gray-600">Total Donor</div>
                      </div>
                      <div class="text-center">
                          <div class="text-2xl font-bold text-green-600">{{ $donorStats['completed'] }}</div>
                          <div class="text-sm text-gray-600">Berhasil</div>
                      </div>
                  </div>
                  
                  <!-- Tambahan: Tampilkan cancelled jika ada -->
                  @if($donorStats['cancelled'] > 0)
                      <div class="mt-3 pt-3 border-t">
                          <div class="text-center">
                              <div class="text-lg font-bold text-gray-500">{{ $donorStats['cancelled'] }}</div>
                              <div class="text-xs text-gray-500">Dibatalkan</div>
                          </div>
                      </div>
                  @endif
                  
                  <div class="mt-4 pt-4 border-t">
                      <div class="flex justify-between text-sm">
                          <span class="text-gray-600">Poin Donor:</span>
                          <span class="font-bold text-yellow-600">{{ $donorStats['points'] }} pts</span>
                      </div>
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
                              @elseif($lastDonation->status == 'rejected' && $lastDonation->eligibility_reason == 'Dibatalkan oleh pengguna') bg-gray-100 text-gray-800
                              @elseif($lastDonation->status == 'rejected') bg-red-100 text-red-800
                              @else bg-yellow-100 text-yellow-800 @endif">
                              
                              @if($lastDonation->status == 'rejected')
                              Dibatalkan
                              @else
                                  {{ ucfirst($lastDonation->status) }}
                              @endif
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
                  <li class="flex items-center">
                      <i class="fas fa-birthday-cake mr-2 text-blue-600"></i>
                      <span><strong>Usia donor: 17-65 tahun</strong></span>
                  </li>
                  <li class="flex items-center">
                      <i class="fas fa-clock mr-2 text-blue-600"></i>
                      <span>Minimal jarak donor: 2 minggu</span>
                  </li>
                  <li class="flex items-center">
                      <i class="fas fa-weight mr-2 text-blue-600"></i>
                      <span>Berat badan minimal: 45 kg</span>
                  </li>
                  <li class="flex items-center">
                      <i class="fas fa-heart mr-2 text-blue-600"></i>
                      <span>Dalam kondisi sehat</span>
                  </li>
                  <li class="flex items-center">
                      <i class="fas fa-bed mr-2 text-blue-600"></i>
                      <span>Istirahat cukup sebelum donor</span>
                  </li>
              </ul>
          </div>
      </div>
  </div>
</div>
</div>
@endsection