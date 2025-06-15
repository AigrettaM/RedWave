<header class="bg-white shadow-sm fixed top-0 left-0 w-full z-50">
    <nav class="max-w-screen-lg mx-auto h-24 flex justify-between items-center px-4">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <a href="/" class="flex items-center">    
                <img src="{{ asset('image/logo1.png') }}" alt="logo" class="h-16" />
                <img src="{{ asset('image/Red-Wave.png') }}" alt="logo2" class="h-14"/>
            </a>
        </div>
        
        <ul class="flex items-center space-x-10 text-m font-semibold text-maroon">
            <li>
                <div class="relative inline-block text-left">
                    <button id="dropdownButton" class="px-4 py-2 bg-white text-black rounded-md hover:bg-red-900 hover:text-white transition duration-200 transform hover:scale-105 hover:-translate-y-0.5 
                        {{ request()->is('article') || request()->is('event') || request()->is('alur-donor') || request()->is('location') ? 'border-b-2 border-red-900' : '' }}">
                        Informasi
                    </button>
                    <div id="dropdownMenu" class="absolute left-0 right-6 mt-2 w-36 bg-white border border-gray-300 rounded-md shadow-lg hidden z-10 overflow-y-auto max-h-96">
                        <ul class="text-sm text-gray-800 divide-y divide-gray-100">
                            <li class="px-4 py-2 hover:bg-gray-100 {{ request()->is('article') ? 'border-b-2 border-red-900 bg-red-50' : '' }}">
                                <a href="/article" class="hover:text-red-800">Article</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-100 {{ request()->is('event') ? 'border-b-2 border-red-900 bg-red-50' : '' }}">
                                <a href="/informasi/events" class="hover:text-red-800">Event</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-100 {{ request()->is('alur-donor') ? 'border-b-2 border-red-900 bg-red-50' : '' }}">
                                <a href="/alur-donor" class="hover:text-red-800">Alur Donor Darah</a>
                            </li>
                            <li class="px-4 py-2 hover:bg-gray-100 {{ request()->is('location') ? 'border-b-2 border-red-900 bg-red-50' : '' }}">
                                <a href="/location" class="hover:text-red-800">Lokasi</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
                <a href="/contact">
                    <button class="px-4 py-2 bg-white text-black rounded-md hover:bg-red-900 hover:text-white transition duration-200 transform hover:scale-105 hover:-translate-y-0.5 
                        {{ request()->is('contact') ? 'border-b-2 border-red-900' : '' }}">
                        Contact
                    </button>
                </a>
            </li>
            <li>
                <a href="/login">
                    <button class="px-4 py-2 bg-white text-black rounded-md hover:bg-red-900 hover:text-white transition duration-200 transform hover:scale-105 hover:-translate-y-0.5 
                        {{ request()->is('login') || request()->is('register') ? 'border-b-2 border-red-900' : '' }}">
                        Login
                    </button>
                </a>
            </li>
        </ul>
    </nav>
</header>
