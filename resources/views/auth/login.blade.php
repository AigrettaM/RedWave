@extends('layouts.app')
@section('main-class', 'mt-0 w-full px-0')

@section('content')
<div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="max-w-6xl w-full shadow-lg rounded-lg overflow-hidden"> 
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('image/foto 4.jpg') }}');">
                <!-- Gambar dengan padding kiri-kanan -->
            </div>
            <div class="flex justify-center items-center bg-white p-8">
                <div class="max-w-md w-full">
                    <div>
                        @if (Session::has('error'))
                            <!-- Menampilkan pesan error dengan desain seperti yang diinginkan -->
                            <div id="error-message" class="bg-pink-100 border-l-4 border-red-500 text-gray-800 p-3 mb-4 rounded-md w-full">
                                <p class="text-sm">{{ Session::get('error') }}</p>
                            </div>
                        @endif

                        <h2 class="text-2xl font-bold mb-6 text-center">LOGIN</h2>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="text" id="email" name="email" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('email') }}">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                            <input type="password" id="password" name="password" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div class="mb-4 flex justify-between items-center">
                            <label for="remember" class="inline-flex items-center">
                                <input type="checkbox" id="remember" name="remember" class="form-checkbox">
                                <span class="ml-2 text-sm text-gray-600">Remember Me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-gray hover:underline text-blue-900" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            @endif
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-600">LOGIN</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}" class="text-red-600 hover:underline">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            // Mengatur waktu untuk menghilangkan pesan error setelah 3 detik
            setTimeout(() => {
                // Mengubah tampilan menjadi transparan
                errorMessage.classList.remove('opacity-100');
                errorMessage.classList.add('opacity-0');

                // Menghilangkan elemen setelah transisi selesai
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 1000); // waktu yang sama dengan durasi transisi
            }, 3000); // pop-up muncul selama 3 detik
        }
    });
</script>

@endsection