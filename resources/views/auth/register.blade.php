@extends('layouts.app')
@section('main-class', 'pt-0 w-full px-0') 

@section('content')
<div class="flex justify-center items-start h-screen bg-gray-100 pt-40">
    <div class="max-w-6xl w-full shadow-lg rounded-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">    
            <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ asset('image/foto 4.jpg') }}');">
                <!-- Gambar dengan padding kiri-kanan -->
            </div>

            <div class="flex justify-center items-center bg-white p-8">
                <div class="max-w-md w-full">
                    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">REGISTER</h2>
    
                    <!-- Dynamic Alert Container - No gap when empty, smooth expand/collapse -->
                    <div id="alert-container" class="mb-4 overflow-hidden transition-all duration-500 ease-in-out" 
                         style="@if(!session('success') && !$errors->any() && !session('error')) max-height: 0; @else max-height: 200px; @endif">
                        
                        @if(session('success'))
                            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2 opacity-100 transition-all duration-1000">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2 opacity-100 transition-all duration-1000">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div id="session-error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2 opacity-100 transition-all duration-1000">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-2">
                            <label for="name" class="block text-sm font-medium text-gray-600">Username</label>
                            <input type="text" id="name" name="name" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('name') }}">
                        </div>
                        <div class="mb-2">
                            <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" id="email" name="email" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('email') }}">
                        </div>
                        <div class="mb-2">
                            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                            <input type="password" id="password" name="password" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div class="mb-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Register</button>
                        </div>
                    </form>
                    <div class="mt-2 text-center">
                        <span>Already have an account?</span>   
                        <a href="{{ route('login') }}" class="text-red-600 hover:underline">Login</a>
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
    hideMessage('success-message', 3000);      // Success: 3 seconds
    hideMessage('error-message', 5000);        // Validation errors: 5 seconds
    hideMessage('session-error-message', 3000); // Session errors: 3 seconds
});
</script>
@endsection
