<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-8">
                <!-- Welcome Section -->
                <div class="text-center mb-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Use the navigation to manage your account</p>
                    <div class="w-24 h-1 bg-indigo-600 mx-auto mt-4"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Admin/User Specific Links -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        @if(Auth::user()->user_type === 'admin')
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Admin Management</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/admin/products" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-box-open mr-2"></i> Manage Products
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/categories" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-tags mr-2"></i> Manage Categories
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/orders" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-receipt mr-2"></i> Manage Orders
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/users" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-users mr-2"></i> Manage Users
                                    </a>
                                </li>
                            </ul>
                        @else
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Quick Links</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/orders" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-shopping-bag mr-2"></i> My Orders
                                    </a>
                                </li>
                                <li>
                                    <a href="/cart" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-shopping-cart mr-2"></i> My Cart
                                    </a>
                                </li>
                                <li>
                                    <a href="/wishlist" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-heart mr-2"></i> My Wishlist
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>

                    <!-- Account Management -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Account Management</h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('profile.show') }}" class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                    <i class="fas fa-user-edit mr-2"></i> Edit Profile
                                </a>
                            </li>
                            <!-- Add more links if needed -->
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                   class="flex items-center text-indigo-700 hover:text-indigo-900 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #202124;
            --secondary-color: #34a853;
            --accent-color: #fbbc04;
            --text-color: #202124;
            --background-color: #ffffff;
        }
        
        .btn-primary {
            position: relative;
            display: inline-block;
            padding: 0.75rem 2rem;
            border: 1px solid var(--primary-color);
            background-color: var(--primary-color);
            color: white;
            overflow: hidden;
            z-index: 1;
            border-radius: 9999px;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(255, 255, 255);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 0.4s ease;
            border-radius: 9999px;
            z-index: -1;
        }

        .btn-primary:hover::before {
            transform: scaleX(1);
        }

        .btn-primary:hover {
            color: var(--primary-color);
        }
    </style>