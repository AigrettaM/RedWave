<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
  // ========================================
  // ADMIN METHODS (untuk /lokasis routes)
  // ========================================

  /**
   * Display a listing of the resource for admin
   */
  public function index(Request $request)
  {
      try {
          $query = Lokasi::query();

          // Search functionality
          if ($request->filled('search')) {
              $search = $request->search;
              $query->where(function($q) use ($search) {
                  $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
              });
          }

          // Filter by status
          if ($request->filled('status')) {
              $query->where('status', $request->status);
          }

          // Filter by kota
          if ($request->filled('kota')) {
              $query->where('kota', $request->kota);
          }

          // Filter by jenis
          if ($request->filled('jenis')) {
              $query->where('jenis', $request->jenis);
          }

          // Sorting
          $sortBy = $request->get('sort_by', 'created_at');
          $sortOrder = $request->get('sort_order', 'desc');
          $query->orderBy($sortBy, $sortOrder);

          // Pagination
          $perPage = $request->get('per_page', 10);
          $lokasis = $query->paginate($perPage);

          // Get data for filter dropdowns
          $kotas = Lokasi::distinct()->pluck('kota')->sort();
          $statuses = ['aktif', 'tidak_aktif'];
          $jenisOptions = ['kota', 'kabupaten', 'provinsi'];

          // Statistics
          $stats = [
              'total' => Lokasi::count(),
              'aktif' => Lokasi::where('status', 'aktif')->count(),
              'tidak_aktif' => Lokasi::where('status', 'tidak_aktif')->count(),
              'bulan_ini' => Lokasi::whereMonth('created_at', now()->month)->count(),
          ];

          return view('admin.lokasis.index', compact(
              'lokasis', 'kotas', 'statuses', 'jenisOptions', 'stats'
          ));

      } catch (\Exception $e) {
          Log::error('Error in admin lokasis index', [
              'error' => $e->getMessage(),
              'trace' => $e->getTraceAsString()
          ]);

          return back()->with('error', 'Gagal memuat data lokasi');
      }
  }

  /**
   * Show the form for creating a new resource
   */
  public function create()
  {
      return view('admin.lokasis.create');
  }

  /**
   * Store a newly created resource in storage
   */
  public function store(Request $request)
  {
      $request->validate([
          'nama' => 'required|string|max:255',
          'alamat' => 'required|string',
          'kota' => 'required|string|max:100',
          'jenis' => 'required|in:kota,kabupaten,provinsi',
          'kontak' => 'nullable|string|max:50',
          'jam_buka' => 'nullable|date_format:H:i',
          'jam_tutup' => 'nullable|date_format:H:i',
          'kapasitas' => 'nullable|integer|min:1',
          'fasilitas' => 'nullable|string',
          'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
          'latitude' => 'nullable|numeric|between:-90,90',
          'longitude' => 'nullable|numeric|between:-180,180',
          'status' => 'required|in:aktif,tidak_aktif',
      ]);

      try {
          DB::beginTransaction();

          $data = $request->except(['gambar']);

          // Handle file upload
          if ($request->hasFile('gambar')) {
              $file = $request->file('gambar');
              $filename = time() . '_' . $file->getClientOriginalName();
              $path = $file->storeAs('lokasis', $filename, 'public');
              $data['gambar'] = $path;
          }

          Lokasi::create($data);

          DB::commit();

          return redirect()->route('lokasis.index')->with('success', 'Lokasi berhasil ditambahkan!');

      } catch (\Exception $e) {
          DB::rollBack();

          Log::error('Error creating lokasi', [
              'error' => $e->getMessage(),
              'data' => $request->all()
          ]);

          return back()->withInput()->with('error', 'Gagal menambahkan lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Display the specified resource for admin
   */
//   public function show(Lokasi $lokasi)
//   {
//       try {
//           // Load any relationships if needed
//           // $lokasi->load('donors', 'events');

//           return view('admin.lokasis.show', compact('lokasi'));

//       } catch (\Exception $e) {
//           Log::error('Error showing lokasi', [
//               'lokasi_id' => $lokasi->id,
//               'error' => $e->getMessage()
//           ]);

//           return back()->with('error', 'Gagal memuat detail lokasi');
//       }
//   }

  /**
   * Show the form for editing the specified resource
   */
//     public function edit(Lokasi $lokasi)
//   {
//       return view('admin.lokasis.edit', compact('lokasi'));
//   }

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

  /**
   * Update the specified resource in storage
   */
  public function update(Request $request, Lokasi $lokasi)
  {
      $request->validate([
          'nama' => 'required|string|max:255',
          'alamat' => 'required|string',
          'kota' => 'required|string|max:100',
          'jenis' => 'required|in:kota,kabupaten,provinsi',
          'kontak' => 'nullable|string|max:50',
          'jam_buka' => 'nullable|date_format:H:i',
          'jam_tutup' => 'nullable|date_format:H:i',
          'kapasitas' => 'nullable|integer|min:1',
          'fasilitas' => 'nullable|string',
          'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
          'latitude' => 'nullable|numeric|between:-90,90',
          'longitude' => 'nullable|numeric|between:-180,180',
          'status' => 'required|in:aktif,tidak_aktif',
      ]);

      try {
          DB::beginTransaction();

          $data = $request->except(['gambar']);

          // Handle file upload
          if ($request->hasFile('gambar')) {
              // Delete old image - hanya jika dari storage (upload), bukan dari seeder
              if ($lokasi->gambar && str_starts_with($lokasi->gambar, 'lokasis/') && Storage::disk('public')->exists($lokasi->gambar)) {
                  Storage::disk('public')->delete($lokasi->gambar);
              }

              $file = $request->file('gambar');
              $filename = time() . '_' . $file->getClientOriginalName();
              $path = $file->storeAs('lokasis', $filename, 'public');
              $data['gambar'] = $path;
          }

          $lokasi->update($data);

          DB::commit();

          return redirect()->route('lokasis.index')->with('success', 'Lokasi berhasil diperbarui!');

      } catch (\Exception $e) {
          DB::rollBack();

          Log::error('Error updating lokasi', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage(),
              'data' => $request->all()
          ]);

          return back()->withInput()->with('error', 'Gagal memperbarui lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Remove the specified resource from storage
   */
  public function destroy(Lokasi $lokasi)
  {
      try {
          DB::beginTransaction();

          // Delete image file - hanya jika dari storage (upload), bukan dari seeder
          if ($lokasi->gambar && str_starts_with($lokasi->gambar, 'lokasis/') && Storage::disk('public')->exists($lokasi->gambar)) {
              Storage::disk('public')->delete($lokasi->gambar);
          }

          $lokasi->delete();

          DB::commit();

          return redirect()->route('lokasis.index')->with('success', 'Lokasi berhasil dihapus!');

      } catch (\Exception $e) {
          DB::rollBack();

          Log::error('Error deleting lokasi', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage()
          ]);

          return back()->with('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Activate lokasi
   */
  public function activate(Lokasi $lokasi)
  {
      try {
          $lokasi->update(['status' => 'aktif']);
          
          return redirect()->back()->with('success', 'Lokasi berhasil diaktifkan!');
      } catch (\Exception $e) {
          Log::error('Error activating lokasi', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage()
          ]);

          return redirect()->back()->with('error', 'Gagal mengaktifkan lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Deactivate lokasi
   */
  public function deactivate(Lokasi $lokasi)
  {
      try {
          $lokasi->update(['status' => 'tidak_aktif']);
          
          return redirect()->back()->with('success', 'Lokasi berhasil dinonaktifkan!');
      } catch (\Exception $e) {
          Log::error('Error deactivating lokasi', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage()
          ]);

          return redirect()->back()->with('error', 'Gagal menonaktifkan lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Toggle status lokasi
   */
  public function toggleStatus(Lokasi $lokasi)
  {
      try {
          $newStatus = $lokasi->status === 'aktif' ? 'tidak_aktif' : 'aktif';
          $lokasi->update(['status' => $newStatus]);
          
          $message = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
          return redirect()->back()->with('success', "Lokasi berhasil {$message}!");
          
      } catch (\Exception $e) {
          Log::error('Error toggling lokasi status', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage()
          ]);

          return redirect()->back()->with('error', 'Gagal mengubah status lokasi: ' . $e->getMessage());
      }
  }

  /**
   * Show statistics for specific lokasi
   */
  public function statistics(Lokasi $lokasi)
  {
      try {
          // Contoh statistik - sesuaikan dengan kebutuhan
          $stats = [
              'total_donor' => 0, // Nanti bisa diambil dari tabel donor
              'donor_bulan_ini' => 0,
              'donor_minggu_ini' => 0,
              'rata_rata_donor_perhari' => 0,
              'kapasitas_terpakai' => 0,
              'tingkat_okupansi' => 0,
          ];
          
          // Jika ada relasi dengan tabel donor, bisa ditambahkan:
          // $stats['total_donor'] = $lokasi->donors()->count();
          // $stats['donor_bulan_ini'] = $lokasi->donors()->whereMonth('created_at', now()->month)->count();
          // $stats['donor_minggu_ini'] = $lokasi->donors()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
          // $stats['rata_rata_donor_perhari'] = $stats['donor_bulan_ini'] / now()->day;
          
          return view('admin.lokasis.statistics', compact('lokasi', 'stats'));
          
      } catch (\Exception $e) {
          Log::error('Error in statistics method', [
              'lokasi_id' => $lokasi->id,
              'error' => $e->getMessage()
          ]);
          
          return redirect()->back()->with('error', 'Gagal memuat statistik lokasi');
      }
  }

  /**
   * Bulk status update
   */
  public function bulkStatus(Request $request)
  {
      $request->validate([
          'ids' => 'required|array',
          'ids.*' => 'exists:lokasis,id',
          'status' => 'required|in:aktif,tidak_aktif'
      ]);

      try {
          DB::beginTransaction();

          Lokasi::whereIn('id', $request->ids)->update(['status' => $request->status]);

          DB::commit();

          $count = count($request->ids);
          $statusText = $request->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
          
          return redirect()->back()->with('success', "{$count} lokasi berhasil {$statusText}!");

      } catch (\Exception $e) {
          DB::rollBack();

          Log::error('Error in bulk status update', [
              'ids' => $request->ids,
              'status' => $request->status,
              'error' => $e->getMessage()
          ]);

          return redirect()->back()->with('error', 'Gagal memperbarui status lokasi');
      }
  }

  /**
   * Bulk delete
   */
  public function bulkDelete(Request $request)
  {
      $request->validate([
          'ids' => 'required|array',
          'ids.*' => 'exists:lokasis,id'
      ]);

      try {
          DB::beginTransaction();

          $lokasis = Lokasi::whereIn('id', $request->ids)->get();
          
          // Delete images - hanya yang dari storage (upload)
          foreach ($lokasis as $lokasi) {
              if ($lokasi->gambar && str_starts_with($lokasi->gambar, 'lokasis/') && Storage::disk('public')->exists($lokasi->gambar)) {
                  Storage::disk('public')->delete($lokasi->gambar);
              }
          }

          // Delete records
          Lokasi::whereIn('id', $request->ids)->delete();

          DB::commit();

          $count = count($request->ids);
          return redirect()->back()->with('success', "{$count} lokasi berhasil dihapus!");

      } catch (\Exception $e) {
          DB::rollBack();

          Log::error('Error in bulk delete', [
              'ids' => $request->ids,
              'error' => $e->getMessage()
          ]);

          return redirect()->back()->with('error', 'Gagal menghapus lokasi');
      }
  }

  /**
   * Display public listing of locations
   */
  public function publicIndex(Request $request)
  {
      try {
          $query = Lokasi::where('status', 'aktif');
          
          // Handle search untuk public
          if ($request->filled('search')) {
              $search = $request->search;
              $query->where(function($q) use ($search) {
                  $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%");
              });
          }

          // Filter berdasarkan kota
          if ($request->filled('kota')) {
              $query->where('kota', $request->kota);
          }

          // Filter berdasarkan jenis
          if ($request->filled('jenis')) {
              $query->where('jenis', $request->jenis);
          }

          // Pagination untuk public
          $perPage = $request->get('per_page', 12);
          $lokasis = $query->orderBy('created_at', 'desc')->paginate($perPage);

          // Get data untuk filter dropdown
          $kotas = Lokasi::where('status', 'aktif')
                        ->distinct()
                        ->pluck('kota')
                        ->sort();

          // Variable jenisOptions yang hilang
          $jenisOptions = ['kota', 'kabupaten', 'provinsi'];

          // Return view dengan semua variable yang dibutuhkan
          return view('informasi.location.index', compact('lokasis', 'kotas', 'jenisOptions'));
          
      } catch (\Exception $e) {
          Log::error('Error in publicIndex method', [
              'error' => $e->getMessage(),
              'trace' => $e->getTraceAsString()
          ]);

          return back()->with('error', 'Gagal memuat data lokasi');
      }
  }

  /**
   * Display public detail of specific location
   */
  public function publicShow($id)
  {
      try {
          $lokasi = Lokasi::where('status', 'aktif')->findOrFail($id);
          
          $nearbyLokasis = Lokasi::where('status', 'aktif')
                                ->where('id', '!=', $id)
                                ->where('kota', $lokasi->kota)
                                ->limit(3)
                                ->get();

          return view('informasi.location.show', compact('lokasi', 'nearbyLokasis'));
          
      } catch (\Exception $e) {
          Log::error('Error in publicShow method', [
              'id' => $id,
              'error' => $e->getMessage()
          ]);

          return redirect()->route('location.index')->with('error', 'Lokasi tidak ditemukan');
      }
  }

  /**
   * Search nearby locations (for API or AJAX)
   */
  public function publicNearby(Request $request)
  {
      $request->validate([
          'latitude' => 'required|numeric|between:-90,90',
          'longitude' => 'required|numeric|between:-180,180',
          'radius' => 'nullable|numeric|min:1|max:100', // dalam km
      ]);

      try {
          $lat = $request->latitude;
          $lng = $request->longitude;
          $radius = $request->get('radius', 10); // default 10km

          // Haversine formula untuk mencari lokasi terdekat
          $lokasis = Lokasi::where('status', 'aktif')
              ->whereNotNull('latitude')
              ->whereNotNull('longitude')
              ->selectRaw("
                  *,
                  (6371 * acos(
                      cos(radians(?)) * 
                      cos(radians(latitude)) * 
                      cos(radians(longitude) - radians(?)) + 
                      sin(radians(?)) * 
                      sin(radians(latitude))
                  )) AS distance
              ", [$lat, $lng, $lat])
              ->having('distance', '<=', $radius)
              ->orderBy('distance')
              ->limit(10)
              ->get();

          return response()->json([
              'success' => true,
              'data' => $lokasis,
              'message' => 'Lokasi terdekat berhasil ditemukan'
          ]);

      } catch (\Exception $e) {
          Log::error('Error in publicNearby method', [
              'error' => $e->getMessage(),
              'request' => $request->all()
          ]);

          return response()->json([
              'success' => false,
              'message' => 'Gagal mencari lokasi terdekat'
          ], 500);
      }
  }
}