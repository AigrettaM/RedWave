<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Menampilkan profile (read-only)
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('user.profile.show', compact('profile'));
    }

    // Form untuk create/edit profile
    public function form()
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Generate KDD dan ID Donor jika belum ada profile
        $kdd = $profile ? $profile->donor_code : 'UTDC-' . Str::upper(Str::random(6));
        $donorId = $profile ? $profile->donor_id : 'D' . Str::upper(Str::random(12));

        // Fetch provinces untuk dropdown
        $provinces = Province::all();

        return view('user.profile.form', compact('profile', 'provinces', 'kdd', 'donorId'));
    }

    // Store atau Update profile
    public function save(Request $request)
    {
        $request->validate([
            'donor_code' => 'required|string|max:255',
            'donor_id' => 'required|string|max:255',
            'ktp_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'blood_type' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:POSITIF,NEGATIF',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'address' => 'required|string',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'village_id' => 'required|exists:indonesia_villages,id',
            'postal_code' => 'required|string|max:10',
            'rt_rw' => 'required|string|max:10',
            'occupation' => 'required|string|max:100',
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        if ($profile) {
            // Update existing profile
            $profile->update($request->all());
            $message = 'Profile berhasil diupdate!';
        } else {
            // Create new profile
            $user->profile()->create($request->all());
            $message = 'Profile berhasil dibuat!';
        }

        return redirect()->route('profile.show')->with('success', $message);
    }
}
