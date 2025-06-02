@extends('layouts.mylayout')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center p-8 bg-gradient-to-br from-white via-indigo-50 to-indigo-100">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-8">
                    <span class="text-xs tracking-[0.5em] text-indigo-600/80 font-medium">LUXURY EYEWEAR</span>
                </div>
                <h1 class="text-6xl md:text-8xl font-bold text-gray-900 mb-12">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-800 to-gray-900">LUXNEWYORK</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 mb-12 font-light max-w-2xl mx-auto leading-relaxed">
                    Where <span class="font-medium text-indigo-700">precision craftsmanship</span> meets visionary design.<br>
                    Iconic sunglasses for those who define the future.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <a href="/products" class="inline-flex items-center justify-center bg-gradient-to-r from-indigo-900 to-black text-white text-lg font-medium rounded-full px-10 py-4 shadow-sm hover:shadow-md transition-all duration-300">
                        Explore Collections
                    </a>
                    <a href="/new-arrivals" class="inline-flex items-center justify-center border-2 border-gray-300 text-gray-700 text-lg font-medium rounded-full px-10 py-4 hover:border-indigo-500 hover:text-indigo-700 transition-all duration-300">
                        Discover More
                    </a>
                </div>
            </div>
        </section>

        <!-- New Arrivals (Grid populated by JS) -->
        <section id="new-arrivals" class="text-left py-24 px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="mb-16">
                    <h2 class="text-6xl font-bold text-gray-900 mb-4">New Arrivals</h2>
                    <p class="text-gray-500 text-sm mt-2">Discover the latest in luxury eyewear.</p>
                </div>
                <div id="newArrivalsGrid" class="mx-auto max-w-7xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    <!-- Populated by JS -->
                </div>
            </div>
        </section>

        <!-- FAQ Section (HARDCODED) -->
        <section class="py-24 px-8 bg-gray-50">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-14">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
                </div>
                <div class="space-y-6">
                    <div class="border rounded-lg p-6 bg-white shadow-sm">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">What is LUXNEWYORK?</h3>
                        <p class="text-gray-700">LUXNEWYORK is a luxury eyewear brand offering precision-crafted sunglasses for the modern individual.</p>
                    </div>
                    <div class="border rounded-lg p-6 bg-white shadow-sm">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">How fast is shipping?</h3>
                        <p class="text-gray-700">We provide complimentary express delivery on all orders over $200 with real-time tracking worldwide.</p>
                    </div>
                    <div class="border rounded-lg p-6 bg-white shadow-sm">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Where are your products made?</h3>
                        <p class="text-gray-700">Our eyewear is handmade in Italy using premium acetate and optical-grade lenses.</p>
                    </div>
                    <div class="border rounded-lg p-6 bg-white shadow-sm">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Do you offer customer support?</h3>
                        <p class="text-gray-700">Yes! Enjoy 24/7 concierge support and virtual try-on consultations via our website.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter and Footer -->
        @livewire('newsletter-form')
    </div>
</div>

<!-- Axios & Script for New Arrivals -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    axios.get('/api/new-arrivals')
    .then(function(response) {
        const products = response.data.data || response.data;
        const grid = document.getElementById('newArrivalsGrid');
        grid.innerHTML = '';
        products.slice(0, 6).forEach(product => {
            const createdAt = new Date(product.created_at);
            const now = new Date();
            const daysDifference = Math.floor((now - createdAt) / (1000 * 60 * 60 * 24));
            const isNew = daysDifference <= 7;
            const isOutOfStock = product.stock <= 0;

            grid.innerHTML += `
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full ${isOutOfStock ? 'opacity-75' : ''}">
                    <!-- Image Section -->
                    <div class="relative aspect-w-1 aspect-h-1 bg-gray-50 overflow-hidden min-h-[180px]">
                        <button class="absolute top-3 left-3 flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 shadow hover:bg-indigo-600 transition group z-10" style="outline:none; border:none;">
                            <svg class="w-5 h-5 text-indigo-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                        <img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover hover:scale-105 transition duration-500 ${isOutOfStock ? 'grayscale' : ''}">
                        ${isNew ? `
                        <div class="absolute top-3 right-3 bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                            NEW
                        </div>
                        ` : ''}
                        ${isOutOfStock ? `
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <span class="bg-white text-red-600 font-bold px-3 py-1 rounded-lg shadow text-xs">OUT OF STOCK</span>
                        </div>
                        ` : ''}
                    </div>
                    <!-- Content Section -->
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-base font-semibold text-gray-900 line-clamp-1">${product.name}</h3>
                            <div class="font-semibold ${isOutOfStock ? 'text-gray-400' : 'text-indigo-600'} text-sm ml-2">$${product.price}</div>
                        </div>
                        <p class="text-gray-500 text-xs mb-3 line-clamp-2 flex-grow">
                            ${product.description || 'Premium luxury eyewear with timeless design'}
                        </p>
                        <!-- Stock Status -->
                        <div class="mb-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium ${isOutOfStock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                                ${isOutOfStock ? 'Out of Stock' : `${product.stock} in stock`}
                            </span>
                        </div>
                        <!-- CTA Button -->
                        <a href="/products/${product.id}" 
                            class="w-full flex justify-center items-center bg-indigo-600 text-white px-6 py-2 rounded-lg shadow-md font-semibold text-sm transition
                            ${isOutOfStock 
                                ? 'opacity-60 pointer-events-none cursor-not-allowed text-gray-300 bg-gray-200 shadow-none' 
                                : 'hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none'}">
                            ${isOutOfStock ? 'Unavailable' : 'View details'}
                        </a>
                    </div>
                </div>`;
        });
    })
    .catch(function(error) {
        document.getElementById('newArrivalsGrid').innerHTML = '<p class="text-red-600">Failed to load new arrivals.</p>';
    });
});

</script>
@endsection
