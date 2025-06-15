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
      // 1. Buat Stored Procedure untuk validasi donor
      $this->createStoredProcedures();
      
      // 2. Buat Trigger untuk update stok darah otomatis
      $this->createTriggers();
      
      // 3. Jalankan seeding dengan transaction
      $this->seedDonationsWithTransaction();
  }

  /**
   * Membuat Stored Procedures
   */
  private function createStoredProcedures()
  {
      // Drop procedure jika sudah ada
      DB::unprepared('DROP PROCEDURE IF EXISTS ValidateDonorEligibility');
      DB::unprepared('DROP PROCEDURE IF EXISTS CalculateDonorStats');
      DB::unprepared('DROP PROCEDURE IF EXISTS UpdateBloodStock');

      // Procedure 1: Validasi kelayakan donor
      DB::unprepared('
          CREATE PROCEDURE ValidateDonorEligibility(
              IN donor_id INT,
              OUT is_eligible BOOLEAN,
              OUT reason_message VARCHAR(255)
          )
          BEGIN
              DECLARE last_donation_date DATE;
              DECLARE days_since_last INT DEFAULT 0;
              DECLARE donor_age INT;
              DECLARE donor_gender VARCHAR(20);
              
              -- Ambil data donor dari profiles
              SELECT 
                  TIMESTAMPDIFF(YEAR, birth_date, CURDATE()),
                  gender
              INTO donor_age, donor_gender
              FROM profiles 
              WHERE user_id = donor_id;
              
              -- Cek donasi terakhir
              SELECT MAX(donation_date) 
              INTO last_donation_date
              FROM donations 
              WHERE donor_id = donor_id AND status = "completed";
              
              IF last_donation_date IS NOT NULL THEN
                  SET days_since_last = DATEDIFF(CURDATE(), last_donation_date);
              END IF;
              
              -- Validasi kelayakan
              SET is_eligible = TRUE;
              SET reason_message = "Donor memenuhi syarat";
              
              -- Cek umur (17-65 tahun)
              IF donor_age < 17 OR donor_age > 65 THEN
                  SET is_eligible = FALSE;
                  SET reason_message = "Umur donor harus antara 17-65 tahun";
              -- Cek jarak donasi (minimal 56 hari untuk pria, 84 hari untuk wanita)
              ELSEIF donor_gender = "Laki-laki" AND days_since_last < 56 AND last_donation_date IS NOT NULL THEN
                  SET is_eligible = FALSE;
                  SET reason_message = CONCAT("Harus menunggu ", 56 - days_since_last, " hari lagi");
              ELSEIF donor_gender = "Perempuan" AND days_since_last < 84 AND last_donation_date IS NOT NULL THEN
                  SET is_eligible = FALSE;
                  SET reason_message = CONCAT("Harus menunggu ", 84 - days_since_last, " hari lagi");
              END IF;
          END
      ');

      // Procedure 2: Hitung statistik donor
      DB::unprepared('
          CREATE PROCEDURE CalculateDonorStats(
              IN donor_id INT,
              OUT total_donations INT,
              OUT total_volume INT,
              OUT last_donation_date DATE,
              OUT donor_level VARCHAR(50)
          )
          BEGIN
              -- Hitung total donasi
              SELECT 
                  COUNT(*),
                  COALESCE(SUM(volume_ml), 0),
                  MAX(donation_date)
              INTO total_donations, total_volume, last_donation_date
              FROM donations 
              WHERE donor_id = donor_id AND status = "completed";
              
              -- Tentukan level donor berdasarkan jumlah donasi
              CASE
                  WHEN total_donations >= 50 THEN SET donor_level = "Diamond Donor";
                  WHEN total_donations >= 25 THEN SET donor_level = "Gold Donor";
                  WHEN total_donations >= 10 THEN SET donor_level = "Silver Donor";
                  WHEN total_donations >= 5 THEN SET donor_level = "Bronze Donor";
                  WHEN total_donations >= 1 THEN SET donor_level = "Regular Donor";
                  ELSE SET donor_level = "New Donor";
              END CASE;
          END
      ');

      // Procedure 3: Update stok darah
      DB::unprepared('
          CREATE PROCEDURE UpdateBloodStock(
              IN blood_type VARCHAR(5),
              IN rhesus VARCHAR(10),
              IN volume_change INT,
              IN operation_type VARCHAR(10)
          )
          BEGIN
              DECLARE current_stock INT DEFAULT 0;
              
              -- Ambil stok saat ini
              SELECT stock_ml INTO current_stock
              FROM blood_stocks 
              WHERE blood_type = blood_type AND rhesus = rhesus;
              
              -- Update stok berdasarkan operasi
              IF operation_type = "ADD" THEN
                  UPDATE blood_stocks 
                  SET 
                      stock_ml = stock_ml + volume_change,
                      last_updated = NOW()
                  WHERE blood_type = blood_type AND rhesus = rhesus;
              ELSEIF operation_type = "SUBTRACT" THEN
                  UPDATE blood_stocks 
                  SET 
                      stock_ml = GREATEST(0, stock_ml - volume_change),
                      last_updated = NOW()
                  WHERE blood_type = blood_type AND rhesus = rhesus;
              END IF;
          END
      ');

      echo "✅ Stored Procedures berhasil dibuat\n";
  }

  /**
   * Membuat Triggers
   */
  private function createTriggers()
  {
      // Drop trigger jika sudah ada
      DB::unprepared('DROP TRIGGER IF EXISTS after_donation_insert');
      DB::unprepared('DROP TRIGGER IF EXISTS after_donation_update');
      DB::unprepared('DROP TRIGGER IF EXISTS donation_audit_log');

      // Trigger 1: Auto update stok darah setelah donasi berhasil
      DB::unprepared('
          CREATE TRIGGER after_donation_insert
          AFTER INSERT ON donations
          FOR EACH ROW
          BEGIN
              IF NEW.status = "completed" THEN
                  -- Ambil data golongan darah donor
                  SET @blood_type = (SELECT blood_type FROM profiles WHERE user_id = NEW.donor_id);
                  SET @rhesus = (SELECT rhesus FROM profiles WHERE user_id = NEW.donor_id);
                  
                  -- Update stok darah
                  CALL UpdateBloodStock(@blood_type, @rhesus, NEW.volume_ml, "ADD");
                  
                  -- Log aktivitas
                  INSERT INTO donation_logs (donation_id, action, description, created_at)
                  VALUES (NEW.id, "STOCK_UPDATED", 
                         CONCAT("Stok darah ", @blood_type, " ", @rhesus, " bertambah ", NEW.volume_ml, " ml"), 
                         NOW());
              END IF;
          END
      ');

      // Trigger 2: Update stok saat status donasi berubah
      DB::unprepared('
          CREATE TRIGGER after_donation_update
          AFTER UPDATE ON donations
          FOR EACH ROW
          BEGIN
              -- Jika status berubah dari pending ke completed
              IF OLD.status != "completed" AND NEW.status = "completed" THEN
                  SET @blood_type = (SELECT blood_type FROM profiles WHERE user_id = NEW.donor_id);
                  SET @rhesus = (SELECT rhesus FROM profiles WHERE user_id = NEW.donor_id);
                  
                  CALL UpdateBloodStock(@blood_type, @rhesus, NEW.volume_ml, "ADD");
                  
                  INSERT INTO donation_logs (donation_id, action, description, created_at)
                  VALUES (NEW.id, "STATUS_COMPLETED", 
                         CONCAT("Donasi completed, stok ", @blood_type, " ", @rhesus, " +", NEW.volume_ml, " ml"), 
                         NOW());
                         
              -- Jika status berubah dari completed ke cancelled
              ELSEIF OLD.status = "completed" AND NEW.status = "cancelled" THEN
                  SET @blood_type = (SELECT blood_type FROM profiles WHERE user_id = NEW.donor_id);
                  SET @rhesus = (SELECT rhesus FROM profiles WHERE user_id = NEW.donor_id);
                  
                  CALL UpdateBloodStock(@blood_type, @rhesus, OLD.volume_ml, "SUBTRACT");
                  
                  INSERT INTO donation_logs (donation_id, action, description, created_at)
                  VALUES (NEW.id, "STATUS_CANCELLED", 
                         CONCAT("Donasi dibatalkan, stok ", @blood_type, " ", @rhesus, " -", OLD.volume_ml, " ml"), 
                         NOW());
              END IF;
          END
      ');

      // Buat tabel log jika belum ada
      DB::unprepared('
          CREATE TABLE IF NOT EXISTS donation_logs (
              id INT AUTO_INCREMENT PRIMARY KEY,
              donation_id INT,
              action VARCHAR(50),
              description TEXT,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              INDEX idx_donation_id (donation_id),
              INDEX idx_action (action),
              INDEX idx_created_at (created_at)
          )
      ');

      echo "✅ Triggers berhasil dibuat\n";
  }

  /**
   * Seeding data donasi dengan Transaction
   */
  private function seedDonationsWithTransaction()
  {
      // Buat tabel donations jika belum ada
      $this->createDonationsTable();

      $donationData = $this->prepareDonationData();
      $batchSize = 10; // Process dalam batch untuk performa
      $batches = array_chunk($donationData, $batchSize);

      foreach ($batches as $batchIndex => $batch) {
          // Mulai Transaction
          DB::beginTransaction();
          
          try {
              echo "🔄 Memproses batch " . ($batchIndex + 1) . " dari " . count($batches) . "\n";
              
              foreach ($batch as $donation) {
                  // Validasi donor menggunakan stored procedure
                  $result = DB::select('
                      CALL ValidateDonorEligibility(?, @is_eligible, @reason_message);
                      SELECT @is_eligible as is_eligible, @reason_message as reason_message;
                  ', [$donation['donor_id']]);
                  
                  $validation = $result[1] ?? $result[0];
                  
                  if (!$validation->is_eligible) {
                      echo "⚠️  Donor ID {$donation['donor_id']} tidak memenuhi syarat: {$validation->reason_message}\n";
                      // Skip donor ini tapi lanjutkan yang lain
                      continue;
                  }

                  // Insert donation record
                  $donationId = DB::table('donations')->insertGetId([
                      'donor_id' => $donation['donor_id'],
                      'lokasi_id' => $donation['lokasi_id'],
                      'donation_date' => $donation['donation_date'],
                      'volume_ml' => $donation['volume_ml'],
                      'hemoglobin_level' => $donation['hemoglobin_level'],
                      'blood_pressure' => $donation['blood_pressure'],
                      'pulse_rate' => $donation['pulse_rate'],
                      'temperature' => $donation['temperature'],
                      'weight' => $donation['weight'],
                      'status' => $donation['status'],
                      'notes' => $donation['notes'],
                      'created_by' => $donation['created_by'],
                      'created_at' => $donation['created_at'],
                      'updated_at' => $donation['updated_at']
                  ]);

                  // Hitung statistik donor menggunakan stored procedure
                  DB::select('
                      CALL CalculateDonorStats(?, @total_donations, @total_volume, @last_donation, @donor_level)
                  ', [$donation['donor_id']]);
                  
                  $stats = DB::select('
                      SELECT @total_donations as total_donations, 
                             @total_volume as total_volume, 
                             @last_donation as last_donation,
                             @donor_level as donor_level
                  ')[0];

                  // Update donor statistics (jika tabel ada)
                  DB::table('donor_statistics')->updateOrInsert(
                      ['donor_id' => $donation['donor_id']],
                      [
                          'total_donations' => $stats->total_donations,
                          'total_volume_ml' => $stats->total_volume,
                          'last_donation_date' => $stats->last_donation,
                          'donor_level' => $stats->donor_level,
                          'updated_at' => now()
                      ]
                  );

                  echo "✅ Donasi ID {$donationId} berhasil ditambahkan\n";
              }

              // Commit transaction jika semua berhasil
              DB::commit();
              echo "✅ Batch " . ($batchIndex + 1) . " berhasil diproses\n\n";
              
          } catch (Exception $e) {
              // Rollback jika ada error
              DB::rollback();
              echo "❌ Error pada batch " . ($batchIndex + 1) . ": " . $e->getMessage() . "\n";
              echo "🔄 Rollback dilakukan untuk batch ini\n\n";
              
              // Lanjutkan ke batch berikutnya
              continue;
          }
      }

      echo "🎉 Seeding donasi selesai!\n";
      $this->showSummary();
  }

  /**
   * Buat tabel yang diperlukan
   */
  private function createDonationsTable()
  {
      DB::unprepared('
          CREATE TABLE IF NOT EXISTS donations (
              id INT AUTO_INCREMENT PRIMARY KEY,
              donor_id INT NOT NULL,
              lokasi_id INT NOT NULL,
              donation_date DATE NOT NULL,
              volume_ml INT DEFAULT 450,
              hemoglobin_level DECIMAL(3,1),
              blood_pressure VARCHAR(20),
              pulse_rate INT,
              temperature DECIMAL(3,1),
              weight DECIMAL(5,2),
              status ENUM("pending", "completed", "cancelled", "rejected") DEFAULT "pending",
              notes TEXT,
              created_by VARCHAR(100),
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              INDEX idx_donor_id (donor_id),
              INDEX idx_lokasi_id (lokasi_id),
              INDEX idx_donation_date (donation_date),
              INDEX idx_status (status)
          )
      ');

      DB::unprepared('
          CREATE TABLE IF NOT EXISTS donor_statistics (
              id INT AUTO_INCREMENT PRIMARY KEY,
              donor_id INT UNIQUE NOT NULL,
              total_donations INT DEFAULT 0,
              total_volume_ml INT DEFAULT 0,
              last_donation_date DATE,
              donor_level VARCHAR(50) DEFAULT "New Donor",
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              INDEX idx_donor_id (donor_id),
              INDEX idx_donor_level (donor_level)
          )
      ');
  }

  /**
   * Persiapkan data donasi dummy
   */
  private function prepareDonationData()
  {
      $donations = [];
      $statuses = ['completed', 'completed', 'completed', 'pending', 'cancelled'];
      $bloodPressures = ['120/80', '110/70', '130/85', '125/82', '115/75'];
      $createdBy = ['Dr. Ahmad', 'Dr. Sari', 'Perawat Budi', 'Dr. Lisa', 'Perawat Dian'];

      // Generate 100 donasi
      for ($i = 1; $i <= 100; $i++) {
          $donationDate = Carbon::now()->subDays(rand(1, 365))->format('Y-m-d');
          
          $donations[] = [
              'donor_id' => rand(1, 20), // Asumsi ada 20 donor
              'lokasi_id' => rand(1, 10), // Asumsi ada 10 lokasi
              'donation_date' => $donationDate,
              'volume_ml' => [350, 400, 450, 500][array_rand([350, 400, 450, 500])],
              'hemoglobin_level' => round(rand(120, 160) / 10, 1), // 12.0 - 16.0
              'blood_pressure' => $bloodPressures[array_rand($bloodPressures)],
              'pulse_rate' => rand(60, 100),
              'temperature' => round(rand(360, 375) / 10, 1), // 36.0 - 37.5
              'weight' => round(rand(450, 900) / 10, 1), // 45.0 - 90.0 kg
              'status' => $statuses[array_rand($statuses)],
              'notes' => $this->generateNotes(),
              'created_by' => $createdBy[array_rand($createdBy)],
              'created_at' => Carbon::parse($donationDate)->addHours(rand(1, 5)),
              'updated_at' => Carbon::parse($donationDate)->addHours(rand(6, 24))
          ];
      }

      return $donations;
  }

  /**
   * Generate catatan donasi
   */
  private function generateNotes()
  {
      $notes = [
          'Donor dalam kondisi sehat, tidak ada keluhan',
          'Pemeriksaan fisik normal, donor kooperatif',
          'Tekanan darah stabil, tidak ada riwayat penyakit',
          'Donor rutin, kondisi prima',
          'Pemeriksaan lengkap, hasil memuaskan',
          'Donor pertama kali, sedikit nervous tapi kooperatif',
          'Kondisi fisik baik, motivasi tinggi untuk membantu',
          null, null // Beberapa tanpa notes
      ];

      return $notes[array_rand($notes)];
  }

  /**
   * Tampilkan ringkasan hasil seeding
   */
  private function showSummary()
  {
      $totalDonations = DB::table('donations')->count();
      $completedDonations = DB::table('donations')->where('status', 'completed')->count();
      $totalVolume = DB::table('donations')->where('status', 'completed')->sum('volume_ml');
      
      echo "\n📊 RINGKASAN SEEDING DONASI:\n";
      echo "================================\n";
      echo "Total Donasi: {$totalDonations}\n";
      echo "Donasi Completed: {$completedDonations}\n";
      echo "Total Volume: " . number_format($totalVolume) . " ml\n";
      echo "================================\n";

      // Tampilkan contoh penggunaan stored procedure
      echo "\n🔍 CONTOH PENGGUNAAN STORED PROCEDURE:\n";
      echo "=====================================\n";
      
      $result = DB::select('CALL CalculateDonorStats(1, @total, @volume, @last, @level)');
      $stats = DB::select('SELECT @total as total, @volume as volume, @last as last_date, @level as level')[0];
      
      echo "Donor ID 1:\n";
      echo "- Total Donasi: {$stats->total}\n";
      echo "- Total Volume: {$stats->volume} ml\n";
      echo "- Donasi Terakhir: {$stats->last_date}\n";
      echo "- Level: {$stats->level}\n";
      echo "=====================================\n";
  }
}