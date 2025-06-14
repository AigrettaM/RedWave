@extends('dashboardlayout.app')

@section('page-title', $profile ? 'Edit Profile' : 'Create Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $profile ? 'Edit Profile' : 'Buat Profile Baru' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $profile ? 'Update informasi profile donor Anda' : 'Lengkapi informasi profile donor Anda' }}
                    </p>
                </div>
                @if($profile)
                    <a href="{{ route('profile.show') }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Progress Indicator -->
            <div class="mb-6">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <div class="flex items-center text-red-600">
                            <div class="rounded-full h-8 w-8 bg-red-600 text-white flex items-center justify-center text-sm font-semibold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium">{{ $profile ? 'Update' : 'Create' }} Profile</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('profile.save') }}" method="POST" id="profileForm">
                @csrf

                <div class="space-y-8">
                    <!-- Informasi Donor -->
                    <div class="bg-red-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                            <i class="fas fa-tint mr-2"></i>
                            Informasi Donor
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Donor Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    KDD (Kode Donor Darah) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="donor_code" 
                                       value="{{ old('donor_code', $profile->donor_code ?? $kdd) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" 
                                       readonly>
                                <p class="text-xs text-gray-500 mt-1">Kode otomatis digenerate sistem</p>
                            </div>

                            <!-- Donor ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ID Donor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="donor_id" 
                                       value="{{ old('donor_id', $profile->donor_id ?? $donorId) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" 
                                       readonly>
                                <p class="text-xs text-gray-500 mt-1">ID unik untuk donor</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Data Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- KTP Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. KTP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="ktp_number" 
                                       value="{{ old('ktp_number', $profile->ktp_number ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Masukkan nomor KTP" required>
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" 
                                       value="{{ old('name', $profile->name ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Masukkan nama lengkap" required>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('gender', $profile->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $profile->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <!-- Blood Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <select name="blood_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A" {{ old('blood_type', $profile->blood_type ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('blood_type', $profile->blood_type ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('blood_type', $profile->blood_type ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('blood_type', $profile->blood_type ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                            </div>

                            <!-- Rhesus -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rhesus <span class="text-red-500">*</span>
                                </label>
                                <select name="rhesus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Rhesus</option>
                                    <option value="POSITIF" {{ old('rhesus', $profile->rhesus ?? '') == 'POSITIF' ? 'selected' : '' }}>Positif (+)</option>
                                    <option value="NEGATIF" {{ old('rhesus', $profile->rhesus ?? '') == 'NEGATIF' ? 'selected' : '' }}>Negatif (-)</option>
                                </select>
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="birth_date" 
                                       value="{{ old('birth_date', $profile->birth_date ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       required>
                            </div>

                            <!-- Birth Place -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="birth_place" 
                                       value="{{ old('birth_place', $profile->birth_place ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Masukkan tempat lahir" required>
                            </div>

                            <!-- Telephone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="telephone" 
                                       value="{{ old('telephone', $profile->telephone ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Contoh: 08123456789" required>
                            </div>

                            <!-- Occupation -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="occupation" 
                                       value="{{ old('occupation', $profile->occupation ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Masukkan pekerjaan" required>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Alamat
                        </h3>
                        
                        <!-- Address -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                      placeholder="Masukkan alamat lengkap" required>{{ old('address', $profile->address ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Province -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select name="province_id" id="province" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ old('province_id', $profile->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <select name="city_id" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            <!-- District -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kecamatan <span class="text-red-500">*</span>
                                </label>
                                <select name="district_id" id="district" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <!-- Village -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Desa/Kelurahan <span class="text-red-500">*</span>
                                </label>
                                <select name="village_id" id="village" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Pilih Desa/Kelurahan</option>
                                </select>
                            </div>

                            <!-- RT/RW -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    RT/RW <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="rt_rw" 
                                       value="{{ old('rt_rw', $profile->rt_rw ?? '') }}" 
                                       placeholder="Contoh: 001/002"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       required>
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Pos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="postal_code" 
                                       value="{{ old('postal_code', $profile->postal_code ?? '') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" 
                                       placeholder="Contoh: 12345" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    @if($profile)
                        <a href="{{ route('profile.show') }}" 
                           class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                    @endif
                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        {{ $profile ? 'Update Profile' : 'Simpan Profile' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery Full Version -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Document ready - initializing dependent dropdowns');

    // Function to load dependent dropdown data
    function loadDropdownData(url, targetSelect, selectedValue = null) {
        console.log('Loading data from:', url);
        console.log('Target select:', targetSelect);
        
        $(targetSelect).html('<option value="">Loading...</option>').prop('disabled', true);
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data received:', data);
                
                $(targetSelect).html('<option value="">Pilih Salah Satu</option>');
                
                if (data && data.length > 0) {
                    $.each(data, function(index, item) {
                        var isSelected = selectedValue && selectedValue == item.id ? 'selected' : '';
                        $(targetSelect).append('<option value="' + item.id + '" ' + isSelected + '>' + item.name + '</option>');
                    });
                } else {
                    $(targetSelect).append('<option value="">Tidak ada data</option>');
                }
                
                $(targetSelect).prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                $(targetSelect).html('<option value="">Error loading data</option>').prop('disabled', false);
            }
        });
    }

    // Province change event
    $('#province').on('change', function() {
        var provinceId = $(this).val();
        console.log('Province selected:', provinceId);
        
        // Clear dependent dropdowns
        $('#city').html('<option value="">Pilih Kota/Kabupaten</option>');
        $('#district').html('<option value="">Pilih Kecamatan</option>');
        $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
        
        if (provinceId) {
            var url = '{{ route("cities") }}?province_id=' + provinceId;
            loadDropdownData(url, '#city');
        }
    });

    // City change event
    $('#city').on('change', function() {
        var cityId = $(this).val();
        console.log('City selected:', cityId);
        
        // Clear dependent dropdowns
        $('#district').html('<option value="">Pilih Kecamatan</option>');
        $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
        
        if (cityId) {
            var url = '{{ route("districts") }}?city_id=' + cityId;
            loadDropdownData(url, '#district');
        }
    });

    // District change event
    $('#district').on('change', function() {
        var districtId = $(this).val();
        console.log('District selected:', districtId);
        
        // Clear dependent dropdown
        $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
        
        if (districtId) {
            var url = '{{ route("villages") }}?district_id=' + districtId;
            loadDropdownData(url, '#village');
        }
    });

    // Load existing data if editing profile
    @if(isset($profile) && $profile)
        console.log('Loading existing profile data...');
        
        @if($profile->province_id)
            setTimeout(function() {
                $('#province').trigger('change');
                
                @if($profile->city_id)
                    setTimeout(function() {
                        $('#city').val('{{ $profile->city_id }}').trigger('change');
                        
                        @if($profile->district_id)
                            setTimeout(function() {
                                $('#district').val('{{ $profile->district_id }}').trigger('change');
                                
                                @if($profile->village_id)
                                    setTimeout(function() {
                                        $('#village').val('{{ $profile->village_id }}');
                                    }, 1000);
                                @endif
                            }, 1000);
                        @endif
                    }, 1000);
                @endif
            }, 500);
        @endif
    @endif
});
</script>
@endsection
