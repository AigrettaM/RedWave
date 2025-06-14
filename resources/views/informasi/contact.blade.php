@extends('layouts.app')

@section('title', 'Contact - RedWave Blood Donation Center')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            Hubungi <span class="text-red-600">RedWave</span>
        </h1>
        <p class="text-xl text-gray-600 mb-6">
            Siap membantu Anda dengan informasi donor darah
        </p>
        <div class="bg-red-600 text-white px-6 py-3 rounded-lg inline-block">
            💬 Chatbot AI tersedia di pojok kanan bawah untuk bantuan instan!
        </div>
    </div>

    <!-- Contact Cards -->
    <div class="grid md:grid-cols-3 gap-8 mb-12">
        
        <!-- Phone Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-600">
            <div class="text-center">
                <div class="bg-red-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    📞
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Telepon</h3>
                <p class="text-gray-600 mb-3">Hubungi kami langsung</p>
                <a href="tel:+6221234567" class="text-red-600 font-semibold hover:text-red-800">
                    (021) 123-4567
                </a>
            </div>
        </div>

        <!-- WhatsApp Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-600">
            <div class="text-center">
                <div class="bg-red-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    📱
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">WhatsApp</h3>
                <p class="text-gray-600 mb-3">Chat via WhatsApp</p>
                <a href="https://wa.me/6281234567890" class="text-red-600 font-semibold hover:text-red-800">
                    0812-3456-7890
                </a>
            </div>
        </div>

        <!-- Email Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-600">
            <div class="text-center">
                <div class="bg-red-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    📧
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Email</h3>
                <p class="text-gray-600 mb-3">Kirim email kepada kami</p>
                <a href="mailto:info@redwave-donor.com" class="text-red-600 font-semibold hover:text-red-800">
                    info@redwave-donor.com
                </a>
            </div>
        </div>

    </div>

    <!-- Test Chatbot Section -->
    <div class="bg-blue-50 rounded-xl p-8 text-center mt-12">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">🤖 Test Chatbot RedWave</h3>
        <p class="text-gray-600 mb-6">
            Coba chatbot AI kami dengan mengetik kata-kata berikut:
        </p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div class="bg-white p-3 rounded-lg shadow">
                <strong>"halo"</strong><br>
                <span class="text-gray-500">Memulai percakapan</span>
            </div>
            <div class="bg-white p-3 rounded-lg shadow">
                <strong>"syarat"</strong><br>
                <span class="text-gray-500">Syarat donor darah</span>
            </div>
            <div class="bg-white p-3 rounded-lg shadow">
                <strong>"help"</strong><br>
                <span class="text-gray-500">Menu bantuan</span>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-4">
            💡 Chatbot tersedia di pojok kanan bawah halaman
        </p>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 text-white rounded-xl p-8 text-center mt-12">
        <h3 class="text-2xl font-bold mb-4">Siap untuk Donor Darah?</h3>
        <p class="text-lg mb-6 opacity-90">
            Jadilah pahlawan dengan mendonorkan darah Anda. Satu tindakan kecil, dampak besar!
        </p>
        <div class="space-x-4">
            <a href="#" class="bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
                Daftar Donor Sekarang
            </a>
            <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors inline-block">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </div>

</div>
@endsection

@push('botman-widget')
<script>
    var botmanWidget = {
        chatServer: '/botman',
        title: '🩸 RedWave Assistant',
        introMessage: 'Halo! Ketik "help" untuk melihat menu bantuan! 😊',
        placeholderText: 'Ketik pesan Anda...',
        
        // Warna dan styling
        mainColor: '#B31312',
        bubbleBackground: '#B31312',
        headerTextColor: '#ffffff',
        
        // Ukuran
        desktopHeight: 450,
        desktopWidth: 370,
    };
</script>
<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endpush
