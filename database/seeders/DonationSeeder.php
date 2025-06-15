<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class DonationSeeder extends Seeder
{
  public function run()
  {
      echo "🚀 Memulai DonationSeeder untuk tabel donors...\n";
      
      // 1. Buat Stored Procedures
      $this->createStoredProcedures();
      
      // 2. Buat Triggers (diperbaiki)
      $this->createTriggers();
      
      // 3. Jalankan seeding dengan transaction
      $this->seedDonorsWithTransaction();
  }

  /**
   * Membuat Stored Procedures
   */
  private function createStoredProcedures()
  {
      echo "📝 Membuat Stored Procedures...\n";

      // Drop procedures jika sudah ada
      DB::unprepared('DROP PROCEDURE IF EXISTS ValidateDonorEligibility');
      DB::unprepared('DROP PROCEDURE IF EXISTS CalculateNextEligibleDate');
      DB::unprepared('DROP PROCEDURE IF EXISTS UpdateDonorStatus');
      DB::unprepared('DROP PROCEDURE IF EXISTS GetDonorStatistics');

      // Procedure 1: Validasi kelayakan donor (diperbaiki typo)
      DB::unprepared('
          CREATE PROCEDURE ValidateDonorEligibility(
              IN p_user_id INT,
              IN p_health_questions JSON,
              OUT p_is_eligible BOOLEAN,
              OUT p_rejection_reason TEXT
          )
          BEGIN
              DECLARE last_donation_date DATE;
              DECLARE days_since_last INT DEFAULT 0;
              DECLARE donor_count INT DEFAULT 0;
              
              -- Cek apakah user sudah pernah donor
              SELECT COUNT(*), MAX(donation_date) 
              INTO donor_count, last_donation_date
              FROM donors 
              WHERE user_id = p_user_id AND status = "completed";
              
              -- Hitung hari sejak donasi terakhir
              IF last_donation_date IS NOT NULL THEN
                  SET days_since_last = DATEDIFF(CURDATE(), last_donation_date);
              END IF;
              
              -- Default eligible
              SET p_is_eligible = TRUE;
              SET p_rejection_reason = NULL;
              
              -- Validasi jarak donasi (minimal 56 hari)
              IF last_donation_date IS NOT NULL AND days_since_last < 56 THEN
                  SET p_is_eligible = FALSE;
                  SET p_rejection_reason = CONCAT("Harus menunggu ", 56 - days_since_last, " hari lagi sejak donasi terakhir");
                  
              -- Validasi kesehatan berdasarkan health_questions
              ELSEIF JSON_EXTRACT(p_health_questions, "$.has_fever") = true THEN
                  SET p_is_eligible = FALSE;
                  SET p_rejection_reason = "Sedang demam, tidak dapat mendonor";
                  
              ELSEIF JSON_EXTRACT(p_health_questions, "$.taking_medication") = true THEN
                  SET p_is_eligible = FALSE;
                  SET p_rejection_reason = "Sedang mengonsumsi obat-obatan";
                  
              ELSEIF JSON_EXTRACT(p_health_questions, "$.recent_surgery") = true THEN
                  SET p_is_eligible = FALSE;
                  SET p_rejection_reason = "Baru menjalani operasi dalam 6 bulan terakhir";
                  
              ELSEIF JSON_EXTRACT(p_health_questions, "$.weight") < 45 THEN
                  SET p_is_eligible = FALSE;
                  SET p_rejection_reason = "Berat badan kurang dari 45 kg";
              END IF;
          END
      ');

      // Procedure 2: Hitung tanggal eligible berikutnya
      DB::unprepared('
          CREATE PROCEDURE CalculateNextEligibleDate(
              IN p_user_id INT,
              IN p_donation_date DATE,
              OUT p_next_eligible_date DATE
          )
          BEGIN
              DECLARE user_gender VARCHAR(20) DEFAULT "Laki-laki";
              DECLARE waiting_days INT DEFAULT 56;
              
              -- Ambil gender dari profiles jika ada
              SELECT gender INTO user_gender
              FROM profiles 
              WHERE user_id = p_user_id
              LIMIT 1;
              
              -- Tentukan periode tunggu berdasarkan gender
              IF user_gender = "Perempuan" THEN
                  SET waiting_days = 84; -- 12 minggu untuk wanita
              ELSE
                  SET waiting_days = 56; -- 8 minggu untuk pria
              END IF;
              
              -- Hitung tanggal eligible berikutnya
              SET p_next_eligible_date = DATE_ADD(p_donation_date, INTERVAL waiting_days DAY);
          END
      ');

      // Procedure 3: Update status donor
      DB::unprepared('
          CREATE PROCEDURE UpdateDonorStatus(
              IN p_donor_id INT,
              IN p_new_status VARCHAR(20),
              IN p_notes TEXT
          )
          BEGIN
              DECLARE old_status VARCHAR(20);
              
              -- Ambil status lama
              SELECT status INTO old_status
              FROM donors 
              WHERE id = p_donor_id;
              
              -- Update status dan timestamps
              UPDATE donors 
              SET 
                  status = p_new_status,
                  notes = COALESCE(p_notes, notes),
                  updated_at = NOW(),
                  approved_at = CASE 
                      WHEN p_new_status = "approved" THEN NOW() 
                      ELSE approved_at 
                  END,
                  completed_at = CASE 
                      WHEN p_new_status = "completed" THEN NOW() 
                      ELSE completed_at 
                  END
              WHERE id = p_donor_id;
          END
      ');

      // Procedure 4: Statistik donor
      DB::unprepared('
          CREATE PROCEDURE GetDonorStatistics(
              IN p_user_id INT,
              OUT p_total_donations INT,
              OUT p_successful_donations INT,
              OUT p_last_donation_date DATE,
              OUT p_donor_level VARCHAR(50)
          )
          BEGIN
              -- Hitung total donasi
              SELECT COUNT(*) INTO p_total_donations
              FROM donors 
              WHERE user_id = p_user_id;
              
              -- Hitung donasi yang berhasil
              SELECT COUNT(*), MAX(donation_date) 
              INTO p_successful_donations, p_last_donation_date
              FROM donors 
              WHERE user_id = p_user_id AND status = "completed";
              
              -- Tentukan level donor
              CASE
                  WHEN p_successful_donations >= 50 THEN SET p_donor_level = "Diamond Donor";
                  WHEN p_successful_donations >= 25 THEN SET p_donor_level = "Platinum Donor";
                  WHEN p_successful_donations >= 15 THEN SET p_donor_level = "Gold Donor";
                  WHEN p_successful_donations >= 8 THEN SET p_donor_level = "Silver Donor";
                  WHEN p_successful_donations >= 3 THEN SET p_donor_level = "Bronze Donor";
                  WHEN p_successful_donations >= 1 THEN SET p_donor_level = "Regular Donor";
                  ELSE SET p_donor_level = "New Donor";
              END CASE;
          END
      ');

      echo "✅ Stored Procedures berhasil dibuat\n";
  }

  /**
   * Membuat Triggers (diperbaiki untuk menghindari error 1442)
   */
  private function createTriggers()
  {
      echo "🔧 Membuat Triggers...\n";

      // Drop triggers jika sudah ada
      DB::unprepared('DROP TRIGGER IF EXISTS donors_after_insert');
      DB::unprepared('DROP TRIGGER IF EXISTS donors_after_update');
      DB::unprepared('DROP TRIGGER IF EXISTS donors_before_update');

      // Buat tabel log jika belum ada
      $this->createLogTables();

      // Trigger 1: Setelah insert donor baru (tanpa update next_eligible_date)
      DB::unprepared('
          CREATE TRIGGER donors_after_insert
          AFTER INSERT ON donors
          FOR EACH ROW
          BEGIN
              -- Log aktivitas saja, tidak update tabel donors
              INSERT INTO donor_activity_logs (
                  donor_id, user_id, activity_type, description, created_at
              ) VALUES (
                  NEW.id, NEW.user_id, "REGISTRATION", 
                  CONCAT("Donor baru terdaftar dengan kode: ", NEW.donor_code), 
                  NOW()
              );
              
              -- Update statistik user
              INSERT INTO user_donor_stats (user_id, total_registrations, last_registration_date, created_at, updated_at)
              VALUES (NEW.user_id, 1, NEW.donation_date, NOW(), NOW())
              ON DUPLICATE KEY UPDATE
                  total_registrations = total_registrations + 1,
                  last_registration_date = NEW.donation_date,
                  updated_at = NOW();
          END
      ');

      // Trigger 2: Sebelum update (validasi dan auto-generate)
      DB::unprepared('
          CREATE TRIGGER donors_before_update
          BEFORE UPDATE ON donors
          FOR EACH ROW
          BEGIN
              -- Validasi transisi status
              IF OLD.status = "completed" AND NEW.status NOT IN ("completed", "cancelled") THEN
                  SIGNAL SQLSTATE "45000" 
                  SET MESSAGE_TEXT = "Tidak dapat mengubah status dari completed ke status lain kecuali cancelled";
              END IF;
              
              -- Auto-generate donor_code jika kosong
              IF NEW.donor_code IS NULL OR NEW.donor_code = "" THEN
                  SET NEW.donor_code = CONCAT("DNR", YEAR(NEW.donation_date), LPAD(NEW.id, 6, "0"));
              END IF;
          END
      ');

      // Trigger 3: Setelah update
      DB::unprepared('
          CREATE TRIGGER donors_after_update
          AFTER UPDATE ON donors
          FOR EACH ROW
          BEGIN
              -- Log jika status berubah
              IF OLD.status != NEW.status THEN
                  INSERT INTO donor_activity_logs (
                      donor_id, user_id, activity_type, description, created_at
                  ) VALUES (
                      NEW.id, NEW.user_id, "STATUS_CHANGE",
                      CONCAT("Status berubah dari ", OLD.status, " ke ", NEW.status),
                      NOW()
                  );
                  
                  -- Update statistik jika status menjadi completed
                  IF NEW.status = "completed" THEN
                      UPDATE user_donor_stats 
                      SET 
                          successful_donations = successful_donations + 1,
                          last_successful_donation = NEW.donation_date,
                          updated_at = NOW()
                      WHERE user_id = NEW.user_id;
                  END IF;
              END IF;
          END
      ');

      echo "✅ Triggers berhasil dibuat\n";
  }

  /**
   * Buat tabel log yang diperlukan
   */
  private function createLogTables()
  {
      // Tabel log aktivitas donor
      DB::unprepared('
          CREATE TABLE IF NOT EXISTS donor_activity_logs (
              id INT AUTO_INCREMENT PRIMARY KEY,
              donor_id INT NOT NULL,
              user_id INT NOT NULL,
              activity_type VARCHAR(50),
              description TEXT,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              INDEX idx_donor_id (donor_id),
              INDEX idx_user_id (user_id),
              INDEX idx_activity_type (activity_type)
          )
      ');

      // Tabel statistik donor per user
      DB::unprepared('
          CREATE TABLE IF NOT EXISTS user_donor_stats (
              id INT AUTO_INCREMENT PRIMARY KEY,
              user_id INT UNIQUE NOT NULL,
              total_registrations INT DEFAULT 0,
              successful_donations INT DEFAULT 0,
              last_registration_date DATE,
              last_successful_donation DATE,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              INDEX idx_user_id (user_id)
          )
      ');
  }

  /**
   * Seeding data dengan Transaction (diperbaiki)
   */
  private function seedDonorsWithTransaction()
  {
      echo "💾 Memulai seeding data donors...\n";

      // Cek berapa banyak users yang tersedia
      $maxUserId = DB::table('users')->max('id');
      $userCount = DB::table('users')->count();
      
      echo "👥 Tersedia {$userCount} users (max ID: {$maxUserId})\n";

      $donorData = $this->prepareDonorData($maxUserId);
      $batchSize = 15;
      $batches = array_chunk($donorData, $batchSize);
      $insertedCount = 0;
      $skippedCount = 0;

      foreach ($batches as $batchIndex => $batch) {
          DB::beginTransaction();
          
          try {
              echo "🔄 Memproses batch " . ($batchIndex + 1) . " dari " . count($batches) . "\n";
              
              foreach ($batch as $donor) {
                  // Validasi donor menggunakan stored procedure
                  DB::select('
                      CALL ValidateDonorEligibility(?, ?, @is_eligible, @rejection_reason)
                  ', [$donor['user_id'], $donor['health_questions']]);
                  
                  $validation = DB::select('
                      SELECT @is_eligible as is_eligible, @rejection_reason as rejection_reason
                  ')[0];
                  
                  // Set status berdasarkan validasi
                  if (!$validation->is_eligible) {
                      $donor['is_eligible'] = false;
                      $donor['rejection_reason'] = $validation->rejection_reason;
                      $donor['status'] = 'rejected';
                      echo "⚠️  Donor user_id {$donor['user_id']} ditolak: {$validation->rejection_reason}\n";
                  } else {
                      $donor['is_eligible'] = true;
                      $donor['rejection_reason'] = null;
                  }

                  // Hitung next_eligible_date sebelum insert
                  DB::select('
                      CALL CalculateNextEligibleDate(?, ?, @next_date)
                  ', [$donor['user_id'], $donor['donation_date']]);
                  
                  $nextDate = DB::select('SELECT @next_date as next_date')[0];
                  $donor['next_eligible_date'] = $nextDate->next_date;

                  // Insert donor record
                  $donorId = DB::table('donors')->insertGetId($donor);
                  $insertedCount++;

                  echo "✅ Donor ID {$donorId} berhasil ditambahkan\n";
              }

              DB::commit();
              echo "✅ Batch " . ($batchIndex + 1) . " berhasil diproses\n\n";
              
          } catch (Exception $e) {
              DB::rollback();
              echo "❌ Error pada batch " . ($batchIndex + 1) . ": " . $e->getMessage() . "\n";
              echo "🔄 Rollback dilakukan untuk batch ini\n\n";
              continue;
          }
      }

      echo "🎉 Seeding donors selesai!\n";
      echo "📊 Inserted: {$insertedCount}, Skipped: {$skippedCount}\n";
      $this->showSummary();
  }

  /**
   * Persiapkan data donor dummy (diperbaiki)
   */
  private function prepareDonorData($maxUserId)
  {
      $donors = [];
      // Status yang sesuai dengan enum di database (pastikan tidak lebih dari panjang kolom)
      $statuses = ['pending', 'approved', 'completed', 'rejected'];
      $locations = range(1, 10); // Asumsi ada 10 lokasi
      $addresses = [
          'Jl. Merdeka No. 123, Jakarta Pusat',
          'Jl. Sudirman No. 456, Jakarta Selatan', 
          'Jl. Thamrin No. 789, Jakarta Pusat',
          'Jl. Gatot Subroto No. 321, Jakarta Selatan',
          'Jl. Kuningan No. 654, Jakarta Selatan',
          'Jl. Senayan No. 987, Jakarta Pusat',
          'Jl. Kemang No. 147, Jakarta Selatan',
          'Jl. Menteng No. 258, Jakarta Pusat',
          'Jl. Kelapa Gading No. 369, Jakarta Utara',
          'Jl. Pondok Indah No. 741, Jakarta Selatan'
      ];

      // Generate 100 donor records (dikurangi untuk menghindari foreign key error)
      for ($i = 1; $i <= 100; $i++) {
          $donationDate = Carbon::now()->subDays(rand(1, 365))->format('Y-m-d'); // 1 tahun terakhir
          $status = $statuses[array_rand($statuses)];
          
          // Generate health questions JSON
          $healthQuestions = json_encode([
              'has_fever' => rand(0, 10) < 1, // 10% kemungkinan demam
              'taking_medication' => rand(0, 10) < 2, // 20% kemungkinan minum obat
              'recent_surgery' => rand(0, 10) < 1, // 10% kemungkinan operasi
              'weight' => rand(45, 90), // Berat badan 45-90 kg
              'blood_pressure' => rand(110, 140) . '/' . rand(70, 90),
              'hemoglobin' => round(rand(120, 160) / 10, 1) // 12.0-16.0 g/dL
          ]);

          $donor = [
              'user_id' => rand(1, min($maxUserId, 20)), // Gunakan user_id yang ada
              'donor_code' => 'DNR' . date('Y') . str_pad($i, 6, '0', STR_PAD_LEFT),
              'health_questions' => $healthQuestions,
              'is_eligible' => true, // Akan divalidasi oleh stored procedure
              'rejection_reason' => null,
              'status' => $status,
              'donation_date' => $donationDate,
              'next_eligible_date' => null, // Akan dihitung sebelum insert
              'notes' => $this->generateNotes($status),
              'approved_at' => $status === 'approved' || $status === 'completed' ? 
                  Carbon::parse($donationDate)->addHours(rand(1, 4)) : null,
              'completed_at' => $status === 'completed' ? 
                  Carbon::parse($donationDate)->addHours(rand(5, 8)) : null,
              'alamat' => $addresses[array_rand($addresses)],
              'lokasi_id' => $locations[array_rand($locations)],
              'created_at' => Carbon::parse($donationDate)->subHours(rand(1, 24)),
              'updated_at' => Carbon::parse($donationDate)->addHours(rand(1, 48))
          ];

          $donors[] = $donor;
      }

      return $donors;
  }

  /**
   * Generate catatan berdasarkan status
   */
  private function generateNotes($status)
  {
      $notesByStatus = [
          'pending' => [
              'Menunggu verifikasi dokumen',
              'Pendaftaran baru, belum diperiksa',
              'Dalam antrian pemeriksaan'
          ],
          'approved' => [
              'Donor memenuhi syarat kesehatan',
              'Pemeriksaan fisik normal',
              'Siap untuk proses donasi'
          ],
          'completed' => [
              'Donasi berhasil dilakukan',
              'Donor dalam kondisi baik setelah donasi',
              'Proses donasi berjalan lancar',
              'Donor kooperatif, tidak ada komplikasi'
          ],
          'rejected' => [
              'Tidak memenuhi syarat kesehatan',
              'Berat badan kurang dari standar',
              'Sedang dalam pengobatan'
          ]
      ];

      $notes = $notesByStatus[$status] ?? ['Catatan umum'];
      return $notes[array_rand($notes)];
  }

  /**
   * Tampilkan ringkasan hasil seeding
   */
  private function showSummary()
  {
      $totalDonors = DB::table('donors')->count();
      $byStatus = DB::table('donors')
          ->select('status', DB::raw('COUNT(*) as count'))
          ->groupBy('status')
          ->get();
      
      $eligible = DB::table('donors')->where('is_eligible', true)->count();
      $rejected = DB::table('donors')->where('is_eligible', false)->count();

      echo "\n📊 RINGKASAN SEEDING DONORS:\n";
      echo "===============================\n";
      echo "Total Donors: {$totalDonors}\n";
      echo "Eligible: {$eligible}\n";
      echo "Rejected: {$rejected}\n";
      echo "\nStatus Distribution:\n";
      
      foreach ($byStatus as $status) {
          echo "- {$status->status}: {$status->count}\n";
      }
      
      echo "===============================\n";

      // Test stored procedure
      echo "\n🧪 TEST STORED PROCEDURE:\n";
      echo "========================\n";
      
      try {
          DB::select('CALL GetDonorStatistics(1, @total, @successful, @last_date, @level)');
          $stats = DB::select('SELECT @total as total, @successful as successful, @last_date as last_date, @level as level')[0];
          
          echo "User ID 1 Statistics:\n";
          echo "- Total Registrations: {$stats->total}\n";
          echo "- Successful Donations: {$stats->successful}\n";
          echo "- Last Donation: {$stats->last_date}\n";
          echo "- Donor Level: {$stats->level}\n";
      } catch (Exception $e) {
          echo "Error testing stored procedure: " . $e->getMessage() . "\n";
      }
      
      echo "========================\n";
  }
}