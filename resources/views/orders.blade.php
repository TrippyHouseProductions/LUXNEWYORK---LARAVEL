@extends('layouts.mylayout')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
            <div class="w-24 h-1 bg-indigo-600 mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto">View your order history and track current shipments</p>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="text-center py-20">
            <i class="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600">Loading your orders...</p>
        </div>

        <!-- Orders List -->
        <div id="orders-list" class="space-y-6"></div>

        <!-- Empty State (hidden initially) -->
        <div id="empty-state" class="hidden text-center py-20">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
            <p class="mt-1 text-gray-500">You haven't placed any orders yet.</p>
            <div class="mt-6">
                <a href="/products" class="btn-primary px-6 py-3 inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i> Start Shopping
                </a>
            </div>
        </div>

        <!-- Error State (hidden initially) -->
        <div id="error-state" class="hidden text-center py-20">
            <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Failed to load orders</h3>
            <p class="mt-1 text-gray-500">We're having trouble loading your order history.</p>
            <button onclick="window.location.reload()" class="mt-6 btn-primary px-6 py-3">
                Try Again
            </button>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ordersList = document.getElementById('orders-list');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const errorState = document.getElementById('error-state');

        const token = localStorage.getItem('api_token');
        
        fetch('/api/orders', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Failed to fetch orders');
            return res.json();
        })
        .then(data => {
            loadingState.style.display = 'none';
            
            const orders = Array.isArray(data) ? data : (data.data ?? []);
            
            if (!orders.length) {
                emptyState.style.display = 'block';
                return;
            }

            let html = '';
            orders.forEach(order => {
                // Status styling
                let statusClass = 'bg-gray-100 text-gray-800';
                if (order.status.toLowerCase() === 'completed') statusClass = 'bg-green-100 text-green-800';
                else if (order.status.toLowerCase() === 'processing') statusClass = 'bg-blue-100 text-blue-800';
                else if (order.status.toLowerCase() === 'shipped') statusClass = 'bg-purple-100 text-purple-800';
                else if (order.status.toLowerCase() === 'canceled') statusClass = 'bg-red-100 text-red-800';

                // Format date
                const orderDate = new Date(order.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                html += `
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Order #${order.id}</h3>
                                <p class="text-sm text-gray-500 mt-1">Placed on ${orderDate}</p>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                    ${order.status}
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                    <p class="text-xl font-bold text-indigo-700">$${order.total}</p>
                                </div>
                                <div>
                                    <a href="/orders/${order.id}" class="btn-secondary inline-flex items-center px-4 py-2">
                                        <i class="fas fa-eye mr-2"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });
            
            ordersList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            loadingState.style.display = 'none';
            errorState.style.display = 'block';
        });
    });
    </script>

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

        .btn-secondary {
            position: relative;
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
            overflow: hidden;
            z-index: 1;
            border-radius: 9999px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: rgba(32, 33, 36, 0.08);
        }
    </style>
@endsection