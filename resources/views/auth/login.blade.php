@extends('layouts.app')
@section('main-class', 'pt-0 w-full px-0') 

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100 py-12">
    <div class="max-w-6xl w-full shadow-lg rounded-lg overflow-hidden"> 
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('image/foto 4.jpg') }}');">
                <!-- Gambar dengan padding kiri-kanan -->
            </div>
            <div class="flex justify-center items-center bg-white p-8">
                <div class="max-w-md w-full">
                    <h2 class="text-2xl font-bold mb-6 text-center">LOGIN</h2>

                    <!-- Dynamic Alert Container - Same behavior as register -->
                    <div id="alert-container" class="mb-4 overflow-hidden transition-all duration-500 ease-in-out" 
                         style="@if(!Session::has('error') && !session('success')) max-height: 0; @else max-height: 200px; @endif">
                        
                        @if (Session::has('error'))
                            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2 opacity-100 transition-all duration-1000">
                                {{ Session::get('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2 opacity-100 transition-all duration-1000">
                                {{ session('success') }}
                            </div>
                        @endif
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
    const alertContainer = document.getElementById('alert-container');
    
    function hideMessage(messageId, delay = 3000) {
        const message = document.getElementById(messageId);
        if (message) {
            setTimeout(() => {
                // Fade out animation
                message.style.transform = 'translateY(-10px)';
                message.style.opacity = '0';
                
                setTimeout(() => {
                    message.remove();
                    
                    // Check if container is empty, then collapse
                    if (alertContainer.children.length === 0) {
                        alertContainer.style.maxHeight = '0';
                    }
                }, 1000);
            }, delay);
        }
    }

    // Auto-hide messages with different delays
    hideMessage('error-message', 3000);    // Login errors: 3 seconds
    hideMessage('success-message', 3000);  // Success messages: 3 seconds
});
</script>
@endsection
