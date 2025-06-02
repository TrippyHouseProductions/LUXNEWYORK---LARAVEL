<nav wire:poll
     class="sticky top-0 z-50 border-b border-gray-100 backdrop-blur-xl bg-white/80 shadow-lg transition-transform duration-300 will-change-transform h-[6vw]">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4  h-[6vw]">

        <a href="/" class="flex items-center font-light text-2xl tracking-tight text-gray-900 hover:text-indigo-700 transition space-x-1">
            <span>LUX</span><span class="font-bold">NEWYORK</span>
        </a>

        <ul class="flex space-x-2 items-center font-medium text-gray-700">
            <li>
                <a href="/products" class="hover:bg-indigo-50 px-5 py-2 rounded-full transition text-base">All Glasses</a>
            </li>
            <li>
                <a href="/new-arrivals" class="hover:bg-indigo-50 px-5 py-2 rounded-full transition text-base">New Arrivals</a>
            </li>
        </ul>
        <div class="flex items-center space-x-2">
            @auth
                @if(Auth::user()->user_type !== 'admin')
                    <a href="/cart" class="hover:bg-indigo-50 px-4 py-2 rounded-full transition flex items-center text-base relative">
                        <svg class="w-6 h-6 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs px-2">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="/wishlist" class="hover:bg-indigo-50 px-4 py-2 rounded-full transition flex items-center text-base">
                        <svg class="w-6 h-6 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                @endif
                <a href="/dashboard" class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-full shadow hover:bg-indigo-700 hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:outline-none transition flex items-center text-base">
                    Dashboard
                </a>
                <!-- <form method="POST" action="{{ Auth::user()->user_type === 'admin' ? route('admin.logout') : route('logout') }}" class="inline">
                    @csrf
                    <button class="text-gray-600 hover:text-red-600 hover:bg-red-50 px-5 py-2.5 rounded-full transition flex items-center text-base">
                        Logout
                    </button>
                </form> -->
            @else
                <a href="{{ route('login') }}" class="text-center bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-full shadow hover:bg-indigo-700 hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:outline-none transition flex items-center text-base">
                    Login
                </a>
                <a href="{{ route('register') }}" class="border border-indigo-600 text-indigo-600 font-semibold px-6 py-2.5 rounded-full hover:bg-indigo-600 hover:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow transition flex items-center text-base">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>
