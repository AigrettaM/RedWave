<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name ?? 'Guest' }}!</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Logout Button dengan Link -->
            @if(Auth::check())
                <a href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout
                </a>
                
                <!-- Hidden Form dengan Method DELETE -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
</header>
