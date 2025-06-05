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
                            <div id="error-message" class="bg-pink-100 border-l-4 border-red-500 text-gray-800 p-3 mb-4 rounded-md w-full z-50 opacity-100 transition-opacity duration-1000">
                                <p class="text-sm">{{ Session::get('error') }}</p>
                            </div>
                        @endif

                        <h3 class="text-xl font-bold mb-4 text-center">REGISTER</h3>
                    </div>
                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>                
                    @endif
                    
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
@endsection