@extends('dashboardlayout.app')

@section('page-title', 'Kuesioner Kesehatan - Tahap ' . $step)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Kuesioner Kesehatan</h1>
                <div class="text-sm text-gray-600">
                    Kode Donor: <span class="font-semibold text-red-600">{{ $donor->donor_code }}</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="flex items-center space-x-4 mb-4">
                @for($i = 1; $i <= 6; $i++)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                            @if($i <= $step + 1) bg-red-600 text-white
                            @else bg-gray-200 text-gray-600 @endif">
                            {{ $i }}
                        </div>
                        @if($i < 6)
                            <div class="w-8 h-1 mx-2 
                                @if($i <= $step) bg-red-600
                                @else bg-gray-200 @endif">
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
            
            <div class="text-sm text-gray-600">
                Tahap {{ $step + 1 }} dari 6: 
                @if($step == 1) Kondisi Hari Ini & Minggu Terakhir
                @elseif($step == 2) Riwayat 6-12 Minggu Terakhir
                @else Riwayat Jangka Panjang
                @endif
            </div>
        </div>

        <!-- Questions Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('donor.questions.save', $step) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    @if($questions->isEmpty())
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-4">
                                <i class="fas fa-question-circle text-4xl"></i>
                            </div>
                            <p class="text-gray-600">Tidak ada pertanyaan untuk tahap ini.</p>
                            <div class="mt-4">
                                <a href="{{ route('donor.questions', $step + 1) }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                                    Lanjut ke Tahap Berikutnya
                                </a>
                            </div>
                        </div>
                    @else
                        @foreach($questions as $index => $question)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <div class="flex items-start space-x-4">
                                    <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-1">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-gray-800 font-medium mb-3">
                                            {{ $question->question }}
                                        </label>
                                        
                                        @if($question->type == 'yes_no')
                                            <div class="flex space-x-6">
                                                <label class="flex items-center">
                                                    <input type="radio" name="question_{{ $question->id }}" value="yes" 
                                                           class="text-red-600 focus:ring-red-500" required>
                                                    <span class="ml-2 text-gray-700">Ya</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="question_{{ $question->id }}" value="no" 
                                                           class="text-red-600 focus:ring-red-500" required>
                                                    <span class="ml-2 text-gray-700">Tidak</span>
                                                </label>
                                            </div>
                                        @elseif($question->type == 'text')
                                            <input type="text" name="question_{{ $question->id }}" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                                                   placeholder="Masukkan jawaban...">
                                        @elseif($question->type == 'number')
                                            <input type="number" name="question_{{ $question->id }}" 
                                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                                                   placeholder="Masukkan angka...">
                                        @endif
                                        
                                        @if($question->is_disqualifying)
                                            <p class="text-xs text-red-500 mt-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Pertanyaan penting untuk kelayakan donor
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between pt-6">
                            <a href="{{ $step > 1 ? route('donor.questions', $step - 1) : route('donor.index') }}" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                {{ $step > 1 ? 'Sebelumnya' : 'Batal' }}
                            </a>
                            
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 flex items-center">
                                {{ $step == 3 ? 'Selesai' : 'Selanjutnya' }}
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="bg-blue-50 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">💡 Petunjuk Pengisian</h3>
            <ul class="space-y-2 text-sm text-blue-700">
                <li>• Jawab semua pertanyaan dengan jujur dan akurat</li>
                <li>• Jika ragu, pilih jawaban yang paling sesuai dengan kondisi Anda</li>
                <li>• Informasi ini digunakan untuk memastikan keamanan donor dan penerima</li>
                <li>• Semua informasi akan dijaga kerahasiaannya</li>
            </ul>
        </div>
    </div>
</div>
@endsection
