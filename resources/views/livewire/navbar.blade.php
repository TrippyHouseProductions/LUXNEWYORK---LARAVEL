
<nav nav wire:poll class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4 h-20">
        <!-- Logo -->
        <a href="/" class="font-light text-3xl tracking-tight text-gray-900 hover:text-indigo-700 flex items-center space-x-1">
            <span class="font-bold">LUX</span>
            <span class="text-indigo-700 font-bold">NEWYORK</span>
        </a>

        <!-- Hamburger (mobile) -->
        <button @click="open = !open" class="lg:hidden text-gray-600 focus:outline-none">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Desktop Nav Links -->
        <ul class="hidden lg:flex space-x-1 items-center font-medium text-gray-700">
            <li>
                <a href="/products" class="hover:bg-blue-50 px-5 py-2 rounded transition text-base">Glasses</a>
            </li>
            <li>
                <a href="/products" class="hover:bg-blue-50 px-5 py-2 rounded transition text-base">Collections</a>
            </li>
            @auth
                @if(Auth::user()->user_type !== 'admin')
                    <li>
                        <a href="/cart" class="hover:bg-blue-50 px-4 py-2 rounded-full transition flex items-center text-base relative">
                            <svg class="w-6 h-6 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs px-2">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                @endif
                @if(Auth::user()->user_type === 'admin')
                    <li>
                        <a href="/admin/dashboard" class="bg-indigo-600 text-white px-5 py-2 rounded-lg shadow hover:bg-indigo-700 transition flex items-center text-base font-semibold">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Admin
                        </a>
                    </li>
                @endif
                <li>
                    <form method="POST" action="{{ Auth::user()->user_type === 'admin' ? route('admin.logout') : route('logout') }}">
                        @csrf
                        <button class="text-gray-600 hover:text-red-600 hover:bg-red-50 px-5 py-2 rounded transition flex items-center text-base">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg shadow hover:bg-indigo-700 transition flex items-center text-base font-semibold">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="border border-indigo-600 text-indigo-600 px-6 py-2 rounded-lg hover:bg-indigo-600 hover:text-white transition flex items-center text-base font-semibold">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Register
                    </a>
                </li>
            @endauth
        </ul>
    </div>
    <!-- Mobile menu -->
    <div class="lg:hidden px-4 pb-4" x-show="open" x-transition>
        <ul class="space-y-2 font-medium text-gray-700">
            <li>
                <a href="/products" class="block hover:bg-blue-50 px-4 py-2 rounded transition">Products</a>
            </li>
            <li>
                <a href="/categories" class="block hover:bg-blue-50 px-4 py-2 rounded transition">Categories</a>
            </li>
            @auth
                @if(Auth::user()->user_type !== 'admin')
                    <li>
                        <a href="/cart" class="block hover:bg-blue-50 px-4 py-2 rounded transition">Cart</a>
                    </li>
                @endif
                @if(Auth::user()->user_type === 'admin')
                    <li>
                        <a href="/admin/dashboard" class="block bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">Admin</a>
                    </li>
                @endif
                <li>
                    <form method="POST" action="{{ Auth::user()->user_type === 'admin' ? route('admin.logout') : route('logout') }}">
                        @csrf
                        <button class="block w-full text-left text-gray-600 hover:text-red-600 hover:bg-red-50 px-4 py-2 rounded transition">Logout</button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="block bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">Login</a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="block border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-600 hover:text-white transition">Register</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>
