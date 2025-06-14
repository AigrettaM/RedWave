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
            ],
            [
                'title' => 'Persiapan Sebelum Donor Darah',
                'excerpt' => 'Panduan lengkap untuk mempersiapkan diri sebelum melakukan donor darah.',
                'content' => 'Konten lengkap artikel tentang persiapan donor darah...',
                'category' => 'tips',
                'author_name' => 'Dr. Ahmad Rizki',
                'author_title' => 'Dokter Umum',
            ],
            // Tambahkan data artikel lainnya...
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }
}
