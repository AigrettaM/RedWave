@extends('layouts.app')

@section('title', 'RedWave')

@section('content')

<div>
    <div class="pt-24">
        @include('partials.carousel')
    </div>

    <section class="py-14 space-y-4">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4">
            <p><strong>Event Donor:</strong> Ayo ikut donor darah di pusat kesehatan kota pada 25 Maret 2025.</p>
        </div>
        <div class="bg-red-100 border-l-4 border-red-500 p-4">
            <p><strong>Kebutuhan Darurat:</strong> Stok darah golongan O menipis, segera lakukan donor!</p>
        </div>
        <div class="bg-green-100 border-l-4 border-green-500 p-4">
            <p><strong>Promo Reward:</strong> Donorkan darah dan dapatkan voucher kesehatan gratis!</p>
        </div>
    </section>

  <!-- Statistik Section -->
    <section class="py-10 bg-white rounded-xl">
        <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Statistik Donor & Stok Darah</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Chart -->
            <div class="flex justify-around items-end h-48 bg-white rounded-lg shadow p-4">
            <div class="w-10 bg-red-500 h-[90%] rounded-t-md text-center text-white">A</div>
            <div class="w-10 bg-red-400 h-[60%] rounded-t-md text-center text-white">B</div>
            <div class="w-10 bg-red-600 h-[20%] rounded-t-md text-center text-white">O</div>
            <div class="w-10 bg-red-300 h-[50%] rounded-t-md text-center text-white">AB</div>
            </div>

            <!-- Explanation -->
            <div>
            <h3 class="text-xl font-semibold mb-2">Kenapa Donor Darah Penting?</h3>
            <p class="mb-4">Stok darah yang cukup sangat dibutuhkan untuk membantu pasien yang membutuhkan transfusi, seperti korban kecelakaan, pasien operasi, dan penderita penyakit tertentu.</p>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong>A:</strong> 45 kantong</li>
                <li><strong>B:</strong> 30 kantong</li>
                <li><strong>O:</strong> 10 kantong <span class="text-red-600 font-bold">(Kritis!)</span></li>
                <li><strong>AB:</strong> 25 kantong</li>
            </ul>
            <button class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition">Donor Sekarang</button>
            </div>
        </div>
        </div>
    </section>

  <!-- Call to Action Card -->
    <section class="text-center py-4 bg-gray-100 shadow-lg">
        <div class="max-w-10xl mx-auto bg-white rounded-xl shadow-lg p-10">
        <h2 class="text-3xl font-bold mb-6">Siap Menjadi Pahlawan?</h2>
        <div class="space-x-4">
            <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition">Donor Sekarang</button>
            <a href="{{ url('lokasi') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-3 rounded-full font-semibold transition">Lihat Lokasi Terdekat</a>
        </div>
        </div>
    </section>
</div>
@endsection

@section('footer')
    @include("partials/footer")
@endsection