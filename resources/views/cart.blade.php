@extends('layouts.mylayout')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <!-- <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Shopping Cart</h1>
            <div class="w-24 h-1 bg-indigo-600 mx-auto mb-6"></div>
        </div> -->

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-8 mt-16">
            <!-- Cart Items -->
            <div class="lg:col-span-8">
                <div id="loading-state" class="text-center py-20">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600">Loading your cart...</p>
                </div>
                <div id="cart-list" class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-200"></div>
                <div id="empty-state" class="hidden text-center py-20">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm7 0a2 2 0 11-4 0 2 2 0 014 0zm-7-6a2 2 0 11-4 0 2 2 0 014 0zm7 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                    <p class="mt-1 text-gray-500">Start adding some products to your cart</p>
                    <div class="mt-6">
                        <a href="/products" class="btn-primary px-6 py-3 inline-flex items-center">
                            <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
                <div id="error-state" class="hidden text-center py-20">
                    <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Failed to load cart</h3>
                    <p class="mt-1 text-gray-500">We're having trouble loading your cart items.</p>
                    <button onclick="window.location.reload()" class="mt-6 btn-primary px-6 py-3">
                        Try Again
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-8 lg:mt-0 lg:col-span-4">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="summary-subtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium" id="summary-shipping">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium" id="summary-tax">Calculated at checkout</span>
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex justify-between">
                            <span class="text-lg font-medium text-gray-900">Total</span>
                            <span class="text-lg font-bold text-indigo-700" id="summary-total">$0.00</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="/checkout" class="btn-primary w-full text-center py-3 px-4">
                            Proceed to Checkout
                        </a>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        or <a href="/products" class="text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartList = document.getElementById('cart-list');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const errorState = document.getElementById('error-state');
        const subtotalEl = document.getElementById('summary-subtotal');
        const totalEl = document.getElementById('summary-total');
        const token = localStorage.getItem('api_token');

        // Load cart with Axios
        axios.get('/api/cart', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            loadingState.style.display = 'none';
            const data = response.data;
            const items = Array.isArray(data) ? data : (data.items ?? data.data?.items ?? []);
            
            if (!items.length) {
                emptyState.style.display = 'block';
                updateSummary(0);
                return;
            }

            let html = '';
            let subtotal = 0;

            items.forEach(item => {
                const product = item.product || {};
                const itemTotal = (product.price * item.quantity) || 0;
                subtotal += itemTotal;

                html += `
                <div class="p-6 flex flex-col sm:flex-row" data-item-id="${item.id}">
                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-6">
                        <img src="${product.image || 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80'}"
                            alt="${product.name || ''}"
                            class="w-32 h-32 object-cover rounded-lg">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">${product.name || ''}</h3>
                                <p class="text-gray-500 text-sm mt-1">${product.category?.name || ''}</p>
                            </div>
                            <p class="text-lg font-medium text-indigo-700">$${itemTotal.toFixed(2)}</p>
                        </div>
                        <div class="mt-4 flex items-center">
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})"
                                        class="px-3 py-1 hover:bg-gray-50 ${item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                                    -
                                </button>
                                <span class="px-4 py-1 border-x border-gray-300 text-center">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})"
                                        class="px-3 py-1 hover:bg-gray-50">
                                    +
                                </button>
                            </div>
                            <button onclick="removeItem(${item.id})"
                                    class="ml-4 text-red-500 hover:text-red-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
                `;
            });

            cartList.innerHTML = html;
            updateSummary(subtotal);
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            loadingState.style.display = 'none';
            errorState.style.display = 'block';
        });

        function updateSummary(subtotal) {
            subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
            totalEl.textContent = `$${subtotal.toFixed(2)}`;
        }

        // Update quantity function (using Axios, supports zero quantity as remove)
        window.updateQuantity = function(itemId, newQuantity) {
            if (newQuantity < 0) return;

            axios.put(`/api/cart/${itemId}`, { quantity: newQuantity }, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(() => location.reload())
            .catch(() => alert('Failed to update quantity'));
        };

        // Remove item function (also uses updateQuantity with 0 for consistency)
        window.removeItem = function(itemId) {
            if (confirm('Remove this item from your cart?')) {
                updateQuantity(itemId, 0); // Triggers your controller to remove the item
            }
        };
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
        .quantity-selector {
            border-radius: 9999px;
        }
    </style>
@endsection
