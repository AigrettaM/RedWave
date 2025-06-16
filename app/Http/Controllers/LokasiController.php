<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::query();
        
        // Handle search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Handle jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Handle export
        if ($request->filled('export') && $request->export === 'excel') {
            return $this->exportToExcel($query->get());
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $lokasis = $query->orderBy('created_at', 'desc')->paginate($perPage);

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
            'jenis' => 'nullable|in:kota,kabupaten,provinsi',
            'status' => 'required|in:aktif,tidak_aktif',
            'kontak' => 'nullable|string|max:20',
            'kapasitas' => 'nullable|integer|min:1',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'tanggal_operasional' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('lokasi-images', 'public');
        }

        Lokasi::create($validated);

        return redirect()->route('admin.lokasis.index')
                        ->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource - PERBAIKAN UNTUK ERROR 500
     */
    public function show(Lokasi $lokasi)
    {
        try {
            // Log untuk debugging
            Log::info('Accessing lokasi show method', ['lokasi_id' => $lokasi->id]);

            // Return JSON response for AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $lokasi->id,
                    'nama' => $lokasi->nama,
                    'alamat' => $lokasi->alamat,
                    'kota' => $lokasi->kota,
                    'jenis' => $lokasi->jenis ?? 'kota',
                    'status' => $lokasi->status,
                    'kontak' => $lokasi->kontak,
                    'kapasitas' => $lokasi->kapasitas,
                    'jam_buka' => $lokasi->jam_buka,
                    'jam_tutup' => $lokasi->jam_tutup,
                    'tanggal_operasional' => $lokasi->tanggal_operasional ? $lokasi->tanggal_operasional->format('Y-m-d') : null,
                    'latitude' => $lokasi->latitude,
                    'longitude' => $lokasi->longitude,
                    'deskripsi' => $lokasi->deskripsi,
                    'gambar' => $lokasi->gambar ? asset('storage/' . $lokasi->gambar) : null,
                    'created_at' => $lokasi->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $lokasi->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            // Pengkondisian gambar
          if ($lokasi->gambar) {
              if (str_starts_with($lokasi->gambar, 'lokasi-images/')) {
                  // Data baru
                  $data['gambar'] = asset('storage/' . $lokasi->gambar);
              } else {
                  // Data lama
                  $data['gambar'] = asset($lokasi->gambar);
              }
          } else {
              $data['gambar'] = null;
          }
            
            // Return view for normal request
            return view('admin.lokasis.show', compact('lokasi'));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in lokasi show method', [
                'lokasi_id' => $lokasi->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal memuat data lokasi: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal memuat data lokasi');
        }
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
            'jenis' => 'nullable|in:kota,kabupaten,provinsi',
            'status' => 'required|in:aktif,tidak_aktif',
            'kontak' => 'nullable|string|max:20',
            'kapasitas' => 'nullable|integer|min:1',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'tanggal_operasional' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($lokasi->gambar) {
                Storage::disk('public')->delete($lokasi->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('lokasi-images', 'public');
        }

        $lokasi->update($validated);

        return redirect()->route('admin.lokasis.index')
                        ->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(Lokasi $lokasi)
    {
        try {
            // Delete image if exists
            if ($lokasi->gambar) {
                Storage::disk('public')->delete($lokasi->gambar);
            }

            $lokasi->delete();

            return redirect()->route('admin.lokasis.index')
                            ->with('success', 'Lokasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.lokasis.index')
                            ->with('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status lokasi
     */
    public function toggleStatus(Request $request, Lokasi $lokasi)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:aktif,tidak_aktif'
            ]);

            $lokasi->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah',
                'new_status' => $lokasi->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk status update
     */
    public function bulkStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:lokasis,id',
                'status' => 'required|in:aktif,tidak_aktif'
            ]);

            $count = Lokasi::whereIn('id', $validated['ids'])
                          ->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => "Status {$count} lokasi berhasil diubah",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:lokasis,id'
            ]);

            $lokasis = Lokasi::whereIn('id', $validated['ids'])->get();
            
            // Delete images
            foreach ($lokasis as $lokasi) {
                if ($lokasi->gambar) {
                    Storage::disk('public')->delete($lokasi->gambar);
                }
            }

            $count = Lokasi::whereIn('id', $validated['ids'])->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} lokasi berhasil dihapus",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lokasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($lokasis)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="lokasi-donor-' . date('Y-m-d') . '.xls"',
        ];

        $content = "No\tNama\tAlamat\tKota\tJenis\tStatus\tKontak\tKapasitas\tJam Operasional\tTanggal Operasional\n";
        
        foreach ($lokasis as $index => $lokasi) {
            $jamOperasional = '';
            if ($lokasi->jam_buka && $lokasi->jam_tutup) {
                $jamOperasional = $lokasi->jam_buka . ' - ' . $lokasi->jam_tutup;
            }
            
            $content .= ($index + 1) . "\t" .
                       $lokasi->nama . "\t" .
                       $lokasi->alamat . "\t" .
                       $lokasi->kota . "\t" .
                       ucfirst($lokasi->jenis ?? 'kota') . "\t" .
                       ucfirst($lokasi->status) . "\t" .
                       ($lokasi->kontak ?? '-') . "\t" .
                       ($lokasi->kapasitas ?? '-') . "\t" .
                       ($jamOperasional ?: '-') . "\t" .
                       ($lokasi->tanggal_operasional ? $lokasi->tanggal_operasional->format('d/m/Y') : '-') . "\n";
        }

        return response($content, 200, $headers);
    }

    // Public methods untuk frontend
    public function getByKota($kota)
    {
        $lokasis = Lokasi::where('kota', $kota)
                         ->where('status', 'aktif')
                         ->get();
        return response()->json($lokasis);
    }

    public function getNearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10); // default 10km
        
        $lokasis = Lokasi::where('status', 'aktif')
                         ->whereNotNull('latitude')
                         ->whereNotNull('longitude')
                         ->get()
                         ->filter(function ($lokasi) use ($latitude, $longitude, $radius) {
                             $distance = $this->calculateDistance(
                                 $latitude, $longitude,
                                 $lokasi->latitude, $lokasi->longitude
                             );
                             return $distance <= $radius;
                         });
        
        return response()->json($lokasis);
    }

    /**
     * Calculate distance between two coordinates
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
