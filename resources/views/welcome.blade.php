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
        
        {{-- Tampilkan alert hanya untuk stok kritis --}}
        @if($criticalStocks->count() > 0)
            <div class="bg-red-100 border-l-4 border-red-500 p-4">
                <p><strong>Kebutuhan Darurat:</strong> 
                    Stok darah golongan 
                    @foreach($criticalStocks as $critical)
                        {{ $critical->blood_type }}{{ $critical->rhesus === 'POSITIF' ? '+' : '-' }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                    menipis, segera lakukan donor!
                </p>
            </div>
        @endif
        
        <div class="bg-green-100 border-l-4 border-green-500 p-4">
            <p><strong>Promo Reward:</strong> Donorkan darah dan dapatkan voucher kesehatan gratis!</p>
        </div>
    </section>

    <!-- Statistik Section -->
    <section class="py-10 bg-white rounded-xl">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Statistik Donor & Stok Darah</h2>
                @if(config('app.debug'))
                    <div class="bg-gray-100 p-4 mb-4">
                        <div class="grid grid-cols-4 gap-2 text-sm">
                            {{-- Urutkan: Positif dulu, baru Negatif untuk setiap golongan darah --}}
                            @foreach(['A', 'B', 'AB', 'O'] as $bloodType)
                                {{-- Tampilkan Positif dulu --}}
                                @foreach($detailedStocks->where('blood_type', $bloodType)->where('rhesus', 'POSITIF') as $detail)
                                    <div class="bg-white p-2 rounded">
                                        <strong>{{ $detail->blood_type }} {{ $detail->rhesus === 'POSITIF' ? '+' : '-' }}</strong><br>
                                        {{ $detail->stock_quantity }} kantong<br>
                                        <span class="text-xs text-gray-600">{{ $detail->status }}</span>
                                    </div>
                                @endforeach
                                
                                {{-- Kemudian tampilkan Negatif --}}
                                @foreach($detailedStocks->where('blood_type', $bloodType)->where('rhesus', 'NEGATIF') as $detail)
                                    <div class="bg-white p-2 rounded">
                                        <strong>{{ $detail->blood_type }} {{ $detail->rhesus === 'POSITIF' ? '+' : '-' }}</strong><br>
                                        {{ $detail->stock_quantity }} kantong<br>
                                        <span class="text-xs text-gray-600">{{ $detail->status }}</span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif

            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-blue-600">{{ $totalDonors }}</h3>
                    <p class="text-gray-600">Total Donor Terdaftar</p>
                </div>
                <div class="bg-red-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-red-600">{{ $criticalStocks->count() }}</h3>
                    <p class="text-gray-600">Stok Kritis</p>
                </div>
                <div class="bg-green-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-green-600">{{ $bloodAnalytics->sum('stock_quantity') }}</h3>
                    <p class="text-gray-600">Total Stok Kantong</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Chart -->
                <div class="flex justify-around items-end h-48 bg-white rounded-lg shadow p-4">
                    @foreach($bloodAnalytics as $analytics)
                        @php
                            $maxStock = $bloodAnalytics->max('stock_quantity');
                            $height = $maxStock > 0 ? ($analytics->stock_quantity / $maxStock) * 90 : 20;
                            $bgColor = match($analytics->status) {
                                'Kritis' => 'bg-red-700',
                                'Rendah' => 'bg-red-500',
                                'Normal' => 'bg-red-400',
                                'Aman' => 'bg-red-300',
                                default => 'bg-gray-400'
                            };
                        @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-12 {{ $bgColor }} rounded-t-md text-center text-white flex items-end justify-center pb-1 mb-2" 
                                 style="height: {{ max($height, 20) }}px">
                                {{ $analytics->blood_type }}
                            </div>
                            <span class="text-xs text-gray-600">{{ $analytics->stock_quantity }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Explanation -->
                <div>
                    <h3 class="text-xl font-semibold mb-2">Kenapa Donor Darah Penting?</h3>
                    <p class="mb-4">Stok darah yang cukup sangat dibutuhkan untuk membantu pasien yang membutuhkan transfusi, seperti korban kecelakaan, pasien operasi, dan penderita penyakit tertentu.</p>
                    
                    <div class="space-y-2">
                        <h4 class="font-semibold">Stok Darah Saat Ini:</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($bloodAnalytics as $analytics)
                                <li>
                                    <strong>{{ $analytics->blood_type }}:</strong> {{ $analytics->stock_quantity }} kantong
                                    @if($analytics->status === 'Kritis')
                                        <span class="text-red-600 font-bold">({{ $analytics->status }}!)</span>
                                    @elseif($analytics->status === 'Rendah')
                                        <span class="text-orange-600 font-bold">({{ $analytics->status }})</span>
                                    @endif
                                    <span class="text-sm text-gray-500">({{ $analytics->donor_count }} donor terdaftar)</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <a href="{{ url('login') }}">
                        <button class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition">    
                            Donor Sekarang
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Card -->
    <!-- <section class="text-center py-4 bg-gray-100">
        <div class="max-w-10xl mx-auto bg-white rounded-xl shadow-lg p-10">
            <h2 class="text-3xl font-bold mb-6">Siap Menjadi Pahlawan?</h2>
            <p class="text-lg mb-6 opacity-90">
                Jadilah pahlawan dengan mendonorkan darah Anda. Satu tindakan kecil, dampak besar!
            </p>
            <div class="space-x-4 p-2">
                <a href="{{ url('login') }} "class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition">Donor Sekarang</a>
                <a href="{{ url('lokasi') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-3 rounded-full font-semibold transition">Lihat Lokasi Terdekat</a>
            </div>
        </div>
    </section> -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 text-white rounded-xl p-8 text-center mt-12">
        <h3 class="text-2xl font-bold mb-4">Siap untuk Donor Darah?</h3>
        <p class="text-lg mb-6 opacity-90">
            Jadilah pahlawan dengan mendonorkan darah Anda. Satu tindakan kecil, dampak besar!
        </p>
        <div class="space-x-4">
            <a href="{{ url('login') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
                Daftar Donor Sekarang
            </a>
            <a href="{{ url('lokasi') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-colors inline-block">
                Lihat Lokasi Terdekat
            </a>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include("partials/footer")
@endsection
