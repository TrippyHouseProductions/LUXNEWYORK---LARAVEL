@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Category Management</h2>
                <div class="w-24 h-1 bg-indigo-600"></div>
            </div>

            <!-- Add Category Button -->
            <div class="mb-8">
                <button onclick="openAddCategoryModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Category
                </button>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700 inline-block mb-4">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading categories...</p>
            </div>

            <!-- Category Table -->
            <div id="admin-category-list" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"></div>

            <!-- Error State (hidden initially) -->
            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-red-50 text-red-600 inline-block mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load categories</h3>
                <p class="text-gray-500 mb-4">We're having trouble loading category data. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div id="category-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8 relative">
        <button type="button" onclick="closeCategoryModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <h3 id="category-modal-title" class="text-2xl font-bold mb-4">Add Category</h3>
        <form id="category-modal-form">
            <input type="hidden" id="category-id">
            <label class="block mb-4">
                <span class="block mb-2 font-medium">Name</span>
                <input type="text" id="category-name" class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-200" required>
                <div id="category-error-name" class="text-red-600 text-sm mt-1"></div>
            </label>
            <div class="flex items-center gap-3 mb-4">
                <input type="checkbox" id="category-is-active" class="rounded border-gray-300">
                <label for="category-is-active" class="text-gray-700">Active</label>
            </div>
            <button type="submit" id="category-modal-submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 w-full">
                Save Category
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-8 text-center relative">
        <button type="button" onclick="closeDeleteModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Category?</h3>
        <p class="text-gray-500 mb-4">Are you sure you want to delete this category? This action cannot be undone.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="delete-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@vite('resources/js/app.js')
<script>
let editingCategoryId = null;
let deletingCategoryId = null;
let productsList = []; // Store all products for category count

document.addEventListener('DOMContentLoaded', function() {
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const categoryList = document.getElementById('admin-category-list');
    const token = localStorage.getItem('api_token');
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 6;
    let totalCategories = 0;

    // Load all products ONCE (to count per category)
    function loadProductsList(callback) {
        axios.get('/api/products', {
            headers: { 'Accept': 'application/json' }
        }).then(res => {
            productsList = res.data.data || [];
            callback && callback();
        });
    }

    // Load categories function with pagination
    window.loadCategories = function(page = 1) {
        currentPage = page;
        loadingState.style.display = 'block';
        errorState.classList.add('hidden');
        categoryList.innerHTML = '';

        axios.get(`/api/admin/categories?page=${page}&per_page=${perPage}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(res => {
            loadingState.style.display = 'none';
            const data = res.data;
            const categories = data.data || [];
            totalCategories = data.total || categories.length;
            totalPages = data.last_page || Math.ceil(totalCategories / perPage);
            currentPage = data.current_page || currentPage;

            if (!categories.length) {
                categoryList.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="p-3 rounded-lg bg-gray-50 text-gray-500 inline-block mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No categories found</h3>
                        <p class="text-gray-500">There are currently no categories in the system.</p>
                    </div>
                `;
                return;
            }

            renderCategoryTable(categories);
            renderPagination();
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            loadingState.style.display = 'none';
            errorState.classList.remove('hidden');
        });
    };

    // Render category table
    function renderCategoryTable(categories) {
        let html = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;

        categories.forEach(category => {
            // Count products with this category_id
            // NOTE no need of using this bcz in the category controller and class we count the products with that category
            // const count = productsList.filter(p => p.category_id === category.id).length;
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                ${category.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${category.name}</div>
                                <div class="text-sm text-gray-500">${category.slug || ''}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${category.products_count} products
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${category.products_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${category.products_count > 0 ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button"
                            onclick="openEditCategoryModal({ id: ${category.id}, name: \`${category.name.replace(/`/g, '\\`')}\`, is_active: ${category.is_active ? 1 : 0} })"
                            class="text-indigo-600 hover:text-indigo-900 transition-colors mr-4">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button"
                            onclick="openDeleteModal(${category.id})"
                            class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;
        categoryList.innerHTML = html;
    }

    // // Render pagination controls (same as your current code, not repeated here)
    // function renderPagination() {
    //     // ... (copy from your working code)
    //     // unchanged from your version, see earlier
    //     // (no fix needed here)
    //     // ...
    //     // (keep your original renderPagination and generatePageLinks functions)
    // }
    function renderPagination() {
        const paginationHtml = `
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-xl">
                <div class="flex-1 flex justify-between sm:hidden">
                    ${currentPage > 1 ? `
                    <button onclick="loadCategories(${currentPage - 1})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    ` : ''}
                    ${currentPage < totalPages ? `
                    <button onclick="loadCategories(${currentPage + 1})" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </button>
                    ` : ''}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">${((currentPage - 1) * perPage) + 1}</span> to 
                            <span class="font-medium">${Math.min(currentPage * perPage, totalCategories)}</span> of 
                            <span class="font-medium">${totalCategories}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            ${currentPage > 1 ? `
                            <button onclick="loadCategories(${currentPage - 1})" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            ` : ''}
                            
                            ${generatePageLinks()}
                            
                            ${currentPage < totalPages ? `
                            <button onclick="loadCategories(${currentPage + 1})" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            ` : ''}
                        </nav>
                    </div>
                </div>
            </div>
        `;

        categoryList.insertAdjacentHTML('beforeend', paginationHtml);
    }

    // Helper function for numbered links (smart truncation)
    function generatePageLinks() {
        let links = '';
        const maxVisiblePages = 5; // Show max 5 page numbers at a time
        let startPage, endPage;

        if (totalPages <= maxVisiblePages) {
            // Show all pages
            startPage = 1;
            endPage = totalPages;
        } else {
            if (currentPage <= Math.ceil(maxVisiblePages / 2)) {
                startPage = 1;
                endPage = maxVisiblePages;
            } else if (currentPage + Math.floor(maxVisiblePages / 2) >= totalPages) {
                startPage = totalPages - maxVisiblePages + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - Math.floor(maxVisiblePages / 2);
                endPage = currentPage + Math.floor(maxVisiblePages / 2);
            }
        }

        // Add first page and ellipsis if needed
        if (startPage > 1) {
            links += `
                <button onclick="loadCategories(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    1
                </button>
                ${startPage > 2 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
            `;
        }

        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
            links += `
                <button onclick="loadCategories(${i})" class="${i === currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    ${i}
                </button>
            `;
        }

        // Add last page and ellipsis if needed
        if (endPage < totalPages) {
            links += `
                ${endPage < totalPages - 1 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
                <button onclick="loadCategories(${totalPages})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ${totalPages}
                </button>
            `;
        }

        return links;
    }





    // Initial load: first products, then categories
    loadProductsList(() => window.loadCategories());

    // Add/Edit Modal Logic
    window.openAddCategoryModal = function() {
        editingCategoryId = null;
        document.getElementById('category-modal-title').textContent = 'Add Category';
        document.getElementById('category-modal-submit').textContent = 'Add Category';
        document.getElementById('category-name').value = '';
        document.getElementById('category-id').value = '';
        document.getElementById('category-is-active').checked = true;
        document.getElementById('category-error-name').textContent = '';
        document.getElementById('category-modal-backdrop').classList.remove('hidden');
    };

    window.openEditCategoryModal = function(category) {
        editingCategoryId = category.id;
        document.getElementById('category-modal-title').textContent = 'Edit Category';
        document.getElementById('category-modal-submit').textContent = 'Update Category';
        document.getElementById('category-name').value = category.name;
        document.getElementById('category-id').value = category.id;
        document.getElementById('category-is-active').checked = !!category.is_active;
        document.getElementById('category-error-name').textContent = '';
        document.getElementById('category-modal-backdrop').classList.remove('hidden');
    };

    window.closeCategoryModal = function() {
        document.getElementById('category-modal-backdrop').classList.add('hidden');
    };

    document.getElementById('category-modal-form').onsubmit = function(e) {
    e.preventDefault();
    const name = document.getElementById('category-name').value.trim();
    const is_active = document.getElementById('category-is-active').checked ? 1 : 0;
    const id = document.getElementById('category-id').value;
    const token = localStorage.getItem('api_token');
    document.getElementById('category-error-name').textContent = '';
    document.getElementById('category-modal-submit').disabled = true;

    const url = id ? `/api/admin/categories/${id}` : '/api/admin/categories';
    const method = id ? 'put' : 'post';

    axios({
        url: url,
        method: method,
        data: { name, is_active },
        headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
    }).then(() => {
        closeCategoryModal();
        loadCategories();
    }).catch(e => {
        // Custom error handling
        if (
            e.response &&
            e.response.data &&
            e.response.data.errors &&
            e.response.data.errors.name &&
            e.response.data.errors.name[0].includes('unique')
        ) {
            document.getElementById('category-error-name').textContent = "This category already exists. Try editing it.";
        } else if (e.response && e.response.data && e.response.data.errors && e.response.data.errors.name) {
            document.getElementById('category-error-name').textContent = e.response.data.errors.name[0];
        } else {
            alert('Failed to save category');
        }
    }).finally(() => {
        document.getElementById('category-modal-submit').disabled = false;
    });
};


    // Delete Modal Logic
    window.openDeleteModal = function(categoryId) {
        deletingCategoryId = categoryId;
        document.getElementById('delete-modal-backdrop').classList.remove('hidden');
    };

    window.closeDeleteModal = function() {
        deletingCategoryId = null;
        document.getElementById('delete-modal-backdrop').classList.add('hidden');
    };

    document.getElementById('delete-modal-confirm').onclick = function() {
        if (!deletingCategoryId) return;
        axios.delete(`/api/admin/categories/${deletingCategoryId}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).then(() => {
            closeDeleteModal();
            window.loadCategories();
        }).catch(() => {
            alert('Delete failed. Please try again.');
        });
    };
});
</script>
@endsection
