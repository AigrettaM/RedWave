<div class="w-64 bg-red-600 text-white flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-red-500">
        <div class="flex items-center justify-center">
            <a href="/" class="flex items-center space-x-2">    
                <img src="{{ asset('image/logo-no-bg.png') }}" alt="logo" class="h-10" />
                <img src="{{ asset('image/Red-Wave.png') }}" alt="logo2" class="h-12"/>
            </a>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6">
        <ul class="space-y-2">

            <!-- Menu khusus untuk Admin -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('profiles.index') }}" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a>
                </li>

                <li>
                    <a href="{{ route('donor.index') }}" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('analytics') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Data Donor
                    </a>
                </li>

                <li>
                    <a href="/" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-file-alt mr-3"></i>
                        Lokasi
                    </a>
                </li>

                <li>
                    <a href="/" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('admin.logs') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Artikel
                    </a>
                </li>

                 <li>
                    <a href="/admin/events" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('admin.logs') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Event
                    </a>
                </li>

                <!-- Logout untuk Admin -->
                <li>
                    <div class="px-4 py-2">
                        <hr class="border-red-500">
                    </div>
                </li>

            @endif

            <!-- Menu khusus untuk User biasa -->
            @if(Auth::check() && Auth::user()->role === 'user')

                 <li>
                    <a href="/profile" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('user.orders.*') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-shopping-bag mr-3"></i>
                        Profile
                    </a>
                </li>

                <li>
                    <a href="/donor" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('user.orders.*') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-shopping-bag mr-3"></i>
                        Donor
                    </a>
                </li>

                <li>
                    <a href="/donor/history" class="flex items-center px-4 py-3 text-white hover:bg-red-700 rounded-lg transition-colors {{ request()->routeIs('user.history') ? 'bg-red-700' : '' }}">
                        <i class="fas fa-history mr-3"></i>
                        Riwayat Donor
                    </a>
                </li>

                <!-- Logout untuk User -->
                <li>
                    <div class="px-4 py-2">
                        <hr class="border-red-500">
                    </div>
                </li>
            @endif

        </ul>
    </nav>

    <!-- Footer Section -->
    <div class="p-4 border-t border-red-500">
        <div class="text-center text-red-200 text-xs">
            <p>&copy; 2024 Your App</p>
            <p>Version 1.0.0</p>
            @if(Auth::check())
                <p class="mt-1">Logged in as: <span class="font-semibold capitalize">{{ Auth::user()->role }}</span></p>
            @endif
        </div>
    </div>
</div>
