<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_code',
        'donor_id',
        'ktp_number',
        'name',
        'gender',
        'blood_type',
        'rhesus',
        'birth_date',
        'birth_place',
        'telephone',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postal_code',
        'rt_rw',
        'occupation',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Provinsi
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // Relasi ke Kota
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Relasi ke Kecamatan
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Relasi ke Desa
    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
