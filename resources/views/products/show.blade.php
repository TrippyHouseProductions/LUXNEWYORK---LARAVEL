@extends('layouts.mylayout')

@section('content')
<div id="product-details"></div>
<div id="related-products" class="hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get product ID from URL
    const pathParts = window.location.pathname.split('/');
    const productId = pathParts[pathParts.length - 1] || pathParts[pathParts.length - 2];

    // DOM elements
    const productDetails = document.getElementById('product-details');
    const relatedProducts = document.getElementById('related-products');

    // Fetch main product
    axios.get(`/api/products/${productId}`, { headers: { 'Accept': 'application/json' } })
    .then(function(response) {
        const product = response.data.data ?? response.data;

        productDetails.innerHTML = `
        <main class="w-full bg-gray-50 min-h-screen">
            <!-- Product Hero Section -->
            <section class="py-12 md:py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                            <!-- Product Image -->
                            <div class="bg-gray-100 aspect-square rounded-xl overflow-hidden">
                                <img src="${product.image || 'https://placehold.co/800x800?text=No+Image'}" 
                                     alt="${product.name}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex flex-col">
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">${product.name}</h1>
                                
                                ${product.category ? `<div class="mb-4">
                                    <span class="inline-block bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium">
                                        ${product.category.name}
                                    </span>
                                </div>` : ''}
                                
                                <div class="mb-6">
                                    <span class="text-2xl font-bold text-indigo-700">$${product.price}</span>
                                </div>
                                
                                <div class="mb-8">
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Description</h3>
                                    <p class="text-gray-600">${product.description || 'No description available.'}</p>
                                </div>
                                
                                <!-- Add to Cart Section -->
                                <div class="mt-auto">
                                    <div class="flex items-center space-x-4">
                                        <!-- Quantity Selector -->
                                        <div class="flex items-center border border-gray-200 rounded-full px-4 py-2 bg-white">
                                            <button type="button" class="text-gray-500 hover:bg-indigo-50 px-2 rounded transition" onclick="decrementQuantity()">-</button>
                                            <input type="number" id="quantity" value="1" min="1" 
                                                class="w-12 text-center border-0 focus:ring-0 bg-transparent">
                                            <button type="button" class="text-gray-500 hover:bg-indigo-50 px-2 rounded transition" onclick="incrementQuantity()">+</button>
                                        </div>
                                        
                                        <!-- Add to Cart Button -->
                                        <button onclick="addToCart(${product.id})"
                                                class="text-white bg-indigo-600 hover:bg-indigo-700 px-8 py-3 rounded-full transition flex items-center font-medium shadow-sm"
                                                id="add-to-cart-btn">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </div>
                                    <div id="cart-message" class="mt-2 text-sm hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        `;

        // Fetch related products
        fetchRelatedProducts(productId);
        })
        .catch(function() {
            productDetails.innerHTML = `
            <div class="bg-gray-50 min-h-screen flex items-center justify-center">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 max-w-md text-center">
                    <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Product Not Found</h3>
                    <p class="mt-2 text-gray-500">We couldn't find the product you're looking for.</p>
                    <a href="/products" class="mt-6 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Browse Products
                    </a>
                </div>
            </div>
            `;
        });

        // Fetch related products
        function fetchRelatedProducts(currentProductId) {
            axios.get('/api/products', { headers: { 'Accept': 'application/json' } })
            .then(function(response) {
                let products = response.data.data ?? response.data;
                products = products.filter(p => p.id != currentProductId && p.stock > 0)
                            .sort(() => 0.5 - Math.random())
                            .slice(0, 4);

                if (products.length > 0) {
                    let html = `
                    <section class="py-12 bg-gray-50">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-8">You May Also Like</h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">`;

                    products.forEach(product => {
                        html += `
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <a href="/products/${product.id}" class="block">
                                <div class="bg-gray-100 aspect-square overflow-hidden">
                                    <img src="${product.image || 'https://placehold.co/600x600?text=No+Image'}" 
                                        alt="${product.name}" 
                                        class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">${product.name}</h3>
                                    <p class="text-indigo-700 font-bold mt-2">$${product.price}</p>
                                </div>
                            </a>
                        </div>
                        `;
                    });

                    html += `</div></div></section>`;
                    relatedProducts.innerHTML = html;
                    relatedProducts.classList.remove('hidden');
                }
            });
        }
    });

    // Quantity functions
    function incrementQuantity() {
        const qtyInput = document.getElementById('quantity');
        qtyInput.value = parseInt(qtyInput.value) + 1;
    }

    function decrementQuantity() {
        const qtyInput = document.getElementById('quantity');
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
        }
    }

    // Add to cart function
    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;
        const token = localStorage.getItem('api_token');
        axios.post('/api/cart', {
            product_id: productId,
            quantity: quantity
        }, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        }).then(response => {
            const msg = document.getElementById('cart-message');
            msg.classList.remove('hidden');
            msg.classList.remove('text-red-600');
            msg.classList.add('text-green-600');
            msg.innerText = 'Added to cart!';
        }).catch(error => {
            const msg = document.getElementById('cart-message');
            msg.classList.remove('hidden');
            msg.classList.remove('text-green-600');
            msg.classList.add('text-red-600');
            msg.innerText = error.response?.data?.message || 'Something went wrong!';
        });
    }
</script>

<style>
/* Consistent styling with admin dashboard */
#product-details {
    transition: all 0.2s ease-in-out;
}

/* Quantity input styling */
#quantity {
    -moz-appearance: textfield;
}
#quantity::-webkit-outer-spin-button,
#quantity::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    
    #add-to-cart-btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
    }
}
</style>
@endsection