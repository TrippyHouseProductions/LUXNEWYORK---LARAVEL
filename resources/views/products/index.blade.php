@extends('layouts.mylayout')

@section('content')
<div class="flex bg-gray-50">
    <!-- Main content -->
    <div class="flex-1 overflow-auto mt-20">
        <div class="p-12">
            <!-- Page Header with more space -->
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Collections</h2>
                <div class="w-32 h-1 bg-indigo-600 mx-auto mb-8"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Discover luxury eyewear crafted with precision and timeless design</p>
            </div>
            <!-- Search and Filter Controls with more padding -->
            <div class="mb-16 flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="relative w-full sm:w-80">
                    <input type="text" id="search-input" placeholder="Search products..." 
                           class="w-full pl-12 pr-6 py-3 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex gap-4 w-full sm:w-auto">
                    <select id="category-filter" class="border border-gray-300 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                        <option value="">All Categories</option>
                        <!-- Categories will be loaded here by JS -->
                    </select>
                    <select id="sort-by" class="border border-gray-300 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                        <option value="newest">Newest</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Loading State with more space -->
            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-16 shadow-sm text-center my-16">
                <div class="p-4 rounded-xl bg-indigo-50 text-indigo-700 inline-block mb-6">
                    <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-xl text-gray-600">Loading our collections...</p>
            </div>

            <!-- Product Grid with more spacing -->
            <div id="product-list" class="mx-auto max-w-7xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10"></div>

            <!-- Empty State with more space -->
            <div id="empty-state" class="hidden bg-white border border-gray-200 rounded-xl p-16 shadow-sm text-center my-16">
                <div class="p-4 rounded-xl bg-gray-50 text-gray-500 inline-block mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">No products found</h3>
                <p class="text-lg text-gray-500 mb-8">We couldn't find any matching products.</p>
                <a href="/" class="px-8 py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors inline-block text-lg">
                    Browse All Products
                </a>
            </div>

            <!-- Error State with more space -->
            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-16 shadow-sm text-center my-16">
                <div class="p-4 rounded-xl bg-red-50 text-red-600 inline-block mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-4">Failed to load products</h3>
                <p class="text-lg text-gray-500 mb-8">We're having trouble loading our collections. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-8 py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-lg">
                    Retry
                </button>
            </div>

            <!-- Load More Button with more space -->
            <div id="load-more-container" class="mt-20 text-center hidden">
                <button id="load-more-btn" class="px-10 py-4 border-2 border-indigo-600 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-colors font-medium text-lg">
                    Load More
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productList = document.getElementById('product-list');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const errorState = document.getElementById('error-state');
    const searchInput = document.getElementById('search-input');
    const categorySelect = document.getElementById('category-filter');
    const sortSelect = document.getElementById('sort-by');
    const loadMoreContainer = document.getElementById('load-more-container');
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    let products = [];
    let displayedCount = 0;
    const productsPerLoad = 9;

    // Load categories from API
    axios.get('/api/categories', { headers: { 'Accept': 'application/json' } })
        .then(function(response) {
            const cats = response.data.data || response.data;
            cats.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = cat.name;
                categorySelect.appendChild(opt);
            });
        });

    // Load all products from API
    function fetchProducts() {
        loadingState.style.display = 'block';
        emptyState.style.display = 'none';
        errorState.style.display = 'none';
        productList.innerHTML = '';
        loadMoreContainer.style.display = 'none';

        axios.get('/api/products', { headers: { 'Accept': 'application/json' } })
            .then(function (response) {
                loadingState.style.display = 'none';
                products = response.data.data || response.data;
                displayedCount = 0;
                renderProducts();
            })
            .catch(function (error) {
                loadingState.style.display = 'none';
                errorState.style.display = 'block';
                productList.innerHTML = '';
                loadMoreContainer.style.display = 'none';
            });
    }

    // Render the filtered product grid
    function renderProducts(loadMore = false) {
        let filtered = [...products];

        // Filter by category
        const selectedCategory = categorySelect.value;
        if (selectedCategory) {
            filtered = filtered.filter(p => p.category && String(p.category.id) === String(selectedCategory));
        }

        // Filter by search text
        const searchTerm = searchInput.value.trim().toLowerCase();
        if (searchTerm) {
            filtered = filtered.filter(p =>
                p.name.toLowerCase().includes(searchTerm) ||
                (p.description && p.description.toLowerCase().includes(searchTerm))
            );
        }

        // Sort products
        switch (sortSelect.value) {
            case 'price-low':
                filtered.sort((a, b) => a.price - b.price);
                break;
            case 'price-high':
                filtered.sort((a, b) => b.price - a.price);
                break;
            // newest: keep as is
        }

        if (!loadMore) {
            displayedCount = 0;
            productList.innerHTML = '';
        }

        // Determine how many products to show
        const productsToShow = filtered.slice(displayedCount, displayedCount + productsPerLoad);
        displayedCount += productsToShow.length;

        // Render products
        let html = '';
        productsToShow.forEach(product => {
            const createdAt = new Date(product.created_at);
            const now = new Date();
            const daysDifference = Math.floor((now - createdAt) / (1000 * 60 * 60 * 24));
            const isNew = daysDifference <= 7;
            const isOutOfStock = product.stock <= 0;
            
            html += `
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
                            class="w-full flex justify-center items-center bg-indigo-600 text-white px-4 py-1.5 rounded-lg shadow-md font-semibold text-sm transition
                            ${isOutOfStock 
                                ? 'opacity-60 pointer-events-none cursor-not-allowed text-gray-300 bg-gray-200 shadow-none' 
                                : 'hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none'}">
                            ${isOutOfStock ? 'Unavailable' : 'View details'}
                        </a>
                    </div>
                </div>`;
        });
        productList.insertAdjacentHTML('beforeend', html);

        // Show/hide load more button
        if (filtered.length > displayedCount) {
            loadMoreContainer.style.display = 'block';
        } else {
            loadMoreContainer.style.display = 'none';
        }

        // Show empty state if no products
        if (filtered.length === 0 && !loadMore) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Event listeners
    searchInput.addEventListener('input', () => renderProducts());
    categorySelect.addEventListener('change', () => renderProducts());
    sortSelect.addEventListener('change', () => renderProducts());
    loadMoreBtn.addEventListener('click', () => renderProducts(true));

    // Initial load
    fetchProducts();
});
</script>
@endsection