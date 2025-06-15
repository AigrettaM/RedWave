<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\DonorQuestion;
use App\Models\Profile;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DonorController extends Controller
{
    // ==========================================
    // USER METHODS - UNTUK REGULAR USERS
    // ==========================================

    public function index()
    {
        $user = Auth::user();
        
        // ✅ PERBAIKAN: Jika admin mengakses /donor, redirect ke admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('info', 'Silakan gunakan menu admin untuk mengelola donor.');
        }
        
        $profile = $user->profile;
        
        // Inisialisasi variabel umur
        $userAge = null;
        $isAgeEligible = true; // Default true jika tidak ada profile
        $eligibleDate = null;
        
        // Cek umur jika profile ada dan ada birth_date
        if ($profile && $profile->birth_date) {
            $birthDate = Carbon::parse($profile->birth_date);
            $userAge = $birthDate->age;
            
            // Cek kelayakan umur (17-65 tahun)
            $isAgeEligible = ($userAge >= 17 && $userAge <= 65);
            
            // Jika umur kurang dari 17, hitung kapan bisa donor
            if ($userAge < 17) {
                $eligibleDate = $birthDate->copy()->addYears(17);
            }
        }
        
        // Cek donor yang sedang berlangsung
        $currentDonor = Donor::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        // Cek donor terakhir
        $lastDonation = Donor::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Cek apakah bisa donor (gabungan semua syarat)
        $canDonate = false;
        $nextEligibleDate = null;
        
        if ($profile && $isAgeEligible) {
            $canDonate = $this->canUserDonate($user->id);
            if (!$canDonate) {
                $nextEligibleDate = $this->getNextEligibleDate($user->id);
            }
        }
        
        // Progress tracking untuk donor yang sedang berlangsung
        $currentStep = 0;
        $progressPercentage = 0;
        $currentStepName = '';
        $currentStepDescription = '';
        $nextStepRoute = null;
        
        if ($currentDonor) {
            // Tentukan step berdasarkan progress donor
            if (!$currentDonor->lokasi_id) {
                $currentStep = 1;
                $currentStepName = 'Pilih Lokasi';
                $currentStepDescription = 'Pilih lokasi dan tanggal donor';
                $nextStepRoute = route('donor.location', $currentDonor->id);
            } elseif (empty($currentDonor->health_questions)) {
                $currentStep = 2;
                $currentStepName = 'Kuesioner Kesehatan';
                $currentStepDescription = 'Isi kuesioner kesehatan (Tahap 1)';
                $nextStepRoute = route('donor.questions', 1);
            } elseif ($currentDonor->is_eligible === null) {
                // Masih dalam proses kuesioner
                $questionCount = count($currentDonor->health_questions ?? []);
                if ($questionCount < 10) {
                    $currentStep = 2;
                    $currentStepName = 'Kuesioner Kesehatan';
                    $currentStepDescription = 'Isi kuesioner kesehatan (Tahap 1)';
                    $nextStepRoute = route('donor.questions', 1);
                } elseif ($questionCount < 20) {
                    $currentStep = 3;
                    $currentStepName = 'Kuesioner Kesehatan';
                    $currentStepDescription = 'Isi kuesioner kesehatan (Tahap 2)';
                    $nextStepRoute = route('donor.questions', 2);
                } else {
                    $currentStep = 4;
                    $currentStepName = 'Kuesioner Kesehatan';
                    $currentStepDescription = 'Isi kuesioner kesehatan (Tahap 3)';
                    $nextStepRoute = route('donor.questions', 3);
                }
            } elseif ($currentDonor->is_eligible && $currentDonor->status === 'pending') {
                $currentStep = 5;
                $currentStepName = 'Informed Consent';
                $currentStepDescription = 'Baca dan setujui informed consent';
                $nextStepRoute = route('donor.consent');
            } elseif ($currentDonor->status === 'approved') {
                $currentStep = 6;
                $currentStepName = 'Menunggu Donor';
                $currentStepDescription = 'Menunggu jadwal donor di PMI';
                $nextStepRoute = null;
            }
            
            $progressPercentage = ($currentStep / 6) * 100;
        }
        
        // Statistik donor untuk user
        $donorStats = null;
        if ($profile) {
            $donorStats = [
                'total' => Donor::where('user_id', $user->id)->count(),
                'completed' => Donor::where('user_id', $user->id)->where('status', 'completed')->count(),
                'cancelled' => Donor::where('user_id', $user->id)->where('status', 'rejected')->count(),
                'points' => Donor::where('user_id', $user->id)->where('status', 'completed')->count() * 10,
            ];
        }
        
        // Cek apakah ada donor yang baru selesai (untuk notifikasi)
        $recentCompletedDonor = Donor::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->first();
        
        return view('user.donor.index', compact(
            'profile',
            'currentDonor',
            'lastDonation',
            'canDonate',
            'nextEligibleDate',
            'userAge',
            'isAgeEligible',
            'eligibleDate',
            'currentStep',
            'progressPercentage',
            'currentStepName',
            'currentStepDescription',
            'nextStepRoute',
            'donorStats',
            'recentCompletedDonor'
        ));
    }

    public function start()
    {
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Admin tidak boleh akses user donor routes
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user. Gunakan menu admin.');
        }
        
        // Validation checks
        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            return redirect()->route('donor.index')
                ->with('error', 'Profile belum lengkap.');
        }

        if (!Donor::canDonateAgain($user->id)) {
            return redirect()->route('donor.index')
                ->with('error', 'Anda belum dapat mendonor lagi. Minimal jarak 2 minggu dari donor terakhir.');
        }

        // Check if there's already a pending donor session
        $existingDonor = Donor::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingDonor) {
            // Redirect ke step yang sesuai berdasarkan progress
            if (!$existingDonor->lokasi_id) {
                return redirect()->route('donor.location', $existingDonor->id)
                    ->with('info', 'Melanjutkan sesi donor yang belum selesai.');
            } else {
                session(['donor_id' => $existingDonor->id]);
                return redirect()->route('donor.questions', 1)
                    ->with('info', 'Melanjutkan sesi donor yang belum selesai.');
            }
        }

        // Buat donor record baru
        $donorCode = $this->generateDonorCode();
        
        $donor = Donor::create([
            'user_id' => $user->id,
            'donor_code' => $donorCode,
            'health_questions' => [],
            'status' => 'pending'
        ]);

        // Redirect ke pemilihan lokasi dengan donor ID
        return redirect()->route('donor.location', $donor->id)
            ->with('success', 'Proses donor dimulai. Silakan pilih lokasi dan tanggal donor.');
    }

    public function location($donorId)
    {
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        // Validasi donor
        $donor = Donor::where('id', $donorId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Sesi donor tidak valid atau sudah berakhir.');
        }

        // Get active locations
        $lokasis = Lokasi::aktif()->get();

        return view('user.donor.location', compact('lokasis', 'donor'));
    }

    public function saveLocation(Request $request, $donorId)
    {
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        // Validasi donor
        $donor = Donor::where('id', $donorId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Sesi donor tidak valid atau sudah berakhir.');
        }

        $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'alamat' => 'required|string|max:255',
            'donation_date' => 'required|date|after_or_equal:today'
        ], [
            'lokasi_id.required' => 'Pilih lokasi donor',
            'lokasi_id.exists' => 'Lokasi tidak valid',
            'alamat.required' => 'Alamat harus diisi',
            'donation_date.required' => 'Tanggal donor harus dipilih',
            'donation_date.after_or_equal' => 'Tanggal donor tidak boleh kurang dari hari ini'
        ]);

        // Update donor dengan lokasi dan tanggal
        $donor->update([
            'lokasi_id' => $request->lokasi_id,
            'alamat' => $request->alamat,
            'donation_date' => $request->donation_date
        ]);

        session(['donor_id' => $donor->id]);

        return redirect()->route('donor.questions', 1)
            ->with('success', 'Lokasi dan tanggal donor berhasil dipilih. Lanjutkan mengisi kuesioner kesehatan.');
    }

    public function questions($step)
    {
        // ✅ PERBAIKAN: Admin tidak boleh akses user donor
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        if (!in_array($step, [1, 2, 3])) {
            return redirect()->route('donor.index');
        }

        $donorId = session('donor_id');
        if (!$donorId) {
            return redirect()->route('donor.index')
                ->with('error', 'Sesi donor tidak ditemukan. Silakan mulai lagi.');
        }

        $donor = Donor::find($donorId);
        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        // Check if donor belongs to current user
        if ($donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Akses tidak diizinkan.');
        }

        // Define question categories for each step
        $questionCategories = [
            1 => ['today', '48hours', '1week'],
            2 => ['6weeks', '8weeks', '10weeks', '12weeks'],
            3 => ['3years', '1987-now', '1980-now', '1980-1996', 'general']
        ];

        $categories = $questionCategories[$step];
        $questions = collect();

        foreach ($categories as $category) {
            $categoryQuestions = DonorQuestion::getQuestionsByCategory($category);
            $questions = $questions->merge($categoryQuestions);
        }

        // Get existing answers for this step
        $existingAnswers = $donor->health_questions ?? [];

        return view('user.donor.questions', compact('donor', 'questions', 'step', 'existingAnswers'));
    }

    public function saveQuestions(Request $request, $step)
    {
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        if (!in_array($step, [1, 2, 3])) {
            return redirect()->route('donor.index');
        }

        $donorId = session('donor_id');
        $donor = Donor::find($donorId);

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        // Check if donor belongs to current user
        if ($donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Akses tidak diizinkan.');
        }

        // Validate required questions for this step
        $questionCategories = [
            1 => ['today', '48hours', '1week'],
            2 => ['6weeks', '8weeks', '10weeks', '12weeks'],
            3 => ['3years', '1987-now', '1980-now', '1980-1996', 'general']
        ];

        $categories = $questionCategories[$step];
        $requiredQuestions = collect();

        foreach ($categories as $category) {
            $categoryQuestions = DonorQuestion::getQuestionsByCategory($category);
            $requiredQuestions = $requiredQuestions->merge($categoryQuestions);
        }

        // Validate that all questions are answered
        $validationRules = [];
        foreach ($requiredQuestions as $question) {
            $validationRules["question_{$question->id}"] = 'required';
        }

        $request->validate($validationRules, [
            'required' => 'Semua pertanyaan harus dijawab.'
        ]);

        // Get current answers
        $currentAnswers = $donor->health_questions ?? [];
        
        // Add new answers
        $newAnswers = $request->except(['_token']);
        $currentAnswers = array_merge($currentAnswers, $newAnswers);

        // Update donor
        $donor->update([
            'health_questions' => $currentAnswers
        ]);

        // Check if this is the last step
        if ($step == 3) {
            // Evaluate eligibility
            $this->evaluateEligibility($donor);
            
            // Jika TIDAK LAYAK, langsung ke success tanpa consent
            if (!$donor->is_eligible) {
                $donor->update([
                    'status' => 'rejected',
                    'notes' => 'Donor ditolak berdasarkan hasil kuesioner kesehatan.'
                ]);
                
                session()->forget('donor_id');
                return redirect()->route('donor.success', $donor->id)
                    ->with('info', 'Kuesioner selesai. Mohon maaf, Anda belum dapat mendonor saat ini.');
            }
            
            // Jika LAYAK, lanjut ke consent
            return redirect()->route('donor.consent')
                ->with('success', 'Selamat! Anda memenuhi syarat donor. Silakan baca dan setujui informed consent.');
        }

        // Go to next step
        return redirect()->route('donor.questions', $step + 1)
            ->with('success', 'Jawaban berhasil disimpan. Lanjut ke tahap berikutnya.');
    }

    public function consent()
    {
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        $donorId = session('donor_id');
        $donor = Donor::find($donorId);

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        // Check if donor belongs to current user
        if ($donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Akses tidak diizinkan.');
        }

        // Check if all questions have been answered
        if (empty($donor->health_questions)) {
            return redirect()->route('donor.questions', 1)
                ->with('error', 'Anda harus menyelesaikan semua kuesioner terlebih dahulu.');
        }

        // Double check - Jika tidak layak, redirect ke success
        if (!$donor->is_eligible) {
            if ($donor->status === 'pending') {
                $donor->update([
                    'status' => 'rejected',
                    'notes' => 'Donor ditolak berdasarkan hasil kuesioner kesehatan.'
                ]);
            }
            session()->forget('donor_id');
            return redirect()->route('donor.success', $donor->id)
                ->with('info', 'Anda tidak dapat mengakses halaman consent karena belum memenuhi syarat donor.');
        }

        return view('user.donor.consent', compact('donor'));
    }

    public function saveConsent(Request $request)
    {
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        $request->validate([
            'consent' => 'required|accepted'
        ], [
            'consent.required' => 'Anda harus menyetujui informed consent.',
            'consent.accepted' => 'Anda harus menyetujui informed consent.'
        ]);

        $donorId = session('donor_id');
        $donor = Donor::find($donorId);

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        // Check if donor belongs to current user
        if ($donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Akses tidak diizinkan.');
        }

        // Final check - pastikan masih layak
        if (!$donor->is_eligible) {
            session()->forget('donor_id');
            return redirect()->route('donor.success', $donor->id)
                ->with('error', 'Tidak dapat menyimpan consent karena tidak memenuhi syarat donor.');
        }

        // Update status untuk yang layak donor
        $donor->update([
            'status' => 'approved',
            'next_eligible_date' => Carbon::now()->addWeeks(2),
            'notes' => 'Donor disetujui berdasarkan hasil kuesioner kesehatan dan informed consent.'
        ]);

        // Clear session
        session()->forget('donor_id');

        return redirect()->route('donor.success', $donor->id)
            ->with('success', 'Terima kasih! Pendaftaran donor Anda berhasil.');
    }

    public function success($donorId)
    {
        $donor = Donor::with('user', 'lokasi')->find($donorId);

        if (!$donor || $donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        return view('user.donor.success', compact('donor'));
    }

    public function history()
    {
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Admin tidak boleh akses user history
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses riwayat donor user.');
        }
        
        $donors = Donor::where('user_id', $user->id)
            ->with('lokasi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.donor.history', compact('donors'));
    }

    public function detail(Donor $donor)
    {
        // Pastikan user hanya bisa melihat donor milik sendiri
        if ($donor->user_id !== auth()->id()) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }
            abort(403);
        }

        // Jika request AJAX (untuk modal)
        if (request()->wantsJson() || request()->ajax()) {
            try {
                $donor->load(['user', 'lokasi']);
                
                return response()->json([
                    'success' => true,
                    'donor' => [
                        'id' => $donor->id,
                        'donor_code' => $donor->donor_code,
                        'status' => $donor->status,
                        'blood_type' => $donor->blood_type,
                        'weight' => $donor->weight,
                        'height' => $donor->height,
                        'blood_pressure' => $donor->blood_pressure,
                        'is_eligible' => $donor->is_eligible,
                        'notes' => $donor->notes,
                        'rejection_reason' => $donor->rejection_reason,
                        'alamat' => $donor->alamat,
                        'created_at' => $donor->created_at,
                        'approved_at' => $donor->approved_at,
                        'donation_date' => $donor->donation_date,
                        'next_eligible_date' => $donor->next_eligible_date,
                        'user' => [
                            'name' => $donor->user->name,
                            'email' => $donor->user->email,
                            'phone' => $donor->user->phone ?? '-'
                        ],
                        'lokasi' => $donor->lokasi ? [
                            'nama' => $donor->lokasi->nama,
                            'alamat' => $donor->lokasi->alamat_lengkap
                        ] : null
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem'
                ], 500);
            }
        }

        // Jika bukan AJAX, return view biasa
        return view('user.donor.detail', compact('donor'));
    }

    public function certificate($donorId)
    {
        $donor = Donor::with('user', 'lokasi')->find($donorId);

        if (!$donor || $donor->user_id !== auth()->id()) {
            return redirect()->route('donor.history')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Sertifikat hanya untuk status 'completed'
        if ($donor->status !== 'completed') {
            return redirect()->route('donor.history')
                ->with('error', 'Sertifikat hanya tersedia setelah proses donor selesai di PMI.');
        }

        return view('user.donor.certificate', compact('donor'));
    }

    public function cancel()
    {
        $user = auth()->user();
        
        // ✅ PERBAIKAN: Admin tidak boleh akses
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admin tidak dapat mengakses fitur donor user.');
        }

        // Find pending donor
        $donor = Donor::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$donor) {
            return redirect()->route('donor.index')
                ->with('error', 'Tidak ada proses donor yang sedang berlangsung.');
        }

        // Update status ke rejected
        $donor->update([
            'status' => 'rejected',
            'notes' => 'Proses donor dibatalkan oleh pengguna.'
        ]);

        // Clear session
        session()->forget('donor_id');

        return redirect()->route('donor.index')
            ->with('success', 'Proses donor berhasil dibatalkan.');
    }

    // ==========================================
    // ADMIN METHODS - UNTUK ADMIN SAJA
    // ==========================================

    public function adminIndex()
    {
        // ✅ Pastikan hanya admin yang bisa akses
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('donor.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }

        try {
            $donors = Donor::with('user', 'lokasi')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);
            
            $totalDonors = Donor::count();
            $pendingCount = Donor::where('status', 'pending')->count();
            $approvedCount = Donor::where('status', 'approved')->count();
            $completedCount = Donor::where('status', 'completed')->count();
            $rejectedCount = Donor::where('status', 'rejected')->count();
            
            // ✅ PERBAIKAN: Return view admin/donors/index
            return view('admin.donors.index', compact(
                'donors', 
                'totalDonors', 
                'pendingCount',
                'approvedCount', 
                'completedCount', 
                'rejectedCount'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function adminShow($donorId)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        try {
            $donor = Donor::with('user', 'lokasi')->findOrFail($donorId);
            
            return response()->json([
                'success' => true,
                'donor' => $donor
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Donor tidak ditemukan'
            ], 404);
        }
    }

    public function adminUpdateStatus(Request $request, $donorId)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled,completed',
            'rejection_reason' => 'required_if:status,rejected|string|max:500',
            'donation_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        try {
            $donor = Donor::findOrFail($donorId);
            
            $donor->status = $request->status;
            
            if ($request->status === 'rejected') {
                $donor->rejection_reason = $request->rejection_reason;
            }
            
            // Handle completed status
            if ($request->status === 'completed') {
                $donor->donation_date = $request->donation_date ?? now();
                $donor->completed_at = now();
                if ($request->notes) {
                    $donor->notes = $request->notes;
                }
            }
            
            // Handle approved status
            if ($request->status === 'approved') {
                $donor->approved_at = now();
            }
            
            $donor->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Status berhasil diupdate'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

public function adminComplete(Request $request, $donorId)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
    }

    $request->validate([
        'donation_date' => 'required|date|before_or_equal:today',
        'notes' => 'nullable|string|max:500'
    ]);

    try {
        $donor = Donor::findOrFail($donorId);
        
        // Pastikan donor dalam status approved
        if ($donor->status !== 'approved') {
            return response()->json([
                'success' => false,
                'error' => 'Hanya donor dengan status approved yang dapat diselesaikan'
            ], 400);
        }
        
        // Update donor ke status completed
        $donor->update([
            'status' => 'completed',
            'donation_date' => $request->donation_date,
            'completed_at' => now(),
            'notes' => $request->notes ?? 'Donor berhasil diselesaikan oleh admin',
            'next_eligible_date' => Carbon::parse($request->donation_date)->addDays(56) // 8 minggu
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Donor berhasil diselesaikan',
            'donor' => $donor->fresh()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Gagal menyelesaikan donor: ' . $e->getMessage()
        ], 500);
    }
}

public function adminExport()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak');
    }

    try {
        $donors = Donor::with(['user', 'lokasi'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        $filename = 'data_donor_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($donors) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Kode Donor',
                'Nama Lengkap',
                'Email',
                'Telepon',
                'Status',
                'Lokasi',
                'Alamat',
                'Tanggal Donor',
                'Layak Donor',
                'Alasan Penolakan',
                'Catatan',
                'Tanggal Daftar',
                'Tanggal Disetujui',
                'Tanggal Selesai'
            ]);
            
            // Data rows
            foreach ($donors as $donor) {
                fputcsv($file, [
                    $donor->donor_code,
                    $donor->user->name ?? '-',
                    $donor->user->email ?? '-',
                    $donor->user->phone ?? '-',
                    ucfirst($donor->status),
                    $donor->lokasi->nama ?? '-',
                    $donor->alamat ?? '-',
                    $donor->donation_date ? Carbon::parse($donor->donation_date)->format('d/m/Y') : '-',
                    $donor->is_eligible ? 'Ya' : ($donor->is_eligible === false ? 'Tidak' : 'Belum Dievaluasi'),
                    $donor->rejection_reason ?? '-',
                    $donor->notes ?? '-',
                    $donor->created_at->format('d/m/Y H:i'),
                    $donor->approved_at ? Carbon::parse($donor->approved_at)->format('d/m/Y H:i') : '-',
                    $donor->completed_at ? Carbon::parse($donor->completed_at)->format('d/m/Y H:i') : '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
    }
}

// ==========================================
// HELPER METHODS - PRIVATE FUNCTIONS
// ==========================================

private function getNextEligibleDate($userId)
{
    $lastDonation = Donor::where('user_id', $userId)
        ->where('status', 'completed')
        ->orderBy('donation_date', 'desc')
        ->first();
    
    if ($lastDonation) {
        return Carbon::parse($lastDonation->donation_date)->addDays(56); // 8 minggu
    }
    
    return null;
}

private function canUserDonate($userId)
{
    $lastDonation = Donor::where('user_id', $userId)
        ->where('status', 'completed')
        ->orderBy('donation_date', 'desc')
        ->first();
    
    if (!$lastDonation) {
        return true; // Belum pernah donor
    }
    
    // Minimal 2 minggu dari donor terakhir
    $minInterval = Carbon::parse($lastDonation->donation_date)->addDays(14);
    return Carbon::now()->greaterThanOrEqualTo($minInterval);
}

private function generateDonorCode()
{
    do {
        $code = 'DN' . date('Ymd') . strtoupper(Str::random(4));
    } while (Donor::where('donor_code', $code)->exists());
    
    return $code;
}

private function evaluateEligibility($donor)
{
    $answers = $donor->health_questions;
    
    \Log::info('=== DEBUGGING DONOR ELIGIBILITY ===');
    \Log::info('Donor ID: ' . $donor->id);
    \Log::info('All Answers:', $answers);
    
    // Get disqualifying questions
    $disqualifyingQuestions = DonorQuestion::where('is_disqualifying', true)->get();
    
    $isEligible = true;
    $rejectionReasons = [];

    // Check each disqualifying question
    foreach ($disqualifyingQuestions as $question) {
        $questionKey = "question_" . $question->id;
        
        if (isset($answers[$questionKey])) {
            $answer = $answers[$questionKey];
            
            \Log::info("Question: {$question->question}");
            \Log::info("Answer Key: {$questionKey}");
            \Log::info("Answer Value: {$answer}");
            
            if ($this->shouldDisqualify($question, $answer)) {
                $isEligible = false;
                $rejectionReasons[] = $question->question;
                \Log::info("DISQUALIFIED by: {$question->question}");
            }
        }
    }

    // Perform additional checks (age, weight, etc.)
    $additionalChecks = $this->performAdditionalChecks($donor, $answers);
    \Log::info('Additional Checks Result:', $additionalChecks);
    
    if (!$additionalChecks['eligible']) {
        $isEligible = false;
        $rejectionReasons = array_merge($rejectionReasons, $additionalChecks['reasons']);
    }

    // Special case: If only one health question and answered "yes" (sehat)
    if (count($answers) == 1) {
        $firstAnswer = reset($answers);
        $normalizedAnswer = $this->normalizeAnswer($firstAnswer);
        
        if ($normalizedAnswer === 'yes') {
            \Log::info('OVERRIDE: Single health question answered YES - forcing eligible');
            $isEligible = true;
            $rejectionReasons = [];
        }
    }

    \Log::info('Final Eligibility: ' . ($isEligible ? 'ELIGIBLE' : 'NOT ELIGIBLE'));
    \Log::info('Rejection Reasons:', $rejectionReasons);
    \Log::info('=== END DEBUGGING ===');

    // Update donor eligibility
    $donor->update([
        'is_eligible' => $isEligible,
        'rejection_reason' => $isEligible ? null : implode('; ', $rejectionReasons)
    ]);

    return $isEligible;
}

private function normalizeAnswer($answer)
{
    $normalizedAnswer = strtolower(trim($answer));
    
    $answerMap = [
        'ya' => 'yes',
        'tidak' => 'no',
        'yes' => 'yes',
        'no' => 'no'
    ];
    
    return $answerMap[$normalizedAnswer] ?? $normalizedAnswer;
}

private function shouldDisqualify($question, $answer)
{
    $normalizedAnswer = $this->normalizeAnswer($answer);
    
    \Log::info("Original answer: {$answer}, Normalized: {$normalizedAnswer}");

    // Questions that disqualify on "YES" answer
    $disqualifyOnYes = [
        'Apakah Anda sedang minum antibiotik?',
        'Apakah Anda sedang minum obat lain untuk infeksi?',
        'Apakah Anda sedang minum aspirin?',
        'Apakah Anda pernah minum obat untuk mencegah penolakan organ transplant?',
        'Apakah Anda sedang hamil atau menyusui?',
        'Apakah Anda pernah menggunakan jarum suntik untuk menggunakan obat, steroid, atau apapun yang tidak diresepkan dokter?',
        'Apakah Anda pernah menggunakan jarum untuk tato atau tindik dalam 12 bulan terakhir?',
        'Apakah Anda pernah dites positif untuk virus HIV/AIDS?',
        'Apakah Anda pernah menerima transfusi darah?',
        'Apakah Anda pernah mengidap hepatitis?',
        'Apakah Anda pernah mengidap malaria?',
        'Apakah Anda pernah mengidap kanker?',
        'Apakah Anda pernah mengidap penyakit jantung?',
        'Apakah Anda pernah mengidap diabetes?',
        'Apakah Anda pernah mengidap epilepsi atau kejang?',
        'Apakah Anda pernah mengidap TBC (tuberculosis)?',
        'Apakah Anda sedang dalam pengobatan atau perawatan dokter?'
    ];

    // Questions that disqualify on "NO" answer
    $disqualifyOnNo = [
        'Apakah Anda sehat pada hari ini?'
    ];

    if (in_array($question->question, $disqualifyOnYes) && $normalizedAnswer === 'yes') {
        \Log::info("DISQUALIFIED: YES answer on: {$question->question}");
        return true;
    }

    if (in_array($question->question, $disqualifyOnNo) && $normalizedAnswer === 'no') {
        \Log::info("DISQUALIFIED: NO answer on: {$question->question}");
        return true;
    }

    return false;
}

private function performAdditionalChecks($donor, $answers)
{
    $eligible = true;
    $reasons = [];

    // Check recent donation history
    $recentDonation = Donor::where('user_id', $donor->user_id)
        ->where('id', '!=', $donor->id)
        ->where('status', 'completed')
        ->where('donation_date', '>=', Carbon::now()->subWeeks(8))
        ->exists();

    if ($recentDonation) {
        $eligible = false;
        $reasons[] = 'Anda telah mendonor dalam 8 minggu terakhir';
    }

    // Check user profile for additional requirements
    $profile = Profile::where('user_id', $donor->user_id)->first();
    if ($profile) {
        // Age check
        if ($profile->date_of_birth) {
            $age = Carbon::parse($profile->date_of_birth)->age;
            if ($age < 17 || $age > 65) {
                $eligible = false;
                $reasons[] = 'Usia tidak memenuhi syarat (17-65 tahun)';
            }
        }

        // Weight check
        if ($profile->weight && $profile->weight < 45) {
            $eligible = false;
            $reasons[] = 'Berat badan kurang dari 45kg';
        }

        // Blood pressure check
        if ($profile->blood_pressure) {
            $bp = explode('/', $profile->blood_pressure);
            if (count($bp) == 2) {
                $systolic = (int)$bp[0];
                $diastolic = (int)$bp[1];
                
                if ($systolic > 180 || $systolic < 90 || $diastolic > 100 || $diastolic < 50) {
                    $eligible = false;
                    $reasons[] = 'Tekanan darah tidak normal';
                }
            }
        }
    }

    return [
        'eligible' => $eligible,
        'reasons' => $reasons
    ];
}

} // End of DonorController class

