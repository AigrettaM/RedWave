<?php
// database/seeders/DonorQuestionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DonorQuestion;

class DonorQuestionSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        DonorQuestion::truncate();

        $questions = [
            // Hari Ini - SEHAT = TIDAK diskualifikasi, SAKIT = diskualifikasi
            ['category' => 'today', 'question' => 'Apakah Anda sehat pada hari ini?', 'is_disqualifying' => true, 'order' => 1],
            ['category' => 'today', 'question' => 'Apakah Anda sedang minum antibiotik?', 'is_disqualifying' => true, 'order' => 2],
            ['category' => 'today', 'question' => 'Apakah Anda sedang minum obat lain untuk infeksi?', 'is_disqualifying' => true, 'order' => 3],

            // 48 Jam Terakhir
            ['category' => '48hours', 'question' => 'Apakah Anda sedang minum aspirin atau obat yang mengandung aspirin?', 'is_disqualifying' => true, 'order' => 4],

            // 1 Minggu Terakhir
            ['category' => '1week', 'question' => 'Apakah Anda mengalami sakit kepala dan demam secara bersamaan?', 'is_disqualifying' => true, 'order' => 5],

            // 6 Minggu Terakhir
            ['category' => '6weeks', 'question' => 'Untuk donor wanita: apakah saat ini sedang hamil?', 'is_disqualifying' => true, 'order' => 6],

            // 8 Minggu Terakhir
            ['category' => '8weeks', 'question' => 'Apakah Anda pernah mendonorkan darah?', 'is_disqualifying' => true, 'order' => 7],
            ['category' => '8weeks', 'question' => 'Apakah Anda menerima vaksinasi atau suntikan lainnya?', 'is_disqualifying' => true, 'order' => 8],
            ['category' => '8weeks', 'question' => 'Apakah Anda pernah kontak dengan orang yang menerima vaksin smallpox?', 'is_disqualifying' => true, 'order' => 9],

            // 10 Minggu Terakhir
            ['category' => '10weeks', 'question' => 'Apakah Anda pernah menyumbangkan 2 kantong sel darah melalui proses apheresis?', 'is_disqualifying' => true, 'order' => 10],

            // 12 Minggu Terakhir
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah menerima transfusi darah?', 'is_disqualifying' => true, 'order' => 11],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah mendapat transplantasi organ, jaringan atau sumsum tulang?', 'is_disqualifying' => true, 'order' => 12],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah cangkok tulang untuk kulit?', 'is_disqualifying' => true, 'order' => 13],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah tertusuk jarum medis?', 'is_disqualifying' => true, 'order' => 14],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah berhubungan seksual dengan orang penderita HIV/AIDS?', 'is_disqualifying' => true, 'order' => 15],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah berhubungan dengan pekerja seks komersial?', 'is_disqualifying' => true, 'order' => 16],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah berhubungan dengan pengguna narkoba jarum suntik?', 'is_disqualifying' => true, 'order' => 17],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah berhubungan dengan pengguna konsentrat factor pembeku?', 'is_disqualifying' => true, 'order' => 18],
            ['category' => '12weeks', 'question' => 'Untuk donor wanita: Apakah Anda pernah berhubungan dengan laki-laki biseksual?', 'is_disqualifying' => true, 'order' => 19],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah berhubungan seksual dengan penderita hepatitis?', 'is_disqualifying' => true, 'order' => 20],
            ['category' => '12weeks', 'question' => 'Apakah Anda tinggal dengan penderita hepatitis?', 'is_disqualifying' => true, 'order' => 21],
            ['category' => '12weeks', 'question' => 'Apakah Anda memiliki tato?', 'is_disqualifying' => true, 'order' => 22],
            ['category' => '12weeks', 'question' => 'Apakah Anda memiliki tindik telinga atau bagian tubuh lainnya?', 'is_disqualifying' => true, 'order' => 23],
            ['category' => '12weeks', 'question' => 'Apakah Anda sedang atau pernah mendapatkan pengobatan sifilis atau GO (kencing bernanah)?', 'is_disqualifying' => true, 'order' => 24],
            ['category' => '12weeks', 'question' => 'Apakah Anda pernah ditahan di penjara untuk waktu lebih dari 72 jam?', 'is_disqualifying' => true, 'order' => 25],

            // 3 Tahun Terakhir - Ini tidak diskualifikasi otomatis
            ['category' => '3years', 'question' => 'Apakah Anda pernah berada di luar Indonesia?', 'is_disqualifying' => false, 'order' => 26],

            // 1987 - Sekarang
            ['category' => '1987-now', 'question' => 'Apakah Anda pernah menerima uang, obat atau pembayaran lainnya untuk seks?', 'is_disqualifying' => true, 'order' => 27],
            ['category' => '1987-now', 'question' => 'Untuk laki-laki: Apakah Anda pernah berhubungan seksual dengan laki-laki?', 'is_disqualifying' => true, 'order' => 28],

            // 1980 - Sekarang
            ['category' => '1980-now', 'question' => 'Apakah Anda tinggal selama 5 tahun atau lebih di Eropa?', 'is_disqualifying' => true, 'order' => 29],
            ['category' => '1980-now', 'question' => 'Apakah Anda menerima transfusi darah di Inggris?', 'is_disqualifying' => true, 'order' => 30],

            // 1980 - 1996
            ['category' => '1980-1996', 'question' => 'Apakah Anda tinggal selama 3 bulan atau lebih di Inggris?', 'is_disqualifying' => true, 'order' => 31],

            // General/Umum
            ['category' => 'general', 'question' => 'Apakah Anda mendapat hasil positif untuk HIV/AIDS?', 'is_disqualifying' => true, 'order' => 32],
            ['category' => 'general', 'question' => 'Apakah Anda menggunakan jarum suntik untuk obat-obatan, steroid yang tidak diresepkan dokter?', 'is_disqualifying' => true, 'order' => 33],
            ['category' => 'general', 'question' => 'Apakah Anda menggunakan konsentrat faktor pembeku?', 'is_disqualifying' => true, 'order' => 34],
            ['category' => 'general', 'question' => 'Apakah Anda menderita hepatitis?', 'is_disqualifying' => true, 'order' => 35],
            ['category' => 'general', 'question' => 'Apakah Anda menderita malaria?', 'is_disqualifying' => true, 'order' => 36],
            ['category' => 'general', 'question' => 'Apakah Anda menderita kanker termasuk leukemia?', 'is_disqualifying' => true, 'order' => 37],
            ['category' => 'general', 'question' => 'Apakah Anda bermasalah dengan jantung dan paru-paru?', 'is_disqualifying' => true, 'order' => 38],
            ['category' => 'general', 'question' => 'Apakah Anda menderita pendarahan atau penyakit berhubungan dengan darah?', 'is_disqualifying' => true, 'order' => 39],
            ['category' => 'general', 'question' => 'Apakah Anda berhubungan seksual dengan orang yang tinggal di Afrika?', 'is_disqualifying' => true, 'order' => 40],
            ['category' => 'general', 'question' => 'Apakah Anda tinggal di Afrika?', 'is_disqualifying' => true, 'order' => 41],
        ];

        foreach ($questions as $question) {
            DonorQuestion::create($question);
        }
    }
}
