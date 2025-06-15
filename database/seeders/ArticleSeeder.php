<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('articles')->truncate();

        $articles = [
            [
                'title' => 'Panduan Lengkap Belajar Laravel untuk Pemula',
                'slug' => 'panduan-lengkap-belajar-laravel-untuk-pemula',
                'excerpt' => 'Laravel adalah framework PHP yang powerful dan mudah dipelajari. Artikel ini akan membahas dasar-dasar Laravel dari awal hingga mahir.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'featured_image' => 'articles/laravel-guide.jpg',
                'author_name' => 'Ahmad Rizki Pratama',
                'author_title' => 'Senior Laravel Developer',
                'author_avatar' => 'avatars/ahmad-rizki.jpg',
                'is_published' => true,
                'is_featured' => true,
                'category' => 'web-development',
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Tips dan Trik Optimasi Database MySQL untuk Performa Maksimal',
                'slug' => 'tips-dan-trik-optimasi-database-mysql-untuk-performa-maksimal',
                'excerpt' => 'Pelajari cara mengoptimalkan performa database MySQL dengan berbagai teknik dan best practices yang terbukti efektif dalam production.',
                'content' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'featured_image' => 'articles/mysql-optimization.jpg',
                'author_name' => 'Sari Dewi Lestari',
                'author_title' => 'Database Administrator',
                'author_avatar' => 'avatars/sari-dewi.jpg',
                'is_published' => true,
                'is_featured' => false,
                'category' => 'database',
                'published_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subHours(5),
            ],
            [
                'title' => 'Membangun API RESTful dengan Node.js dan Express Framework',
                'slug' => 'membangun-api-restful-dengan-nodejs-dan-express-framework',
                'excerpt' => 'Tutorial step-by-step untuk membuat API RESTful yang scalable dan secure menggunakan Node.js dan Express framework.',
                'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'featured_image' => null, // Tidak ada gambar
                'author_name' => 'Budi Santoso',
                'author_title' => 'Full Stack Developer',
                'author_avatar' => null, // Tidak ada avatar
                'is_published' => false, // Draft
                'is_featured' => false,
                'category' => 'backend-development',
                'published_at' => null, // Belum dipublikasikan
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'title' => 'Mengenal React Hooks: useState, useEffect, dan Custom Hooks',
                'slug' => 'mengenal-react-hooks-usestate-useeffect-dan-custom-hooks',
                'excerpt' => 'React Hooks telah mengubah cara kita menulis komponen React. Pelajari penggunaan hooks yang paling umum digunakan dalam development.',
                'content' => 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.',
                'featured_image' => 'articles/react-hooks.jpg',
                'author_name' => 'Lisa Permata Sari',
                'author_title' => 'Frontend Specialist',
                'author_avatar' => 'avatars/lisa-permata.jpg',
                'is_published' => true,
                'is_featured' => true,
                'category' => 'frontend-development',
                'published_at' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subHours(8),
            ],
            [
                'title' => 'Implementasi Design Patterns dalam PHP: Singleton, Factory, Observer',
                'slug' => 'implementasi-design-patterns-dalam-php-singleton-factory-observer',
                'excerpt' => 'Design patterns adalah solusi umum untuk masalah yang sering terjadi dalam pengembangan software. Mari pelajari implementasinya di PHP.',
                'content' => 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.',
                'featured_image' => 'articles/php-design-patterns.jpg',
                'author_name' => 'Eko Prasetyo',
                'author_title' => 'Software Architect',
                'author_avatar' => 'avatars/eko-prasetyo.jpg',
                'is_published' => false, // Draft
                'is_featured' => false,
                'category' => 'software-engineering',
                'published_at' => null,
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            [
                'title' => 'Keamanan Web: Mencegah Serangan XSS, CSRF, dan SQL Injection',
                'slug' => 'keamanan-web-mencegah-serangan-xss-csrf-dan-sql-injection',
                'excerpt' => 'Keamanan web adalah aspek penting dalam pengembangan aplikasi. Pelajari cara melindungi aplikasi dari berbagai jenis serangan cyber.',
                'content' => 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.',
                'featured_image' => 'articles/web-security.jpg',
                'author_name' => 'Dian Kusuma Wardani',
                'author_title' => 'Security Engineer',
                'author_avatar' => 'avatars/dian-kusuma.jpg',
                'is_published' => true,
                'is_featured' => true,
                'category' => 'web-security',
                'published_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'Microservices Architecture dengan Docker dan Kubernetes',
                'slug' => 'microservices-architecture-dengan-docker-dan-kubernetes',
                'excerpt' => 'Arsitektur microservices menjadi trend dalam pengembangan aplikasi modern. Pelajari implementasinya dengan Docker dan Kubernetes.',
                'content' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.',
                'featured_image' => null,
                'author_name' => 'Ravi Sharma',
                'author_title' => 'DevOps Engineer',
                'author_avatar' => null,
                'is_published' => true,
                'is_featured' => false,
                'category' => 'devops',
                'published_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'title' => 'Machine Learning untuk Web Developer: Implementasi TensorFlow.js',
                'slug' => 'machine-learning-untuk-web-developer-implementasi-tensorflowjs',
                'excerpt' => 'Machine Learning tidak hanya untuk data scientist. Web developer juga bisa memanfaatkan ML untuk meningkatkan user experience aplikasi.',
                'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.',
                'featured_image' => 'articles/ml-web-dev.jpg',
                'author_name' => 'Maya Sari Indah',
                'author_title' => 'ML Engineer',
                'author_avatar' => 'avatars/maya-sari.jpg',
                'is_published' => false,
                'is_featured' => true,
                'category' => 'machine-learning',
                'published_at' => null,
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'title' => 'Vue.js 3 Composition API: Panduan Lengkap untuk Developer',
                'slug' => 'vuejs-3-composition-api-panduan-lengkap-untuk-developer',
                'excerpt' => 'Vue.js 3 membawa banyak fitur baru termasuk Composition API. Pelajari cara menggunakan fitur-fitur terbaru Vue.js 3.',
                'content' => 'Similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.',
                'featured_image' => 'articles/vuejs-composition-api.jpg',
                'author_name' => 'Andi Wijaya',
                'author_title' => 'Vue.js Specialist',
                'author_avatar' => 'avatars/andi-wijaya.jpg',
                'is_published' => true,
                'is_featured' => false,
                'category' => 'frontend-development',
                'published_at' => Carbon::now()->subHours(18),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(10),
            ],
            [
                'title' => 'CI/CD Pipeline dengan GitHub Actions dan AWS',
                'slug' => 'cicd-pipeline-dengan-github-actions-dan-aws',
                'excerpt' => 'Otomatisasi deployment dengan CI/CD pipeline menggunakan GitHub Actions dan AWS. Tingkatkan efisiensi development workflow Anda.',
                'content' => 'Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est.',
                'featured_image' => 'articles/cicd-github-aws.jpg',
                'author_name' => 'Rizky Firmansyah',
                'author_title' => 'DevOps Specialist',
                'author_avatar' => 'avatars/rizky-firmansyah.jpg',
                'is_published' => true,
                'is_featured' => true,
                'category' => 'devops',
                'published_at' => Carbon::now()->subDays(6),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(5),
            ]
        ];

        foreach ($articles as $article) {
            DB::table('articles')->insert($article);
        }

        $this->command->info('Articles seeded successfully!');
    }
}
