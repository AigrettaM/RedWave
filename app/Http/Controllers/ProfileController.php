<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Menampilkan profile user yang sedang login (read-only)
     */
    public function show()
    {
        try {
            $user = auth()->user();
            $profile = $user->profile;

            // Jika belum ada profile, redirect ke form
            if (!$profile) {
                return redirect()->route('profile.form')->with('info', 'Silakan lengkapi profile Anda terlebih dahulu.');
            }

            return view('user.profile.show', compact('profile'));
        } catch (\Exception $e) {
            Log::error('Error showing profile: ' . $e->getMessage());
            return redirect()->route('user.home')->with('error', 'Terjadi kesalahan saat memuat profile.');
        }
    }

public function form()
{
    try {
        $user = auth()->user();
        
        // Cek user
        if (!$user) {
            return redirect()->route('login');
        }
        
        $profile = $user->profile;

        // Debug profile
        \Log::info('User ID: ' . $user->id);
        \Log::info('Profile exists: ' . ($profile ? 'Yes' : 'No'));
        
        if ($profile) {
            \Log::info('Profile ID: ' . $profile->id);
            \Log::info('Province ID: ' . $profile->province_id);
            \Log::info('City ID: ' . $profile->city_id);
        }

        // Generate KDD dan ID Donor
        $kdd = $profile ? $profile->donor_code : 'UTDC-' . Str::upper(Str::random(6));
        $donorId = $profile ? $profile->donor_id : 'D' . Str::upper(Str::random(12));

        // Fetch provinces
        $provinces = Province::orderBy('name')->get();
        \Log::info('Provinces count: ' . $provinces->count());
        
        // Initialize collections
        $cities = collect();
        $districts = collect();
        $villages = collect();
        
        // Load existing location data if editing
        if ($profile && $profile->province_id) {
            try {
                $cities = City::where('province_id', $profile->province_id)->orderBy('name')->get();
                
                if ($profile->city_id) {
                    $districts = District::where('city_id', $profile->city_id)->orderBy('name')->get();
                    
                    if ($profile->district_id) {
                        $villages = Village::where('district_id', $profile->district_id)->orderBy('name')->get();
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Error loading location data: ' . $e->getMessage());
            }
        }

        return view('user.profile.form', compact(
            'profile', 
            'provinces', 
            'cities',
            'districts',
            'villages',
            'kdd', 
            'donorId'
        ));
        
    } catch (\Exception $e) {
        \Log::error('Profile form error: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        
        // Untuk debug, tampilkan error
        dd($e->getMessage(), $e->getFile(), $e->getLine());
    }
}

    /**
     * Store atau Update profile
     */
    public function save(Request $request)
    {
        try {
            $request->validate([
                'donor_code' => 'required|string|max:255',
                'donor_id' => 'required|string|max:255',
                'ktp_number' => 'required|string|max:16|unique:profiles,ktp_number,' . (auth()->user()->profile->id ?? 'NULL'),
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'blood_type' => 'required|in:A,B,AB,O',
                'rhesus' => 'required|in:POSITIF,NEGATIF',
                'birth_date' => 'required|date|before:today',
                'birth_place' => 'required|string|max:255',
                'telephone' => 'required|string|max:20|unique:profiles,telephone,' . (auth()->user()->profile->id ?? 'NULL'),
                'address' => 'required|string|max:500',
                'province_id' => 'required|exists:indonesia_provinces,id',
                'city_id' => 'required|exists:indonesia_cities,id',
                'district_id' => 'required|exists:indonesia_districts,id',
                'village_id' => 'required|exists:indonesia_villages,id',
                'postal_code' => 'required|string|max:10',
                'rt_rw' => 'required|string|max:10',
                'occupation' => 'required|string|max:100',
            ], [
                'ktp_number.unique' => 'Nomor KTP sudah terdaftar.',
                'telephone.unique' => 'Nomor telepon sudah terdaftar.',
                'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
                'donor_code.required' => 'Kode donor harus diisi.',
                'donor_id.required' => 'ID donor harus diisi.',
            ]);

            DB::beginTransaction();

            $user = auth()->user();
            $profile = $user->profile;

            // Data yang akan disimpan
            $profileData = $request->all();
            
            // Pastikan user_id selalu ada
            $profileData['user_id'] = $user->id;

            if ($profile) {
                // Update existing profile
                $profile->update($profileData);
                $message = 'Profile berhasil diperbarui!';
                $alertType = 'success';
            } else {
                // Create new profile
                $user->profile()->create($profileData);
                $message = 'Profile berhasil dibuat!';
                $alertType = 'success';
            }

            DB::commit();

            return redirect()->route('profile.show')->with($alertType, $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withErrors($e->validator)
                           ->withInput()
                           ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving profile: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat menyimpan profile. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan semua profile untuk admin
     */
    public function index()
    {
        try {
            $profiles = Profile::with(['user'])
                              ->orderBy('created_at', 'desc')
                              ->get();
            
            return view('admin.users.index', compact('profiles'));
        } catch (\Exception $e) {
            Log::error('Error fetching profiles: ' . $e->getMessage());
            return view('admin.users.index', ['profiles' => collect()])
                   ->with('error', 'Terjadi kesalahan saat memuat data user.');
        }
    }

    /**
     * Menampilkan detail user untuk admin (AJAX)
     */
    public function show_admin($id)
    {
        try {
            $profile = Profile::with(['user'])
                             ->findOrFail($id);
            
            // Format data untuk response
            $profileData = [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'donor_code' => $profile->donor_code,
                'donor_id' => $profile->donor_id,
                'ktp_number' => $profile->ktp_number,
                'name' => $profile->name,
                'gender' => $profile->gender,
                'blood_type' => $profile->blood_type,
                'rhesus' => $profile->rhesus,
                'birth_date' => $profile->birth_date ? Carbon::parse($profile->birth_date)->format('d/m/Y') : null,
                'birth_place' => $profile->birth_place,
                'age' => $profile->birth_date ? Carbon::parse($profile->birth_date)->age : null,
                'telephone' => $profile->telephone,
                'address' => $profile->address,
                'rt_rw' => $profile->rt_rw,
                'postal_code' => $profile->postal_code,
                'occupation' => $profile->occupation,
                'created_at' => $profile->created_at->format('d/m/Y H:i'),
                'updated_at' => $profile->updated_at->format('d/m/Y H:i'),
                'user_email' => $profile->user ? $profile->user->email : null,
                'user_role' => $profile->user ? $profile->user->role : null,
            ];

            // Ambil nama wilayah
            try {
                if ($profile->province_id) {
                    $province = Province::find($profile->province_id);
                    $profileData['province_name'] = $province ? $province->name : null;
                }
                
                if ($profile->city_id) {
                    $city = City::find($profile->city_id);
                    $profileData['city_name'] = $city ? $city->name : null;
                }
                
                if ($profile->district_id) {
                    $district = District::find($profile->district_id);
                    $profileData['district_name'] = $district ? $district->name : null;
                }
                
                if ($profile->village_id) {
                    $village = Village::find($profile->village_id);
                    $profileData['village_name'] = $village ? $village->name : null;
                }
            } catch (\Exception $e) {
                Log::warning('Error loading location names: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $profileData
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error showing user details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data user.'
            ], 500);
        }
    }

    /**
     * Menghapus profile user (Admin only)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $profile = Profile::findOrFail($id);
            $userName = $profile->name;
            $userId = $profile->user_id;

            // Hapus profile
            $profile->delete();

            // Optional: Hapus user juga jika diperlukan
            // User::find($userId)->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                           ->with('success', "Data user '{$userName}' berhasil dihapus.");
                           
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('admin.users.index')
                           ->with('error', 'User tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting profile: ' . $e->getMessage());
            
            return redirect()->route('admin.users.index')
                           ->with('error', 'Gagal menghapus data user. Silakan coba lagi.');
        }
    }

    /**
     * Export data users ke Excel/CSV (Admin only)
     */
    public function export()
    {
        try {
            $profiles = Profile::with(['user'])
                              ->orderBy('created_at', 'desc')
                              ->get();

            $csvData = [];
            $csvData[] = [
                'No',
                'Nama',
                'Email',
                'KTP',
                'Telepon',
                'Golongan Darah',
                'Rhesus',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Umur',
                'Tempat Lahir',
                'Alamat',
                'Pekerjaan',
                'Kode Donor',
                'ID Donor',
                'Tanggal Daftar'
            ];

            foreach ($profiles as $index => $profile) {
                $csvData[] = [
                    $index + 1,
                    $profile->name,
                    $profile->user ? $profile->user->email : '',
                    $profile->ktp_number,
                    $profile->telephone,
                    $profile->blood_type,
                    $profile->rhesus,
                    $profile->gender,
                    $profile->birth_date ? Carbon::parse($profile->birth_date)->format('d/m/Y') : '',
                    $profile->birth_date ? Carbon::parse($profile->birth_date)->age . ' tahun' : '',
                    $profile->birth_place,
                    $profile->address,
                    $profile->occupation,
                    $profile->donor_code,
                    $profile->donor_id,
                    $profile->created_at->format('d/m/Y H:i')
                ];
            }

            $filename = 'data_users_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($csvData) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                foreach ($csvData as $row) {
                    fputcsv($file, $row, ';'); // Use semicolon as delimiter for Excel compatibility
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting users: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                           ->with('error', 'Gagal mengexport data. Silakan coba lagi.');
        }
    }

    /**
     * Statistik untuk dashboard admin
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_users' => Profile::count(),
                'total_donors' => Profile::whereNotNull('donor_code')->count(),
                'blood_types' => Profile::select('blood_type', DB::raw('count(*) as total'))
                                       ->groupBy('blood_type')
                                       ->pluck('total', 'blood_type')
                                       ->toArray(),
                'gender_distribution' => Profile::select('gender', DB::raw('count(*) as total'))
                                               ->groupBy('gender')
                                               ->pluck('total', 'gender')
                                               ->toArray(),
                'recent_registrations' => Profile::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            ];

            return $stats;
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            return [
                'total_users' => 0,
                'total_donors' => 0,
                'blood_types' => [],
                'gender_distribution' => [],
                'recent_registrations' => 0,
            ];
        }
    }

    /**
     * Validasi keunikan data
     */
    private function validateUnique($field, $value, $excludeId = null)
    {
        $query = Profile::where($field, $value);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Generate unique donor code
     */
    private function generateDonorCode()
    {
        do {
            $code = 'UTDC-' . Str::upper(Str::random(6));
        } while (Profile::where('donor_code', $code)->exists());
        
        return $code;
    }

    /**
     * Generate unique donor ID
     */
    private function generateDonorId()
    {
        do {
            $id = 'D' . Str::upper(Str::random(12));
        } while (Profile::where('donor_id', $id)->exists());
        
        return $id;
    }
}
