<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::query();
        
        // Fitur pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%')
                  ->orWhere('kota', 'like', '%' . $request->search . '%');
        }
        
        $lokasis = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.lokasis.index', compact('lokasis'));
    }

    public function create()
    {
        return view('admin.lokasis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
            'tanggal_operasional' => 'required|date',
            'kapasitas' => 'nullable|integer|min:1',
            'jenis' => 'required|in:kota,provinsi,cabang',
            'status' => 'required|in:aktif,tidak_aktif',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi gambar
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaGambar = time() . '_' . Str::random(10) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/lokasi', $namaGambar);
            $validated['gambar'] = $namaGambar;
        }

        Lokasi::create($validated);

        return redirect()->route('lokasis.index')
            ->with('success', 'Lokasi donor berhasil ditambahkan!');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasis.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
            'tanggal_operasional' => 'required|date',
            'kapasitas' => 'nullable|integer|min:1',
            'jenis' => 'required|in:kota,provinsi,cabang',
            'status' => 'required|in:aktif,tidak_aktif',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($lokasi->gambar && Storage::exists('public/lokasi/' . $lokasi->gambar)) {
                Storage::delete('public/lokasi/' . $lokasi->gambar);
            }

            $gambar = $request->file('gambar');
            $namaGambar = time() . '_' . Str::random(10) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/lokasi', $namaGambar);
            $validated['gambar'] = $namaGambar;
        }

        $lokasi->update($validated);

        return redirect()->route('lokasis.index')
            ->with('success', 'Lokasi donor berhasil diperbarui!');
    }

    public function destroy(Lokasi $lokasi)
    {
        try {
            $lokasi->delete();
            return redirect()->route('lokasis.index')
                            ->with('success', 'Lokasi donor berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('lokasis.index')
                            ->with('error', 'Gagal menghapus lokasi. Mungkin masih ada data terkait.');
        }

        // Hapus gambar jika ada
        if ($lokasi->gambar && Storage::exists('public/lokasi/' . $lokasi->gambar)) {
            Storage::delete('public/lokasi/' . $lokasi->gambar);
        }

        $lokasi->delete();

        return redirect()->route('lokasis.index')
            ->with('success', 'Lokasi donor berhasil dihapus!');
    }

    public function getByKota($kota)
    {
        $lokasis = Lokasi::byKota($kota)->aktif()->get();
        return response()->json($lokasis);
    }

    public function getNearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10); // default 10km
        
        $lokasis = Lokasi::aktif()->get()->filter(function ($lokasi) use ($latitude, $longitude, $radius) {
            $distance = $lokasi->getDistanceFrom($latitude, $longitude);
            return $distance !== null && $distance <= $radius;
        });
        
        return response()->json($lokasis);
    }
    // Update method ini di LokasiController.php

public function publicIndex(Request $request)
{
    $query = Lokasi::aktif(); // Hanya tampilkan yang aktif
    
    // Filter berdasarkan kota jika ada
    if ($request->has('kota') && $request->kota != '') {
        $query->byKota($request->kota);
    }
    
    // Filter berdasarkan jenis jika ada
    if ($request->has('jenis') && $request->jenis != '') {
        $query->byJenis($request->jenis);
    }
    
    // Pencarian
    if ($request->has('search') && $request->search != '') {
        $query->where(function($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('alamat', 'like', '%' . $request->search . '%')
              ->orWhere('kota', 'like', '%' . $request->search . '%');
        });
    }
    
    $lokasis = $query->orderBy('created_at', 'desc')->paginate(12);
    
    // Data untuk filter dropdown
    $kotas = Lokasi::aktif()->distinct()->pluck('kota')->sort();
    $jenisOptions = ['provinsi', 'kota', 'cabang'];
    
    return view('informasi.location.index', compact('lokasis', 'kotas', 'jenisOptions'));
}

public function publicShow(Lokasi $lokasi)
{
    // Hanya tampilkan jika aktif
    if ($lokasi->status !== 'aktif') {
        abort(404);
    }
    
    return view('informasi.location.show', compact('lokasi'));
}

}
