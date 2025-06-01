@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">User Management</h2>
                <div class="w-24 h-1 bg-indigo-600"></div>
            </div>
            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700 inline-block mb-4">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading users...</p>
            </div>
            <div id="admin-user-list" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"></div>
            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-red-50 text-red-600 inline-block mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load users</h3>
                <p class="text-gray-500 mb-4">We're having trouble loading user data. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-user-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-8 text-center relative">
        <button type="button" onclick="closeDeleteUserModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete User?</h3>
        <p class="text-gray-500 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteUserModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="delete-user-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

<script>
// PaginationHelper class (copy-paste, don't change)
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
                    <button onclick="userPagination.loadPrevious()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    ` : ''}
                    ${this.currentPage < this.totalPages ? `
                    <button onclick="userPagination.loadNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
        if (this.currentPage > 1) {
            links += `
                <button onclick="userPagination.loadPrevious()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
        }
        if (startPage > 1) {
            links += `
                <button onclick="userPagination.loadPage(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</button>
                ${startPage > 2 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
            `;
        }
        for (let i = startPage; i <= endPage; i++) {
            links += `
                <button onclick="userPagination.loadPage(${i})" class="${i === this.currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    ${i}
                </button>
            `;
        }
        if (endPage < this.totalPages) {
            links += `
                ${endPage < this.totalPages - 1 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
                <button onclick="userPagination.loadPage(${this.totalPages})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">${this.totalPages}</button>
            `;
        }
        if (this.currentPage < this.totalPages) {
            links += `
                <button onclick="userPagination.loadNext()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

let userPagination;
let deletingUserId = null;

document.addEventListener('DOMContentLoaded', function() {
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const userList = document.getElementById('admin-user-list');
    const token = localStorage.getItem('api_token');

    userPagination = new PaginationHelper(loadUsers, 'admin-user-list', { perPage: 10, maxVisiblePages: 5 });

    function loadUsers(page = 1) {
        loadingState.style.display = 'block';
        errorState.classList.add('hidden');
        userList.innerHTML = '';

        axios.get(`/api/admin/users?page=${page}&per_page=${userPagination.perPage}`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => {
            loadingState.style.display = 'none';
            const data = res.data;
            const users = data.data || [];
            userPagination.updateState(data);
            if (!users.length) {
                userList.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="p-3 rounded-lg bg-gray-50 text-gray-500 inline-block mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No users found</h3>
                        <p class="text-gray-500">There are currently no users in the system.</p>
                    </div>
                `;
                userPagination.renderPagination();
                return;
            }
            renderUserTable(users);
            userPagination.renderPagination();
        })
        .catch(error => {
            console.error('Error loading users:', error);
            loadingState.style.display = 'none';
            errorState.classList.remove('hidden');
        });
    }

    function renderUserTable(users) {
        let html = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;
        users.forEach(user => {
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                ${user.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${user.name}</div>
                                <div class="text-sm text-gray-500">Joined ${new Date(user.created_at).toLocaleDateString()}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${user.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            ${user.role || 'User'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.orders_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${user.orders_total ? user.orders_total : '0.00'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openDeleteUserModal(${user.id})" class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
        userList.innerHTML = html;
    }

    // Delete User Modal logic
    window.openDeleteUserModal = function(userId) {
        deletingUserId = userId;
        document.getElementById('delete-user-modal-backdrop').classList.remove('hidden');
    };
    window.closeDeleteUserModal = function() {
        deletingUserId = null;
        document.getElementById('delete-user-modal-backdrop').classList.add('hidden');
    };
    document.getElementById('delete-user-modal-confirm').onclick = function() {
        if (!deletingUserId) return;
        axios.delete(`/api/admin/users/${deletingUserId}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        }).then(() => {
            closeDeleteUserModal();
            userPagination.loadPage(userPagination.currentPage);
        }).catch(() => {
            alert('Delete failed. Please try again.');
        });
    };

    // Initial load
    userPagination.loadPage(1);
});
</script>
@endsection
