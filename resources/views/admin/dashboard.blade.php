@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <!-- Main content -->
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h2>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Products</p>
                            <!-- Products -->
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="total-products">...</p>
                        </div>
                        <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                            <!-- Pending Orders -->
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="pending-orders">...</p>
                        </div>
                        <div class="p-3 rounded-lg bg-amber-50 text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Registered Users</p>
                            <!-- Registered Users -->
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="registered-users">...</p>
                        </div>
                        <div class="p-3 rounded-lg bg-green-50 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Revenue (30d)</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="revenue">...</p>
                        </div>
                        <div class="p-3 rounded-lg bg-purple-50 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="/admin/products/create" class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:bg-indigo-50 transition-colors text-center">
                        <div class="text-indigo-700 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Add New Product</div>
                    </a>
                    
                    <a href="/admin/categories/create" class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:bg-indigo-50 transition-colors text-center">
                        <div class="text-indigo-700 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Create Category</div>
                    </a>
                    
                    <a href="/admin/orders" class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:bg-indigo-50 transition-colors text-center">
                        <div class="text-indigo-700 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Process Orders</div>
                    </a>
                    
                    <a href="/admin/users" class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:bg-indigo-50 transition-colors text-center">
                        <div class="text-indigo-700 mb-2">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="font-medium text-gray-900">Manage Users</div>
                    </a>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Orders (Last 30 Days)</h3>
                <div id="chartLoading" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600 mb-2"></div>
                    <p class="text-gray-500">Loading chart data...</p>
                </div>
                <canvas id="ordersChart" height="200" class="hidden"></canvas>
                <div id="chartError" class="text-center py-8 text-red-500 hidden"></div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('api_token');

        // Update count helpers
        function setStat(selector, value) {
            document.querySelector(selector).textContent = value;
        }

        // Products count
        axios.get('/api/admin/products/count', { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => setStat('#total-products', res.data.count));

        // Pending orders
        axios.get('/api/admin/orders/count?status=pending', { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => setStat('#pending-orders', res.data.count));

        // Registered users
        axios.get('/api/admin/users/count', { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => setStat('#registered-users', res.data.count));

        // Revenue from confirmed orders
        axios.get('/api/admin/orders/revenue', { headers: { 'Authorization': 'Bearer ' + token } })
            .then(res => setStat('#revenue', '$' + Number(res.data.revenue).toLocaleString()));
    });
</script>
@endsection