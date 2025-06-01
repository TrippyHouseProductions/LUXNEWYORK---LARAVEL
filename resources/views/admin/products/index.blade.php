@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Product Management</h2>
                <div class="w-24 h-1 bg-indigo-600"></div>
            </div>

            <!-- Add Product Button -->
            <div class="mb-8">
                <button onclick="openAddProductModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Product
                </button>
            </div>

            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700 inline-block mb-4">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading products...</p>
            </div>
            <div id="admin-product-list" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"></div>
            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-red-50 text-red-600 inline-block mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load products</h3>
                <p class="text-gray-500 mb-4">We're having trouble loading product data. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Product Modals (Add/Edit) -->
<div id="product-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-8 relative">
        <button type="button" onclick="closeProductModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <h3 id="product-modal-title" class="text-2xl font-bold mb-4">Add Product</h3>
        <form id="product-modal-form">
            <input type="hidden" id="product-id">
            <div class="mb-4">
                <label class="block mb-2 font-medium">Name</label>
                <input type="text" id="product-name" class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-200" maxlength="255" required>
                <div id="product-error-name" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Category</label>
                <select id="product-category" class="w-full border px-3 py-2 rounded" required>
                    <option value="">Select Category</option>
                </select>
                <div id="product-error-category" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">SKU</label>
                <input type="text" id="product-sku" class="w-full border px-3 py-2 rounded" maxlength="255" required>
                <div id="product-error-sku" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Price ($)</label>
                <input type="number" min="0" step="0.01" id="product-price" class="w-full border px-3 py-2 rounded" required>
                <div id="product-error-price" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Stock</label>
                <input type="number" min="0" id="product-stock" class="w-full border px-3 py-2 rounded" required>
                <div id="product-error-stock" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Image URL</label>
                <input type="text" id="product-image" class="w-full border px-3 py-2 rounded" placeholder="https://...">
                <div id="product-error-image" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Description</label>
                <textarea id="product-description" class="w-full border px-3 py-2 rounded" maxlength="500" rows="3" required></textarea>
                <div id="product-error-description" class="text-red-600 text-sm mt-1"></div>
            </div>
            <button type="submit" id="product-modal-submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 w-full">
                Save Product
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-product-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-8 text-center relative">
        <button type="button" onclick="closeDeleteProductModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Product?</h3>
        <p class="text-gray-500 mb-4">Are you sure you want to delete this product? This action cannot be undone.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteProductModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="delete-product-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

<script>
// --- Pagination Helper (your provided class, globally available) ---
class PaginationHelper {
    constructor(loadFunction, containerId, options = {}) {
        this.loadFunction = loadFunction;
        this.containerId = containerId;
        this.currentPage = 1;
        this.totalPages = 1;
        this.perPage = options.perPage || 10;
        this.totalItems = 0;
        this.maxVisiblePages = options.maxVisiblePages || 5;
    }

    updateState(responseData) {
        this.totalItems = responseData.total || 0;
        this.totalPages = responseData.last_page || Math.ceil(this.totalItems / this.perPage);
        this.currentPage = responseData.current_page || this.currentPage;
    }

    renderPagination() {
        const container = document.getElementById(this.containerId);
        if (!container) return;

        const paginationHtml = `
            <div class="pagination-container bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-xl">
                <div class="flex-1 flex justify-between sm:hidden">
                    ${this.currentPage > 1 ? `
                    <button onclick="productPagination.loadPrevious()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    ` : ''}
                    ${this.currentPage < this.totalPages ? `
                    <button onclick="productPagination.loadNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </button>
                    ` : ''}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">${((this.currentPage - 1) * this.perPage) + 1}</span> to 
                            <span class="font-medium">${Math.min(this.currentPage * this.perPage, this.totalItems)}</span> of 
                            <span class="font-medium">${this.totalItems}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            ${this.generatePageLinks()}
                        </nav>
                    </div>
                </div>
            </div>
        `;

        // Insert or update pagination
        const existingPagination = container.querySelector('.pagination-container');
        if (existingPagination) {
            existingPagination.outerHTML = paginationHtml;
        } else {
            container.insertAdjacentHTML('beforeend', paginationHtml);
        }
    }

    generatePageLinks() {
        let links = '';
        let startPage, endPage;

        if (this.totalPages <= this.maxVisiblePages) {
            startPage = 1;
            endPage = this.totalPages;
        } else {
            if (this.currentPage <= Math.ceil(this.maxVisiblePages / 2)) {
                startPage = 1;
                endPage = this.maxVisiblePages;
            } else if (this.currentPage + Math.floor(this.maxVisiblePages / 2) >= this.totalPages) {
                startPage = this.totalPages - this.maxVisiblePages + 1;
                endPage = this.totalPages;
            } else {
                startPage = this.currentPage - Math.floor(this.maxVisiblePages / 2);
                endPage = this.currentPage + Math.floor(this.maxVisiblePages / 2);
            }
        }

        // Previous button
        if (this.currentPage > 1) {
            links += `
                <button onclick="productPagination.loadPrevious()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
        }

        // First page and ellipsis
        if (startPage > 1) {
            links += `
                <button onclick="productPagination.loadPage(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    1
                </button>
                ${startPage > 2 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
            `;
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            links += `
                <button onclick="productPagination.loadPage(${i})" class="${i === this.currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    ${i}
                </button>
            `;
        }

        // Last page and ellipsis
        if (endPage < this.totalPages) {
            links += `
                ${endPage < this.totalPages - 1 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
                <button onclick="productPagination.loadPage(${this.totalPages})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ${this.totalPages}
                </button>
            `;
        }

        // Next button
        if (this.currentPage < this.totalPages) {
            links += `
                <button onclick="productPagination.loadNext()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
        }

        return links;
    }

    loadPage(page) {
        this.currentPage = page;
        this.loadFunction(page);
    }

    loadNext() {
        if (this.currentPage < this.totalPages) {
            this.loadPage(this.currentPage + 1);
        }
    }

    loadPrevious() {
        if (this.currentPage > 1) {
            this.loadPage(this.currentPage - 1);
        }
    }
}

// --- Page Script ---
let editingProductId = null;
let deletingProductId = null;
let categoriesCache = [];
let productPagination; // Will be initialized on DOMContentLoaded

document.addEventListener('DOMContentLoaded', function() {
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const productList = document.getElementById('admin-product-list');
    const token = localStorage.getItem('api_token');

    // Initialize PaginationHelper
    productPagination = new PaginationHelper(
        loadProducts,
        'admin-product-list',
        { perPage: 6, maxVisiblePages: 5 }
    );

    function loadCategoriesForSelect() {
        // Loads all categories
        return axios.get('/api/categories', { headers: { 'Accept': 'application/json' } })
        .then(res => {
            categoriesCache = res.data.data || [];
            const select = document.getElementById('product-category');
            select.innerHTML = '<option value="">Select Category</option>';
            categoriesCache.forEach(cat => {
                select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            });
        });
    }

    function loadProducts(page = 1) {
        loadingState.style.display = 'block';
        errorState.classList.add('hidden');
        productList.innerHTML = '';

        axios.get(`/api/admin/products?page=${page}&per_page=${productPagination.perPage}`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            loadingState.style.display = 'none';
            const data = res.data;
            const products = data.data || [];

            // Update pagination state from response
            productPagination.updateState(data);

            if (!products.length) {
                productList.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="p-3 rounded-lg bg-gray-50 text-gray-500 inline-block mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No products found</h3>
                        <p class="text-gray-500">There are currently no products in the system.</p>
                    </div>
                `;
                productPagination.renderPagination();
                return;
            }
            renderProductTable(products);
            productPagination.renderPagination();
        })
        .catch(error => {
            console.error('Error loading products:', error);
            loadingState.style.display = 'none';
            errorState.classList.remove('hidden');
        });
    }

    function renderProductTable(products) {
        let html = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;
        products.forEach(product => {
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${product.image ? `
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-md object-cover" src="${product.image}" alt="${product.name}">
                            </div>
                            ` : `
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                ${product.name.charAt(0).toUpperCase()}
                            </div>
                            `}
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                                <div class="text-sm text-gray-500">${product.sku || 'No SKU'}</div>
                                <div class="text-xs text-gray-400 mt-1">${product.description ? product.description.substring(0, 60) + (product.description.length > 60 ? '...' : '') : ''}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${product.category ? product.category.name : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        $${product.price}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${product.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${product.stock} in stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" onclick="openEditProductModal(${product.id})" class="text-indigo-600 hover:text-indigo-900 transition-colors mr-4">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button" onclick="openDeleteProductModal(${product.id})" class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
        productList.innerHTML = html;
    }

    // --- The rest (modal and CRUD) logic below is unchanged ---

    // Add Product
    window.openAddProductModal = function() {
        editingProductId = null;
        document.getElementById('product-modal-title').textContent = 'Add Product';
        document.getElementById('product-modal-submit').textContent = 'Add Product';
        document.getElementById('product-id').value = '';
        document.getElementById('product-name').value = '';
        document.getElementById('product-category').value = '';
        document.getElementById('product-sku').value = '';
        document.getElementById('product-price').value = '';
        document.getElementById('product-stock').value = '';
        document.getElementById('product-image').value = '';
        document.getElementById('product-description').value = '';
        clearProductErrors();
        loadCategoriesForSelect().then(() => {
            document.getElementById('product-modal-backdrop').classList.remove('hidden');
        });
    };

    // Edit Product
    window.openEditProductModal = function(productId) {
        // Fetch product details via API or reuse from the current table as you wish
        axios.get(`/api/admin/products/${productId}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).then(res => {
            const product = res.data.data || {};
            editingProductId = product.id;
            document.getElementById('product-modal-title').textContent = 'Edit Product';
            document.getElementById('product-modal-submit').textContent = 'Update Product';
            document.getElementById('product-id').value = product.id;
            document.getElementById('product-name').value = product.name;
            document.getElementById('product-category').value = product.category_id || (product.category ? product.category.id : '');
            document.getElementById('product-sku').value = product.sku || '';
            document.getElementById('product-price').value = product.price;
            document.getElementById('product-stock').value = product.stock;
            document.getElementById('product-image').value = product.image || '';
            document.getElementById('product-description').value = product.description || '';
            clearProductErrors();
            loadCategoriesForSelect().then(() => {
                document.getElementById('product-modal-backdrop').classList.remove('hidden');
                document.getElementById('product-category').value = product.category_id || (product.category ? product.category.id : '');
            });
        });
    };

    // Close Product Modal
    window.closeProductModal = function() {
        document.getElementById('product-modal-backdrop').classList.add('hidden');
    };

    // Handle Product Form Submit
    document.getElementById('product-modal-form').onsubmit = function(e) {
        e.preventDefault();
        clearProductErrors();
        const id = document.getElementById('product-id').value;
        const name = document.getElementById('product-name').value.trim();
        const category_id = document.getElementById('product-category').value;
        const sku = document.getElementById('product-sku').value.trim();
        const price = document.getElementById('product-price').value;
        const stock = document.getElementById('product-stock').value;
        const image = document.getElementById('product-image').value.trim();
        const description = document.getElementById('product-description').value.trim();

        // Simple frontend validation
        let hasError = false;
        if (!name) { setError('name', 'Name is required.'); hasError = true; }
        if (!category_id) { setError('category', 'Category is required.'); hasError = true; }
        if (!sku) { setError('sku', 'SKU is required.'); hasError = true; }
        if (!price || price < 0) { setError('price', 'Price is required and must be non-negative.'); hasError = true; }
        if (!stock || stock < 0) { setError('stock', 'Stock is required and must be non-negative.'); hasError = true; }
        if (description.length > 500) { setError('description', 'Description must be less than 500 characters.'); hasError = true; }
        if (hasError) return;

        document.getElementById('product-modal-submit').disabled = true;

        const url = id ? `/api/admin/products/${id}` : '/api/admin/products';
        const method = id ? 'put' : 'post';
        axios({
            url: url,
            method: method,
            data: { name, category_id, sku, price, stock, image, description },
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).then(() => {
            closeProductModal();
            productPagination.loadPage(productPagination.currentPage); // reload current page
        }).catch(e => {
            if (e.response && e.response.data && e.response.data.errors) {
                for (const key in e.response.data.errors) {
                    setError(key, e.response.data.errors[key][0]);
                }
            } else if (e.response && e.response.data && e.response.data.message) {
                alert(e.response.data.message);
            } else {
                alert('Failed to save product.');
            }
        }).finally(() => {
            document.getElementById('product-modal-submit').disabled = false;
        });
    };

    function setError(key, msg) {
        document.getElementById('product-error-' + key).textContent = msg;
    }
    function clearProductErrors() {
        ['name', 'category', 'sku', 'price', 'stock', 'image', 'description'].forEach(k => setError(k, ''));
    }

    // Delete Modal logic
    window.openDeleteProductModal = function(productId) {
        deletingProductId = productId;
        document.getElementById('delete-product-modal-backdrop').classList.remove('hidden');
    };
    window.closeDeleteProductModal = function() {
        deletingProductId = null;
        document.getElementById('delete-product-modal-backdrop').classList.add('hidden');
    };
    document.getElementById('delete-product-modal-confirm').onclick = function() {
        if (!deletingProductId) return;
        axios.delete(`/api/admin/products/${deletingProductId}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).then(() => {
            closeDeleteProductModal();
            productPagination.loadPage(productPagination.currentPage);
        }).catch(() => {
            alert('Delete failed. Please try again.');
        });
    };

    // Initial load
    productPagination.loadPage(1);
});

</script>
@endsection
