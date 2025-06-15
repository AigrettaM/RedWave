<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasis';
    protected $fillable = [
        'nama',
        'alamat',
        'kota',
        'provinsi',
        'tanggal_operasional',
        'jam_buka',
        'jam_tutup',
        'kontak',
        'kapasitas',
        'gambar',
        'status',
        'jenis',
        'latitude',
        'longitude',
        'deskripsi'
    ];

    protected $casts = [
        'tanggal_operasional' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Accessor untuk format jam operasional
    protected function jamOperasional(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->jam_buka && $this->jam_tutup) {
                    return $this->jam_buka . ' - ' . $this->jam_tutup;
                }
                return 'Tidak tersedia';
            }
        );
    }

    // Accessor untuk alamat lengkap
    protected function alamatLengkap(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->alamat . ', ' . $this->kota . ', ' . $this->provinsi
        );
    }

    // Scope untuk filter berdasarkan kota
    public function scopeByKota($query, $kota)
    {
        return $query->where('kota', $kota);
    }

    // Scope untuk filter berdasarkan status
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk filter berdasarkan jenis
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    // Relasi ke Donor
    public function donors()
    {
        return $this->hasMany(Donor::class);
    }

    // Method untuk mendapatkan jarak (jika diperlukan untuk fitur pencarian terdekat)
    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Radius bumi dalam kilometer

        $latFrom = deg2rad($latitude);
        $lonFrom = deg2rad($longitude);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/lokasi/' . $this->gambar);
        }
        return asset('images/default-location.jpg'); // Gambar default
    }
}
