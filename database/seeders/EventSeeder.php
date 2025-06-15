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
        
        // Data template untuk variasi
        $eventTypes = [
            'Seminar', 'Workshop', 'Pelatihan', 'Webinar', 'Konferensi', 
            'Diskusi', 'Talkshow', 'Kompetisi', 'Festival', 'Pameran',
            'Bazaar', 'Donor Darah', 'Bakti Sosial', 'Gotong Royong',
            'Olahraga', 'Turnamen', 'Lomba', 'Pertunjukan', 'Konser'
        ];
        
        $topics = [
            'Teknologi', 'Pendidikan', 'Kesehatan', 'Ekonomi', 'Sosial',
            'Budaya', 'Lingkungan', 'Kewirausahaan', 'Digital Marketing',
            'Fotografi', 'Desain Grafis', 'Musik', 'Seni', 'Olahraga',
            'Kuliner', 'Pariwisata', 'Agama', 'Hukum', 'Politik'
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
            'Pos Kamling RT 01',
            'Pos Kamling RT 02',
            'Pos Kamling RT 03',
            'Lapangan Sepak Bola Desa',
            'Gedung Serbaguna Desa',
            'Rumah Warga (Alamat akan dikonfirmasi)',
            'Taman Desa Kedungbanteng',
            'Balai Pertemuan RT'
        ];
        
        $organizers = [
            'Karang Taruna Kedungbanteng',
            'PKK Desa Kedungbanteng',
            'BPD Kedungbanteng',
            'RT 01 Kedungbanteng',
            'RT 02 Kedungbanteng',
            'RT 03 Kedungbanteng',
            'RW 01 Kedungbanteng',
            'RW 02 Kedungbanteng',
            'RW 03 Kedungbanteng',
            'Remaja Masjid Al-Ikhlas',
            'Kelompok Tani Maju Jaya',
            'UMKM Kedungbanteng',
            'Posyandu Melati',
            'Posyandu Mawar',
            'Kelompok Pengajian Ibu-ibu',
            'Komunitas Pemuda Kreatif',
            'Paguyuban Warga',
            'Tim Relawan Desa'
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
            
            $title = $eventType . ' ' . $topic . ' ' . date('Y', $eventDate->getTimestamp());
            
            Event::create([
                'title' => $title,
                'description' => $this->generateDescription($eventType, $topic, $organizer),
                'content' => $this->generateContent($eventType, $topic, $organizer),
                'image' => $faker->boolean(70) ? $this->generateImageName($i) : null, // 70% ada gambar
                'location' => $faker->randomElement($locations),
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'max_participants' => $faker->boolean(60) ? $faker->numberBetween(20, 200) : null,
                'contact_person' => $faker->name,
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
                'title' => $eventType . ' ' . $topic . ' (Selesai)',
                'description' => $this->generateDescription($eventType, $topic, $organizer),
                'content' => $this->generateContent($eventType, $topic, $organizer),
                'image' => $faker->boolean(50) ? $this->generateImageName($i + 100) : null,
                'location' => $faker->randomElement($locations),
                'event_date' => $eventDate->format('Y-m-d'),
                'start_time' => $this->generateValidTime(),
                'end_time' => $this->generateValidTime(),
                'max_participants' => $faker->boolean(60) ? $faker->numberBetween(20, 200) : null,
                'contact_person' => $faker->name,
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
            
            $newHour = min(23, $baseHour + rand(1, 4)); // Maksimal jam 23
            $newMinute = rand(0, 59);
            
            return sprintf('%02d:%02d', $newHour, $newMinute);
        }
        
        // Generate random valid time
        $hour = rand(8, 20); // Jam 8 pagi sampai 8 malam
        $minute = rand(0, 59);
        
        return sprintf('%02d:%02d', $hour, $minute);
    }
    
    private function generateDescription($eventType, $topic, $organizer)
    {
        $templates = [
            "Bergabunglah dalam {$eventType} {$topic} yang diselenggarakan oleh {$organizer}. Acara ini akan membahas berbagai hal menarik seputar {$topic} yang berguna untuk masyarakat desa.",
            
            "{$organizer} mengundang seluruh warga untuk mengikuti {$eventType} {$topic}. Mari bersama-sama belajar dan berbagi pengalaman dalam bidang {$topic}.",
            
            "Jangan lewatkan kesempatan emas ini! {$eventType} {$topic} akan memberikan wawasan baru dan pengetahuan praktis yang dapat diterapkan dalam kehidupan sehari-hari.",
            
            "Dalam rangka meningkatkan pengetahuan masyarakat tentang {$topic}, {$organizer} menyelenggarakan {$eventType} yang terbuka untuk umum.",
            
            "Mari ramaikan {$eventType} {$topic} yang akan menghadirkan pembicara berpengalaman dan materi yang sangat bermanfaat untuk kemajuan desa kita."
        ];
        
        return $templates[array_rand($templates)];
    }
    
    private function generateContent($eventType, $topic, $organizer)
    {
        return "
<h3>Tentang Acara</h3>
<p>{$eventType} {$topic} ini merupakan inisiatif dari {$organizer} untuk meningkatkan pengetahuan dan keterampilan masyarakat Desa Kedungbanteng dalam bidang {$topic}.</p>

<h3>Tujuan Acara</h3>
<ul>
<li>Memberikan pemahaman mendalam tentang {$topic}</li>
<li>Meningkatkan keterampilan praktis peserta</li>
<li>Membangun networking antar peserta</li>
<li>Mendorong inovasi dan kreativitas masyarakat</li>
</ul>

<h3>Materi yang Akan Dibahas</h3>
<ul>
<li>Pengenalan dasar {$topic}</li>
<li>Teknik dan strategi terkini</li>
<li>Studi kasus dan best practices</li>
<li>Sesi tanya jawab interaktif</li>
</ul>

<h3>Fasilitas</h3>
<ul>
<li>Materi pembelajaran lengkap</li>
<li>Sertifikat keikutsertaan</li>
<li>Konsumsi dan coffee break</li>
<li>Doorprize menarik</li>
</ul>

<h3>Persyaratan</h3>
<ul>
<li>Terbuka untuk umum</li>
<li>Membawa alat tulis</li>
<li>Registrasi wajib sebelum acara</li>
<li>Mematuhi protokol kesehatan</li>
</ul>

<p><strong>Catatan:</strong> Acara ini gratis dan terbuka untuk seluruh masyarakat. Mari bersama-sama membangun desa yang lebih maju dan sejahtera!</p>
        ";
    }
    
    private function generateImageName($index)
    {
        $extensions = ['jpg', 'jpeg', 'png'];
        $extension = $extensions[array_rand($extensions)];
        return 'event_' . time() . '_' . $index . '.' . $extension;
    }
}
