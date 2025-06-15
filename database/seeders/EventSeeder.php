<?php
// database/seeders/EventSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); // Indonesian locale
        
        // Data template untuk variasi donor darah dan PMI
        $eventTypes = [
            'Donor Darah', 'Donor Darah Sukarela', 'Aksi Donor Darah',
            'Donor Darah Massal', 'Donor Darah Rutin', 'Bakti Sosial Donor Darah',
            'Kampanye Donor Darah', 'Sosialisasi Donor Darah', 'Edukasi Donor Darah',
            'Pelatihan PMI', 'Workshop Pertolongan Pertama', 'Kursus P3K',
            'Pelatihan Siaga Bencana', 'Sosialisasi Kepalangmerahan'
        ];
        
        $topics = [
            'Donor Darah', 'Kepalangmerahan', 'Pertolongan Pertama',
            'Siaga Bencana', 'Kesehatan Masyarakat', 'Kemanusiaan',
            'Relawan PMI', 'P3K Dasar', 'Manajemen Bencana',
            'Kesadaran Donor Darah', 'Edukasi Kesehatan'
        ];
        
        $locations = [
            'Aula Balai Desa Kedungbanteng',
            'Lapangan Desa Kedungbanteng',
            'Balai RW 01 Kedungbanteng',
            'Balai RW 02 Kedungbanteng',
            'Balai RW 03 Kedungbanteng',
            'Masjid Al-Ikhlas Kedungbanteng',
            'Masjid Baitul Muttaqin',
            'SDN Kedungbanteng 1',
            'SDN Kedungbanteng 2',
            'SMP Negeri 1 Kedungbanteng',
            'Puskesmas Kedungbanteng',
            'Kantor Desa Kedungbanteng',
            'Gedung Serbaguna Desa',
            'Mobile Unit PMI Kedungbanteng',
            'Posko PMI Desa',
            'Balai Kesehatan Desa'
        ];
        
        $organizers = [
            'PMI Cabang Kedungbanteng',
            'PMI Unit Desa Kedungbanteng',
            'Relawan PMI Kedungbanteng',
            'Karang Taruna & PMI',
            'PKK Desa & PMI',
            'Puskesmas & PMI',
            'BPD & PMI Kedungbanteng',
            'Tim Kesehatan Desa',
            'Komunitas Donor Darah Kedungbanteng',
            'Palang Merah Remaja (PMR)',
            'Satgas Kesehatan Desa',
            'Forum Kesehatan Desa'
        ];

        // Generate 100 events
        for ($i = 1; $i <= 100; $i++) {
            $eventType = $faker->randomElement($eventTypes);
            $topic = $faker->randomElement($topics);
            $organizer = $faker->randomElement($organizers);
            
            // Random date dalam 6 bulan ke depan
            $eventDate = $faker->dateTimeBetween('now', '+6 months');
            
            // Generate valid times
            $startTime = $this->generateValidTime();
            $endTime = $this->generateValidTime($startTime);
            
            // Status random tapi lebih banyak approved
            $statusOptions = ['approved', 'approved', 'approved', 'pending', 'rejected'];
            $status = $faker->randomElement($statusOptions);
            
            // Type random tapi lebih banyak admin
            $typeOptions = ['admin', 'admin', 'admin', 'user', 'user'];
            $type = $faker->randomElement($typeOptions);
            
            $title = $eventType . ' ' . date('Y', $eventDate->getTimestamp()) . ' - ' . $organizer;
            
            Event::create([
                'title' => $title,
                'description' => $this->generateDescription($eventType, $topic, $organizer),
                'content' => $this->generateContent($eventType, $topic, $organizer),
                'image' => $this->generateColorPlaceholder($eventType, $i), // Menggunakan color placeholder
                'location' => $faker->randomElement($locations),
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'max_participants' => $this->getMaxParticipants($eventType, $faker),
                'contact_person' => $this->getContactPerson($organizer, $faker),
                'contact_phone' => $faker->phoneNumber,
                'submitted_by' => $faker->name,
                'submitted_email' => $faker->email,
                'submitted_phone' => $faker->phoneNumber,
                'type' => $type,
                'status' => $status,
                'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
        }
        
        // Tambahan: Event khusus yang sudah lewat untuk testing
        for ($i = 1; $i <= 20; $i++) {
            $eventType = $faker->randomElement($eventTypes);
            $topic = $faker->randomElement($topics);
            $organizer = $faker->randomElement($organizers);
            
            // Event yang sudah lewat
            $eventDate = $faker->dateTimeBetween('-6 months', '-1 day');
            
            Event::create([
                'title' => $eventType . ' ' . date('Y', $eventDate->getTimestamp()) . ' (Selesai)',
                'description' => $this->generateDescription($eventType, $topic, $organizer),
                'content' => $this->generateContent($eventType, $topic, $organizer),
                'image' => $this->generateColorPlaceholder($eventType, $i + 100),
                'location' => $faker->randomElement($locations),
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => $this->generateValidTime(),
                'end_time' => $this->generateValidTime(),
                'max_participants' => $this->getMaxParticipants($eventType, $faker),
                'contact_person' => $this->getContactPerson($organizer, $faker),
                'contact_phone' => $faker->phoneNumber,
                'submitted_by' => $faker->name,
                'submitted_email' => $faker->email,
                'submitted_phone' => $faker->phoneNumber,
                'type' => 'admin',
                'status' => 'approved',
                'created_at' => $faker->dateTimeBetween('-8 months', '-6 months'),
                'updated_at' => $faker->dateTimeBetween('-6 months', '-1 month'),
            ]);
        }
    }
    
    private function generateValidTime($baseTime = null)
    {
        if ($baseTime) {
            // Jika ada base time, tambahkan 1-4 jam
            $baseParts = explode(':', $baseTime);
            $baseHour = (int)$baseParts[0];
            $baseMinute = (int)$baseParts[1];
            
            $newHour = min(23, $baseHour + rand(2, 5)); // Donor darah biasanya 2-5 jam
            $newMinute = rand(0, 59);
            
            return sprintf('%02d:%02d', $newHour, $newMinute);
        }
        
        // Generate random valid time (donor darah biasanya pagi-sore)
        $hour = rand(8, 16); // Jam 8 pagi sampai 4 sore
        $minute = rand(0, 59);
        
        return sprintf('%02d:%02d', $hour, $minute);
    }
    
    private function getMaxParticipants($eventType, $faker)
    {
        if (strpos($eventType, 'Donor Darah') !== false) {
            return $faker->numberBetween(50, 300); // Donor darah biasanya target lebih banyak
        } else {
            return $faker->numberBetween(20, 100); // Pelatihan/workshop lebih sedikit
        }
    }
    
    private function getContactPerson($organizer, $faker)
    {
        $pmiNames = [
            'dr. ' . $faker->name,
            'Ketua PMI ' . $faker->firstName,
            'Koordinator ' . $faker->firstName,
            'Relawan ' . $faker->firstName,
            $faker->name . ' (PMI)',
            'Penanggung Jawab ' . $faker->firstName
        ];
        
        return $faker->randomElement($pmiNames);
    }
    
    private function generateColorPlaceholder($eventType, $index)
    {
        // Warna-warna yang sesuai dengan tema PMI dan donor darah
        $colors = [
            // Warna merah (tema donor darah dan PMI)
            'dc2626', // red-600
            'ef4444', // red-500
            'f87171', // red-400
            'b91c1c', // red-700
            'dc143c', // crimson
            
            // Warna biru (tema kesehatan)
            '2563eb', // blue-600
            '3b82f6', // blue-500
            '60a5fa', // blue-400
            '1d4ed8', // blue-700
            '0ea5e9', // sky-500
            
            // Warna hijau (tema kesehatan dan kemanusiaan)
            '16a34a', // green-600
            '22c55e', // green-500
            '4ade80', // green-400
            '15803d', // green-700
            '059669', // emerald-600
            
            // Warna orange (tema semangat dan energi)
            'ea580c', // orange-600
            'f97316', // orange-500
            'fb923c', // orange-400
            'c2410c', // orange-700
            'f59e0b', // amber-500
            
            // Warna ungu (tema profesional)
            '9333ea', // purple-600
            'a855f7', // purple-500
            'c084fc', // purple-400
            '7c3aed', // purple-700
            '8b5cf6', // violet-500
        ];
        
        // Pilih warna berdasarkan jenis event
        if (strpos($eventType, 'Donor Darah') !== false) {
            // Untuk donor darah, prioritaskan warna merah
            $redColors = ['dc2626', 'ef4444', 'f87171', 'b91c1c', 'dc143c'];
            $selectedColor = $redColors[array_rand($redColors)];
        } else {
            // Untuk pelatihan PMI, gunakan warna lain
            $otherColors = ['2563eb', '3b82f6', '16a34a', '22c55e', 'ea580c', 'f97316', '9333ea', 'a855f7'];
            $selectedColor = $otherColors[array_rand($otherColors)];
        }
        
        // Buat teks untuk placeholder
        $placeholderText = $this->getPlaceholderText($eventType);
        
        // Generate URL placeholder dengan warna dan teks
        $width = 800;
        $height = 400;
        $encodedText = urlencode($placeholderText);
        
        return "https://via.placeholder.com/{$width}x{$height}/{$selectedColor}/ffffff?text={$encodedText}";
    }
    
    private function getPlaceholderText($eventType)
    {
        if (strpos($eventType, 'Donor Darah') !== false) {
            $texts = [
                'DONOR+DARAH',
                'SETETES+DARAH+SEJUTA+HARAPAN',
                'BERBAGI+DARAH+BERBAGI+KEHIDUPAN',
                'DONOR+DARAH+SUKARELA',
                'PMI+DONOR+DARAH',
                'AKSI+KEMANUSIAAN',
                'SAVE+LIVES+DONATE+BLOOD'
            ];
        } else {
            $texts = [
                'PELATIHAN+PMI',
                'PERTOLONGAN+PERTAMA',
                'SIAGA+BENCANA',
                'KEPALANGMERAHAN',
                'P3K+TRAINING',
                'PMI+WORKSHOP',
                'RELAWAN+SIAGA'
            ];
        }
        
        return $texts[array_rand($texts)];
    }
    
    private function generateDescription($eventType, $topic, $organizer)
    {
        $templates = [
            "Mari bergabung dalam {$eventType} yang diselenggarakan oleh {$organizer}. Setetes darah Anda dapat menyelamatkan nyawa sesama. Ayo berpartisipasi dalam aksi kemanusiaan ini!",
            
            "{$organizer} mengajak seluruh masyarakat Kedungbanteng untuk berpartisipasi dalam {$eventType}. Donor darah adalah bentuk kepedulian tertinggi kepada sesama yang membutuhkan.",
            
            "Jadilah pahlawan tanpa tanda jasa! {$eventType} ini merupakan wujud nyata kepedulian kita terhadap sesama. Satu kantong darah dapat menyelamatkan hingga 3 nyawa.",
            
            "Dalam rangka membantu ketersediaan stok darah di PMI, {$organizer} menyelenggarakan {$eventType} yang terbuka untuk seluruh masyarakat sehat.",
            
            "Berbagi darah, berbagi kehidupan. {$eventType} ini adalah kesempatan emas untuk berkontribusi nyata bagi kemanusiaan. Mari wujudkan solidaritas sosial melalui donor darah!"
        ];
        
        return $templates[array_rand($templates)];
    }
    
    private function generateContent($eventType, $topic, $organizer)
    {
        if (strpos($eventType, 'Donor Darah') !== false) {
            return $this->generateDonorDarahContent($eventType, $organizer);
        } else {
            return $this->generatePelatihanPMIContent($eventType, $topic, $organizer);
        }
    }
    
    private function generateDonorDarahContent($eventType, $organizer)
    {
        return "
<h3>Tentang Kegiatan Donor Darah</h3>
<p>{$eventType} ini merupakan kegiatan rutin {$organizer} dalam mendukung ketersediaan stok darah di PMI. Setiap tetes darah yang Anda sumbangkan dapat menyelamatkan nyawa sesama yang membutuhkan.</p>

<h3>Syarat Donor Darah</h3>
<ul>
<li>Usia 17-60 tahun (17 tahun diizinkan bila ada persetujuan tertulis dari orangtua)</li>
<li>Berat badan minimal 45 kg</li>
<li>Tekanan darah normal (sistole 100-170, diastole 70-100)</li>
<li>Denyut nadi teratur (50-100 kali/menit)</li>
<li>Hemoglobin normal (Pria: 12,5-17 g/dl, Wanita: 12-15,5 g/dl)</li>
<li>Tidak sedang hamil, menyusui, atau haid</li>
<li>Tidak sedang sakit atau dalam pengobatan</li>
<li>Tidak memiliki riwayat penyakit menular (HIV, Hepatitis, Sifilis)</li>
</ul>

<h3>Persiapan Sebelum Donor</h3>
<ul>
<li>Istirahat cukup (tidur minimal 5 jam)</li>
<li>Makan makanan bergizi 4 jam sebelum donor</li>
<li>Minum air putih yang cukup</li>
<li>Tidak mengonsumsi obat-obatan</li>
<li>Tidak mengonsumsi alkohol 24 jam sebelumnya</li>
<li>Membawa identitas diri (KTP/SIM/Paspor)</li>
</ul>

<h3>Fasilitas yang Disediakan</h3>
<ul>
<li>Pemeriksaan kesehatan gratis</li>
<li>Cek golongan darah gratis</li>
<li>Konsumsi dan snack</li>
<li>Sertifikat donor darah</li>
<li>Kartu donor darah</li>
<li>Doorprize menarik</li>
</ul>

<h3>Manfaat Donor Darah</h3>
<ul>
<li>Membantu menyelamatkan nyawa sesama</li>
<li>Mendapat pemeriksaan kesehatan gratis</li>
<li>Membantu menurunkan risiko penyakit jantung</li>
<li>Membantu membakar kalori</li>
<li>Menstimulasi produksi sel darah merah baru</li>
<li>Mendapat pahala dan kepuasan batin</li>
</ul>

<p><strong>Catatan Penting:</strong> Donor darah aman dan tidak berbahaya. Semua alat yang digunakan steril dan sekali pakai. Mari bergabung dalam aksi kemanusiaan ini!</p>
        ";
    }
    
    private function generatePelatihanPMIContent($eventType, $topic, $organizer)
    {
        return "
<h3>Tentang Pelatihan</h3>
<p>{$eventType} ini diselenggarakan oleh {$organizer} untuk meningkatkan kemampuan masyarakat dalam bidang {$topic} dan kesiapsiagaan menghadapi situasi darurat.</p>

<h3>Tujuan Pelatihan</h3>
<ul>
<li>Memberikan pengetahuan dasar tentang {$topic}</li>
<li>Melatih keterampilan pertolongan pertama</li>
<li>Meningkatkan kesadaran akan pentingnya kesiapsiagaan</li>
<li>Membentuk relawan siaga di tingkat desa</li>
<li>Membangun jejaring kepalangmerahan</li>
</ul>

<h3>Materi yang Akan Dipelajari</h3>
<ul>
<li>Prinsip-prinsip dasar PMI dan Gerakan Palang Merah</li>
<li>Teknik pertolongan pertama pada kecelakaan</li>
<li>Penanganan luka dan perdarahan</li>
<li>Resusitasi jantung paru (RJP)</li>
<li>Penanganan patah tulang dan terkilir</li>
<li>Manajemen bencana dan evakuasi</li>
<li>Komunikasi dalam situasi darurat</li>
</ul>

<h3>Fasilitas Pelatihan</h3>
<ul>
<li>Modul pelatihan lengkap</li>
<li>Praktik langsung dengan alat peraga</li>
<li>Sertifikat kelulusan dari PMI</li>
<li>Kartu anggota relawan (bagi yang berminat)</li>
<li>Konsumsi selama pelatihan</li>
<li>Kit P3K untuk peserta terbaik</li>
</ul>

<h3>Persyaratan Peserta</h3>
<ul>
<li>Usia minimal 16 tahun</li>
<li>Sehat jasmani dan rohani</li>
<li>Memiliki motivasi tinggi untuk membantu sesama</li>
<li>Bersedia mengikuti pelatihan hingga selesai</li>
<li>Membawa alat tulis dan buku catatan</li>
</ul>

<h3>Setelah Pelatihan</h3>
<ul>
<li>Peserta dapat bergabung menjadi relawan PMI</li>
<li>Mendapat undangan untuk pelatihan lanjutan</li>
<li>Dapat membantu dalam kegiatan kemanusiaan</li>
<li>Menjadi bagian dari jaringan siaga bencana desa</li>
</ul>

<p><strong>Mari Bergabung!</strong> Jadilah bagian dari gerakan kemanusiaan dan siap membantu sesama dalam situasi darurat. Ilmu yang didapat akan sangat bermanfaat untuk diri sendiri, keluarga, dan masyarakat.</p>
        ";
    }
}
