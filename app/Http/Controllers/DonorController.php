<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor;
use App\Models\DonorQuestion;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DonorController extends Controller
{
    // ==========================================
    // USER METHODS (Donor Registration)
    // ==========================================

    /**
     * Step 1: Index/Check Eligibility
     */
    public function index()
    {
        $user = auth()->user();
        
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.donors.index');
        }
        
        // Check if user has profile
        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            return redirect()->route('profile.form')
                ->with('error', 'Anda harus melengkapi profile terlebih dahulu sebelum dapat mendonor.');
        }

        // Check if can donate again (minimum 2 weeks)
        $canDonate = Donor::canDonateAgain($user->id);
        $nextEligibleDate = Donor::getNextEligibleDate($user->id);

        // Get last donation
        $lastDonation = Donor::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest('donation_date')
            ->first();

        return view('user.donor.index', compact('profile', 'canDonate', 'nextEligibleDate', 'lastDonation'));
    }

    /**
     * Step 2: Start Donation Process
     */
    public function start()
    {
        $user = auth()->user();
        
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.donors.index');
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
            session(['donor_id' => $existingDonor->id]);
            return redirect()->route('donor.questions', 1)
                ->with('info', 'Melanjutkan sesi donor yang belum selesai.');
        }

        // Create new donor record
        $donorCode = $this->generateDonorCode();
        
        $donor = Donor::create([
            'user_id' => $user->id,
            'donor_code' => $donorCode,
            'health_questions' => [],
            'status' => 'pending'
        ]);

        session(['donor_id' => $donor->id]);

        return redirect()->route('donor.questions', 1);
    }

    /**
     * Step 3-5: Health Questions (3 steps)
     */
    public function questions($step)
    {
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.donors.index');
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

    /**
     * Save Question Answers
     */
    public function saveQuestions(Request $request, $step)
    {
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.donors.index');
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
            
            // PERBAIKAN: Jika TIDAK LAYAK, langsung ke success tanpa consent
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

    /**
     * Step 6: Consent (hanya untuk yang layak donor)
     */
    public function consent()
    {
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.donors.index');
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

        // PERBAIKAN: Double check - Jika tidak layak, redirect ke success
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

    /**
     * Save Consent & Complete (hanya untuk yang layak)
     */
    public function saveConsent(Request $request)
    {
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.donors.index');
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

        // PERBAIKAN: Final check - pastikan masih layak
        if (!$donor->is_eligible) {
            session()->forget('donor_id');
            return redirect()->route('donor.success', $donor->id)
                ->with('error', 'Tidak dapat menyimpan consent karena tidak memenuhi syarat donor.');
        }

        // Update status untuk yang layak donor
        $donor->update([
            'status' => 'approved',
            'donation_date' => Carbon::now(),
            'next_eligible_date' => Carbon::now()->addWeeks(2),
            'notes' => 'Donor disetujui berdasarkan hasil kuesioner kesehatan dan informed consent.'
        ]);

        // Clear session
        session()->forget('donor_id');

        return redirect()->route('donor.success', $donor->id)
            ->with('success', 'Terima kasih! Pendaftaran donor Anda berhasil.');
    }

    /**
     * Step 7: Success (untuk semua hasil)
     */
    public function success($donorId)
    {
        $donor = Donor::with('user')->find($donorId);

        if (!$donor || $donor->user_id !== auth()->id()) {
            return redirect()->route('donor.index')
                ->with('error', 'Data donor tidak ditemukan.');
        }

        return view('user.donor.success', compact('donor'));
    }

    /**
     * Donor History
     */
    public function history()
    {
        // PERBAIKAN: Jika admin, redirect ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.donors.index');
        }

        $donors = Donor::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.donor.history', compact('donors'));
    }

    /**
     * Get donor detail (AJAX)
     */
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
            $donor->load(['user']);
            
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
                    'created_at' => $donor->created_at,
                    'approved_at' => $donor->approved_at,
                    'donation_date' => $donor->donation_date,
                    'next_eligible_date' => $donor->next_eligible_date,
                    'user' => [
                        'name' => $donor->user->name,
                        'email' => $donor->user->email,
                        'phone' => $donor->user->phone ?? '-'
                    ]
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
    return view('donor.detail', compact('donor'));
}

    /**
     * Generate certificate
     */
    public function certificate($donorId)
    {
        $donor = Donor::with('user')->find($donorId);

        if (!$donor || $donor->user_id !== auth()->id()) {
            return redirect()->route('donor.history')
                ->with('error', 'Data tidak ditemukan.');
        }

        // PERBAIKAN: Sertifikat hanya untuk status 'completed'
        if ($donor->status !== 'completed') {
            return redirect()->route('donor.history')
                ->with('error', 'Sertifikat hanya tersedia setelah proses donor selesai di PMI.');
        }

        return view('user.donor.certificate', compact('donor'));
    }

    /**
     * Cancel donor process
     */
  
    
     public function cancel()
    {
        $donorId = session('donor_id');
        if ($donorId) {
            $donor = Donor::find($donorId);
            if ($donor && $donor->user_id === auth()->id() && $donor->status === 'pending') {
                $donor->update([
                    'status' => 'cancelled',
                    'notes' => 'Proses donor dibatalkan oleh pengguna'
                ]);
            }
            session()->forget('donor_id');
        }

        return redirect()->route('donor.index')
            ->with('info', 'Proses donor telah dibatalkan.');
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    /**
     * Admin - View all donors
     */
    public function adminIndex()
    {
        // PERBAIKAN: Pastikan hanya admin yang bisa akses
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('donor.index');
        }

        try {
            $donors = Donor::with('user')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);
            
            $totalDonors = Donor::count();
            $pendingCount = Donor::where('status', 'pending')->count();
            $approvedCount = Donor::where('status', 'approved')->count();
            $completedCount = Donor::where('status', 'completed')->count();
            $rejectedCount = Donor::where('status', 'rejected')->count();
            
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

    /**
     * Admin - Show donor detail (AJAX)
     */
    public function adminShow($donorId)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        try {
            $donor = Donor::with('user')->findOrFail($donorId);
            
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

    /**
     * Admin - Update donor status
     */
public function adminUpdateStatus(Request $request, $donorId)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
    }

    $request->validate([
        'status' => 'required|in:pending,approved,rejected,cancelled,completed', // Tambahkan completed
        'rejection_reason' => 'required_if:status,rejected|string|max:500',
        'donation_date' => 'nullable|date', // Tambahkan validasi untuk donation_date
        'notes' => 'nullable|string|max:1000' // Tambahkan validasi untuk notes
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

    /**
     * Admin - Mark donor as completed
     */
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
            
            if ($donor->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'error' => 'Donor harus dalam status approved'
                ], 400);
            }
            
            $donor->status = 'completed';
            $donor->donation_date = $request->donation_date;
            $donor->notes = $request->notes;
            
            // Calculate next eligible date (3 months later)
            $donor->next_eligible_date = Carbon::parse($request->donation_date)->addMonths(3);
            
            $donor->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Donor berhasil ditandai selesai'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menandai donor selesai'
            ], 500);
        }
    }

    /**
     * Admin - Export donors data
     */
    public function adminExport()
    {
        // Check admin access
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.donors.index')
                ->with('error', 'Akses ditolak');
        }

        try {
            $donors = Donor::with('user')->get();
            
            // Check if there are donors
            if ($donors->isEmpty()) {
                return redirect()->route('admin.donors.index')
                    ->with('error', 'Tidak ada data donor untuk diekspor');
            }
            
            $filename = 'donor_data_' . date('Y-m-d_H-i-s') . '.csv';
            $filePath = storage_path('app/public/exports/' . $filename);
            
            // Create directory if not exists
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
            
            $file = fopen($filePath, 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, [
                'Kode Donor',
                'Nama',
                'Email',
                'Status',
                'Layak',
                'Tanggal Daftar',
                'Tanggal Donor',
                'Donor Berikutnya',
                'Catatan',
                'Alasan Penolakan'
            ]);
            
            // Data
            foreach ($donors as $donor) {
                fputcsv($file, [
                    $donor->donor_code ?? '',
                    $donor->user->name ?? '',
                    $donor->user->email ?? '',
                    $donor->status ?? '',
                    $donor->is_eligible ? 'Ya' : 'Tidak',
                    $donor->created_at ? $donor->created_at->format('d/m/Y H:i') : '',
                    $donor->donation_date ? $donor->donation_date->format('d/m/Y') : '',
                    $donor->next_eligible_date ? $donor->next_eligible_date->format('d/m/Y') : '',
                    $donor->notes ?? '',
                    $donor->rejection_reason ?? ''
                ]);
            }
            
            fclose($file);
            
            // Download file
            return response()->download($filePath)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return redirect()->route('admin.donors.index')
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Generate unique donor code
     */
    private function generateDonorCode()
    {
        do {
            $code = 'DN' . date('Ymd') . strtoupper(Str::random(4));
        } while (Donor::where('donor_code', $code)->exists());
        
        return $code;
    }

    /**
     * Evaluate Eligibility based on answers - PERBAIKAN UTAMA
     */
    private function evaluateEligibility($donor)
    {
        $answers = $donor->health_questions;
        
        // DEBUG: Log semua jawaban untuk troubleshooting
        \Log::info('=== DEBUGGING DONOR ELIGIBILITY ===');
        \Log::info('Donor ID: ' . $donor->id);
        \Log::info('All Answers:', $answers);
        
        $disqualifyingQuestions = DonorQuestion::where('is_disqualifying', true)->get();
        
        $isEligible = true;
        $rejectionReasons = [];

        foreach ($disqualifyingQuestions as $question) {
            $questionKey = "question_" . $question->id;
            
            if (isset($answers[$questionKey])) {
                $answer = $answers[$questionKey];
                
                // DEBUG: Log setiap pertanyaan dan jawaban
                \Log::info("Question: {$question->question}");
                \Log::info("Answer Key: {$questionKey}");
                \Log::info("Answer Value: {$answer}");
                
                // Check if this answer should disqualify
                if ($this->shouldDisqualify($question, $answer)) {
                    $isEligible = false;
                    $rejectionReasons[] = $question->question;
                    \Log::info("DISQUALIFIED by: {$question->question}");
                }
            }
        }

        // Additional checks
        $additionalChecks = $this->performAdditionalChecks($donor, $answers);
        \Log::info('Additional Checks Result:', $additionalChecks);
        
        if (!$additionalChecks['eligible']) {
            $isEligible = false;
            $rejectionReasons = array_merge($rejectionReasons, $additionalChecks['reasons']);
        }

        // PERBAIKAN: Jika hanya ada 1 jawaban dan itu adalah "ya" untuk kesehatan, paksa eligible
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

        $donor->update([
            'is_eligible' => $isEligible,
            'rejection_reason' => $isEligible ? null : implode('; ', $rejectionReasons)
        ]);

        return $isEligible;
    }

    /**
     * Normalize answer untuk handle berbagai format
     */
    private function normalizeAnswer($answer)
    {
        $normalizedAnswer = strtolower(trim($answer));
        
        // Convert Indonesian answers to English
        $answerMap = [
            'ya' => 'yes',
            'tidak' => 'no',
            'yes' => 'yes',
            'no' => 'no'
        ];
        
        return $answerMap[$normalizedAnswer] ?? $normalizedAnswer;
    }

    /**
     * Method to determine if answer should disqualify - DIPERBAIKI
     */
    private function shouldDisqualify($question, $answer)
    {
        // Normalize answer
        $normalizedAnswer = $this->normalizeAnswer($answer);
        
        // DEBUG: Log normalization
        \Log::info("Original answer: {$answer}, Normalized: {$normalizedAnswer}");

        // Questions that disqualify if answered "YES"
        $disqualifyOnYes = [
            'Apakah Anda sedang minum antibiotik?',
            'Apakah Anda sedang minum obat lain untuk infeksi?',
            'Apakah Anda sedang minum aspirin atau obat yang mengandung aspirin?',
            'Apakah Anda mengalami sakit kepala dan demam secara bersamaan?',
            'Untuk donor wanita: apakah saat ini sedang hamil?',
            'Apakah Anda pernah mendonorkan darah?',
            'Apakah Anda menerima vaksinasi atau suntikan lainnya?',
            'Apakah Anda pernah kontak dengan orang yang menerima vaksin smallpox?',
            'Apakah Anda pernah menyumbangkan 2 kantong sel darah melalui proses apheresis?',
            'Apakah Anda pernah menerima transfusi darah?',
            'Apakah Anda pernah mendapat transplantasi organ, jaringan atau sumsum tulang?',
            'Apakah Anda pernah cangkok tulang untuk kulit?',
            'Apakah Anda pernah tertusuk jarum medis?',
            'Apakah Anda pernah berhubungan seksual dengan orang penderita HIV/AIDS?',
            'Apakah Anda pernah berhubungan dengan pekerja seks komersial?',
            'Apakah Anda pernah berhubungan dengan pengguna narkoba jarum suntik?',
            'Apakah Anda pernah berhubungan dengan pengguna konsentrat factor pembeku?',
            'Untuk donor wanita: Apakah Anda pernah berhubungan dengan laki-laki biseksual?',
            'Apakah Anda pernah berhubungan seksual dengan penderita hepatitis?',
            'Apakah Anda tinggal dengan penderita hepatitis?',
            'Apakah Anda memiliki tato?',
            'Apakah Anda memiliki tindik telinga atau bagian tubuh lainnya?',
            'Apakah Anda sedang atau pernah mendapatkan pengobatan sifilis atau GO (kencing bernanah)?',
            'Apakah Anda pernah ditahan di penjara untuk waktu lebih dari 72 jam?',
            'Apakah Anda pernah menerima uang, obat atau pembayaran lainnya untuk seks?',
            'Untuk laki-laki: Apakah Anda pernah berhubungan seksual dengan laki-laki?',
            'Apakah Anda tinggal selama 5 tahun atau lebih di Eropa?',
            'Apakah Anda menerima transfusi darah di Inggris?',
            'Apakah Anda tinggal selama 3 bulan atau lebih di Inggris?',
            'Apakah Anda mendapat hasil positif untuk HIV/AIDS?',
            'Apakah Anda menggunakan jarum suntik untuk obat-obatan, steroid yang tidak diresepkan dokter?',
            'Apakah Anda menggunakan konsentrat faktor pembeku?',
            'Apakah Anda menderita hepatitis?',
            'Apakah Anda menderita malaria?',
            'Apakah Anda menderita kanker termasuk leukemia?',
            'Apakah Anda bermasalah dengan jantung dan paru-paru?',
            'Apakah Anda menderita pendarahan atau penyakit berhubungan dengan darah?',
            'Apakah Anda berhubungan seksual dengan orang yang tinggal di Afrika?',
            'Apakah Anda tinggal di Afrika?'
        ];

        // Questions that disqualify if answered "NO"
        $disqualifyOnNo = [
            'Apakah Anda sehat pada hari ini?'
        ];

        // Check disqualification based on "YES" answers
        if (in_array($question->question, $disqualifyOnYes) && $normalizedAnswer === 'yes') {
            \Log::info("DISQUALIFIED: YES answer on: {$question->question}");
            return true;
        }

        // Check disqualification based on "NO" answers
        if (in_array($question->question, $disqualifyOnNo) && $normalizedAnswer === 'no') {
            \Log::info("DISQUALIFIED: NO answer on: {$question->question}");
            return true;
        }

        return false;
    }

    /**
     * Additional checks for donor eligibility - DIPERBAIKI
     */
    private function performAdditionalChecks($donor, $answers)
    {
        $eligible = true;
        $reasons = [];

        // Check if user has donated recently (within 8 weeks)
        $recentDonation = Donor::where('user_id', $donor->user_id)
            ->where('id', '!=', $donor->id)
            ->where('status', 'completed')
            ->where('donation_date', '>=', Carbon::now()->subWeeks(8))
            ->exists();

        if ($recentDonation) {
            $eligible = false;
            $reasons[] = 'Anda telah mendonor dalam 8 minggu terakhir';
        }

        // Check profile requirements
        $profile = Profile::where('user_id', $donor->user_id)->first();
        if ($profile) {
            // Check age (17-65 years)
            if ($profile->date_of_birth) {
                $age = Carbon::parse($profile->date_of_birth)->age;
                if ($age < 17 || $age > 65) {
                    $eligible = false;
                    $reasons[] = 'Usia tidak memenuhi syarat (17-65 tahun)';
                }
            }

            // Check weight (minimum 45kg)
            if ($profile->weight && $profile->weight < 45) {
                $eligible = false;
                $reasons[] = 'Berat badan kurang dari 45kg';
            }

            // Check blood pressure if available
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
}
