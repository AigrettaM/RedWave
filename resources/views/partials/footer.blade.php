<footer class="bg-red-600 text-white w-full m-0 p-0 mt-16">
    <div class="px-6 py-8 bg-red-600 w-full">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <a href="/contact">
                    <button class="border-2 border-white px-6 py-3 rounded hover:bg-white hover:text-red-600 transition-all duration-300 font-medium">
                        Kontak Kami
                    </button>
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="font-medium">Ikuti sosial media kami</span>
                <div class="flex space-x-3">
                    <!-- Instagram -->
                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-300">
                        <img src="{{ asset('image/ig-30.png') }}" alt="Instagram" />
                    </a>
                    <!-- X -->
                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-300">
                        <img src="{{ asset('image/x-30.png') }}" alt="X" />
                    </a>
                    <!-- YouTube -->
                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-300">
                        <img src="{{ asset('image/yt-30.png') }}" alt="YouTube" />
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-800 px-6 py-6 border-t-0 m-0 w-full">
        <div class="max-w-7xl mx-auto text-center">
            <div class="text-gray-300 space-y-2">
                <p class="text-sm">© {{ date('Y') }} RedWave. Semua hak dilindungi.</p>
                <p class="text-sm font-medium">
                    Kami berkomitmen untuk memberikan <span class="text-white">dampak positif bagi masyarakat.</span>
                </p>
                <p class="text-sm">
                    Hubungi kami untuk berkontribusi dalam perubahan yang lebih baik.
                </p>
                <div class="mt-4">
                    <p class="text-xs text-gray-400">
                        💬 <strong>Chatbot AI</strong> tersedia di pojok kanan bawah untuk bantuan instan!
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
