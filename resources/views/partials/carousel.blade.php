<div class="relative bg-white rounded-lg shadow-lg overflow-hidden max-w-10xl mx-auto">
    <div id="carousel" class="flex transition-transform duration-500 ease-in-out" style="scroll-behavior: smooth;">
        <!-- Ke 1 -->
        <div class="w-full flex-shrink-0 relative">
            <img src="{{ asset('image/foto 1.jpg') }}"
                 alt="Pemandangan Gunung" 
                 class="w-full h-96 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <div class="text-center text-white">
                    <h2 class="text-4xl font-bold mb-4">Bersama Kita Pasti Bisa</h2>
                    <p class="text-xl">Dukung program kemanusiaan untuk masa depan lebih baik</p>
                    <button class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition">Donor Sekarang</button>
                </div>
            </div>
        </div>
        
        <!-- Ke 2 -->
        <div class="w-full flex-shrink-0 relative">
            <img src="{{ asset('image/foto 2.png') }}"
                 alt="Pantai Tropis" 
                 class="w-full h-96 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <div class="text-center text-white">
                    <h2 class="text-4xl font-bold mb-4">Tahun Berganti, Dedikasi tak berhenti</h2>
                    <p class="text-xl">Bergabunglah dengan kami, misi untuk membantu mereka yang membutuhkan</p>
                    <button class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition">Donor Sekarang</button>
                </div>
            </div>
        </div>
        
        <!-- Ke 3 -->
        <div class="w-full flex-shrink-0 relative">
            <img src="{{ asset('image/foto 4.jpg') }}"
                 alt="Hutan Hijau" 
                 class="w-full h-96 object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <div class="text-center text-white">
                    <h2 class="text-4xl font-bold mb-4">Harapan Baru di setiap langkah</h2>
                    <p class="text-xl">Setiap donasi memberikan harapan bagi mereka yang membutuhkan</p>
                    <button class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition">Donor Sekarang</button>
                </div>
            </div>
        </div>
    </div>
   
    <button id="prevBtn" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-3 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    
    <button id="nextBtn" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white p-3 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
   
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <button class="indicator w-3 h-3 bg-white bg-opacity-50 rounded-full hover:bg-opacity-75 transition-all duration-200 focus:outline-none" data-slide="0"></button>
        <button class="indicator w-3 h-3 bg-white bg-opacity-50 rounded-full hover:bg-opacity-75 transition-all duration-200 focus:outline-none" data-slide="1"></button>
        <button class="indicator w-3 h-3 bg-white bg-opacity-50 rounded-full hover:bg-opacity-75 transition-all duration-200 focus:outline-none" data-slide="2"></button>
    </div>
</div>

<script>
let currentSlide = 0;
const carousel = document.getElementById('carousel');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = 3;

// Update indicator active state
function updateIndicators() {
    indicators.forEach((indicator, index) => {
        if (index === currentSlide) {
            indicator.classList.remove('bg-opacity-50');
            indicator.classList.add('bg-opacity-100');
        } else {
            indicator.classList.remove('bg-opacity-100');
            indicator.classList.add('bg-opacity-50');
        }
    });
}

// Go to specific slide
function goToSlide(slideIndex) {
    currentSlide = slideIndex;
    const translateX = -slideIndex * 100;
    carousel.style.transform = `translateX(${translateX}%)`;
    updateIndicators();
}

// Next slide
function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    goToSlide(currentSlide);
}

// Previous slide
function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    goToSlide(currentSlide);
}

// Event listeners
document.getElementById('nextBtn').addEventListener('click', nextSlide);
document.getElementById('prevBtn').addEventListener('click', prevSlide);

// Indicator click events
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => goToSlide(index));
});

// Auto-slide (optional)
setInterval(nextSlide, 5000);

// Initialize
updateIndicators();
</script>