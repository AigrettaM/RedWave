<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        // Greeting responses
        $botman->hears('halo|hai|hello|hi|selamat', function ($botman) {
            $this->greetingResponse($botman);
        });

        // Info donor darah
        $botman->hears('syarat|persyaratan|requirement|1|satu', function ($botman) {
            $this->syaratDonorResponse($botman);
        });

        // Golongan darah
        $botman->hears('golongan|blood type|tipe darah|darah|2|dua', function ($botman) {
            $this->golonganDarahResponse($botman);
        });

        // Manfaat
        $botman->hears('manfaat|benefit|keuntungan|bagus|3|tiga', function ($botman) {
            $this->manfaatResponse($botman);
        });

        // Kontak
        $botman->hears('kontak|contact|hubungi|telepon|alamat|4|empat', function ($botman) {
            $this->kontakResponse($botman);
        });

        // Proses donor
        $botman->hears('proses|cara|langkah|bagaimana', function ($botman) {
            $this->prosesResponse($botman);
        });

        // Help
        $botman->hears('help|bantuan|menu|perintah', function ($botman) {
            $this->helpResponse($botman);
        });

        // Darurat
        $botman->hears('darurat|emergency|urgent|mendesak', function ($botman) {
            $this->emergencyResponse($botman);
        });

        // Fallback
        $botman->fallback(function($botman) {
            $this->fallbackResponse($botman);
        });

        $botman->listen();
    }

    // Method untuk greeting
    private function greetingResponse($botman)
    {
        $botman->reply('Halo, sobat Redwave! 🩸 <br>
                Selamat datang di RedWave Blood Donation Center! <br><br>
                Saya siap membantu Anda dengan informasi donor darah. 😊 <br><br>
                Ketik salah satu kata kunci berikut: <br>
                1. 📋 "syarat" - Syarat donor darah <br>
                2. 🩸 "golongan" - Info golongan darah <br>
                3. 💪 "manfaat" - Manfaat donor darah <br>
                4. 📞 "kontak" - Hubungi kami');
    }

    // Method untuk syarat donor
    private function syaratDonorResponse($botman)
    {
        $botman->reply('
                📋 Syarat Donor Darah 📋: <br><br>
                ✅ Usia 17-60 tahun (65 tahun untuk donor rutin) <br>
                ✅ Berat badan minimal 45 kg <br>
                ✅ Tekanan darah normal (100-170 / 70-100 mmHg) <br>
                ✅ Kadar hemoglobin normal (12.5-17.0 g/dL) <br>
                ✅ Tidak sedang hamil/menyusui <br>
                ✅ Tidak sedang sakit atau dalam pengobatan <br>
                ✅ Tidur cukup & sudah makan sebelum donor (minimal 6-8 jam)
                ');
    }

    // Method untuk golongan darah
    private function golonganDarahResponse($botman)
    {
        $botman->reply('
            🩸 <strong>Status Stok Darah Hari Ini:</strong> <br><br>
            🔴 <strong>Golongan A:</strong> Sangat Dibutuhkan <br>
            🔴 <strong>Golongan B:</strong> Dibutuhkan <br>
            🔴 <strong>Golongan AB:</strong> Stok Cukup <br>
            🔴 <strong>Golongan O:</strong> Sangat Dibutuhkan <br><br>
            💡 <strong>Info:</strong> Golongan O adalah donor universal! <br>
            🆘 <strong>Darurat:</strong> Golongan A & O sangat mendesak!
        ');
    }


    // Method untuk manfaat
    private function manfaatResponse($botman)
    {
        $botman->reply('
            💪 <strong>Manfaat Donor Darah untuk Anda:</strong> <br><br>
            ✨ Membantu menyelamatkan nyawa orang lain <br>
            ✨ Mendapat pemeriksaan kesehatan gratis <br>
            ✨ Membakar kalori (sekitar 650 kalori) <br>
            ✨ Mengurangi risiko penyakit jantung <br>
            ✨ Meningkatkan produksi sel darah baru <br>
            ✨ Mendapat sertifikat penghargaan <br><br>
            🏆 <strong>Bonus:</strong> Menjadi pahlawan tanpa tanda jasa!
        ');
    }


    // Method untuk kontak
    private function kontakResponse($botman)
    {
        $botman->reply('
            📞 <strong>Hubungi RedWave Blood Donation Center:</strong> <br><br>
            🏥 <strong>Nama:</strong> RedWave Blood Donation Center <br>
            📍 <strong>Alamat:</strong> Jl. Donor Darah No. 123, Jakarta Pusat 12345 <br>
            ☎️ <strong>Telepon:</strong> (021) 123-4567 <br>
            📱 <strong>WhatsApp:</strong> 0812-3456-7890 <br>
            📧 <strong>Email:</strong> info@redwave-donor.com <br>
            🌐 <strong>Website:</strong> www.redwave-donor.com <br><br>
            🚨 <strong>Hotline Darurat 24/7:</strong> 0812-3456-7890
        ');
    }


    // Method untuk proses
    private function prosesResponse($botman)
    {
        $botman->reply('
            📝 <strong>Proses Donor Darah:</strong> <br><br>
            1️⃣ <strong>Registrasi</strong> - Isi formulir & tunjukkan KTP <br>
            2️⃣ <strong>Pemeriksaan</strong> - Cek tensi, HB, & suhu tubuh <br>
            3️⃣ <strong>Konsultasi</strong> - Tanya jawab dengan petugas <br>
            4️⃣ <strong>Donor</strong> - Proses pengambilan darah (10-15 menit) <br>
            5️⃣ <strong>Istirahat</strong> - Minum & makan ringan <br>
            6️⃣ <strong>Selesai</strong> - Terima sertifikat & jadwal donor berikutnya <br><br>
            ⏱️ <strong>Total waktu:</strong> Sekitar 30-45 menit
        ');
    }

    // Method untuk help
    private function helpResponse($botman)
    {
        $botman->reply('
            🤖 <strong>Daftar Perintah RedWave Assistant:</strong> <br><br>
            📋 <strong>"syarat"</strong> - Syarat donor darah <br>
            🕐 <strong>"jadwal"</strong> - Jadwal & lokasi <br>
            🩸 <strong>"golongan"</strong> - Info golongan darah <br>
            💪 <strong>"manfaat"</strong> - Manfaat donor darah <br>
            📝 <strong>"proses"</strong> - Langkah-langkah donor <br>
            📞 <strong>"kontak"</strong> - Hubungi kami <br><br>
            💡 <strong>Tips:</strong> Ketik kata kunci untuk info lebih detail!
        ');
    }

    // Method untuk emergency
    private function emergencyResponse($botman)
    {
        $botman->reply('
            🚨 <strong>KONDISI DARURAT DARAH</strong> <br><br>
            📞 <strong>Hubungi Segera:</strong> <br>
            🔴 <strong>Hotline 24/7:</strong> 0812-3456-7890 <br>
            🔴 <strong>Telepon Kantor:</strong> (021) 123-4567 <br>
            🔴 <strong>WhatsApp:</strong> wa.me/6281234567890 <br>
            🏥 <strong>Lokasi:</strong> Jl. Donor Darah No. 123, Jakarta <br><br>
            ⚡ <strong>Kami siap membantu 24 jam untuk keadaan darurat!</strong>
        ');
    }

    // Method untuk fallback
    private function fallbackResponse($botman)
    {
        $botman->reply('
            Maaf, saya tidak mengerti pesan Anda. 🤔 <br><br>
            Ketik <strong>"help"</strong> untuk melihat daftar perintah yang tersedia. <br>
            Atau coba kata kunci: "syarat", "jadwal", "golongan", "manfaat", "kontak" <br><br>
            💬 Saya siap membantu Anda dengan informasi donor darah!
        ');
    }
}
