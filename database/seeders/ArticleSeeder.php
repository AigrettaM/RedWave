<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'title' => 'Pentingnya Donor Darah Rutin untuk Kesehatan',
                'excerpt' => 'Pelajari manfaat donor darah rutin tidak hanya untuk penerima, tetapi juga untuk kesehatan pendonor.',
                'content' => 'Konten lengkap artikel tentang pentingnya donor darah...',
                'category' => 'kesehatan',
                'author_name' => 'Dr. Sarah Johnson',
                'author_title' => 'Dokter Hematologi',
                'is_featured' => true,
                'slug' => 'pentingnya-donor-darah-rutin-untuk-kesehatan',
            ],
            [
                'title' => 'Persiapan Sebelum Donor Darah',
                'excerpt' => 'Panduan lengkap untuk mempersiapkan diri sebelum melakukan donor darah.',
                'content' => 'Konten lengkap artikel tentang persiapan donor darah...',
                'category' => 'tips',
                'author_name' => 'Dr. Ahmad Rizki',
                'author_title' => 'Dokter Umum',
                'is_featured' => false,
                'slug' => 'persiapan-sebelum-donor-darah',
            ],
            [
                'title' => 'Manfaat Donor Darah bagi Kesehatan',
                'excerpt' => 'Mengetahui manfaat kesehatan dari donor darah secara rutin.',
                'content' => 'Artikel ini membahas berbagai manfaat kesehatan yang didapat dari donor darah...',
                'category' => 'kesehatan',
                'author_name' => 'Dr. Budi Setiawan',
                'author_title' => 'Dokter Spesialis',
                'is_featured' => false,
                'slug' => 'manfaat-donor-darah-bagi-kesehatan',
            ],
            [
                'title' => 'Prosedur Donor Darah yang Aman',
                'excerpt' => 'Langkah-langkah untuk memastikan donor darah dilakukan dengan aman.',
                'content' => 'Dalam artikel ini, kami menjelaskan prosedur donor darah dan apa yang perlu diperhatikan...',
                'category' => 'tips',
                'author_name' => 'Dr. Lisa Mulyani',
                'author_title' => 'Dokter Umum',
                'is_featured' => true,
                'slug' => 'prosedur-donor-darah-yang-aman',
            ],
            [
                'title' => 'Kesalahan Umum Saat Donor Darah',
                'excerpt' => 'Hindari kesalahan umum yang sering dilakukan saat donor darah.',
                'content' => 'Artikel ini menjelaskan kesalahan-kesalahan yang harus dihindari saat melakukan donor darah...',
                'category' => 'tips',
                'author_name' => 'Dr. Rina Kusuma',
                'author_title' => 'Dokter Umum',
                'is_featured' => false,
                'slug' => 'kesalahan-umum-saat-donor-darah',
            ],
            // Tambahkan data artikel lainnya jika diperlukan...
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }
}
