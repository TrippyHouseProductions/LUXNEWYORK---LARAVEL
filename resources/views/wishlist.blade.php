@extends('layouts.mylayout')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Loading State -->
        <div id="loading-state" class="text-center py-20">
            <i class="fas fa-spinner fa-spin text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600">Loading your wishlist...</p>
        </div>

        <!-- Wishlist Items -->
        <div id="wishlist-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

        <!-- Empty State (hidden initially) -->
        <div id="empty-state" class="hidden text-center py-20">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Your wishlist is empty</h3>
            <p class="mt-1 text-gray-500">Start saving your favorite items for later</p>
            <div class="mt-6">
                <a href="/products" class="btn-primary px-6 py-3 inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i> Browse Products
                </a>
            </div>
        </div>

        <!-- Error State (hidden initially) -->
        <div id="error-state" class="hidden text-center py-20">
            <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Failed to load wishlist</h3>
            <p class="mt-1 text-gray-500">We're having trouble loading your saved items.</p>
            <button onclick="window.location.reload()" class="mt-6 btn-primary px-6 py-3">
                Try Again
            </button>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const wishlistList = document.getElementById('wishlist-list');
        const loadingState = document.getElementById('loading-state');
        const emptyState = document.getElementById('empty-state');
        const errorState = document.getElementById('error-state');
        const token = localStorage.getItem('api_token');

        // Load Wishlist (with axios)
        axios.get('/api/wishlist', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            loadingState.style.display = 'none';
            let data = res.data;
            let items = [];
            if (Array.isArray(data)) items = data;
            else if (Array.isArray(data.data)) items = data.data;
            else if (Array.isArray(data.items)) items = data.items;

            if (!items.length) {
                emptyState.style.display = 'block';
                return;
            }

            let html = '';
            items.forEach(item => {
                const product = item.product || item;
                html += `
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative">
                        <img src="${product.image || 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80'}" 
                             alt="${product.name}" 
                             class="w-full h-48 object-cover">
                        <button onclick="removeFromWishlist(${item.id || product.id})" 
                                class="absolute top-3 right-3 bg-white p-2 rounded-full shadow-md text-red-500 hover:bg-red-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-1">${product.name}</h3>
                        <p class="text-indigo-700 font-bold mb-3">$${product.price}</p>
                        <div class="flex justify-between items-center">
                            <a href="/products/${product.id}" 
                               class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                                View Product
                            </a>
                            <button onclick="addToCart(${product.id})" 
                                    class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                `;
            });

            wishlistList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading wishlist:', error);
            loadingState.style.display = 'none';
            errorState.style.display = 'block';
        });

        // Remove from wishlist
        window.removeFromWishlist = function(itemId) {
            if (confirm('Remove this item from your wishlist?')) {
                axios.delete(`/api/wishlist/${itemId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(() => {
                    location.reload();
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    alert('An error occurred');
                });
            }
        };

        // Add to cart
        window.addToCart = function(productId) {
            axios.post('/api/cart', {
                product_id: productId,
                quantity: 1
            }, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                alert('Item added to cart!');
            })
            .catch(error => {
                alert('Failed to add to cart');
            });
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
    </style>
@endsection
