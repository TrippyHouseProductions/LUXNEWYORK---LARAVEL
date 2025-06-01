@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Order Management</h2>
                <div class="w-24 h-1 bg-indigo-600"></div>
            </div>

            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700 inline-block mb-4">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading orders...</p>
            </div>

            <div id="admin-order-list" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"></div>

            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-red-50 text-red-600 inline-block mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load orders</h3>
                <p class="text-gray-500 mb-4">We're having trouble loading order data. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-order-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-8 text-center relative">
        <button type="button" onclick="closeDeleteOrderModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Order?</h3>
        <p class="text-gray-500 mb-4">Are you sure you want to delete this order? This action cannot be undone.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteOrderModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="delete-order-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="order-details-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 relative">
        <button type="button" onclick="closeOrderDetailsModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <h3 class="text-2xl font-bold mb-4">Order Details</h3>
        <div id="order-details-content" class="space-y-4"></div>
    </div>
</div>

<script>
// PaginationHelper definition (exactly as in previous examples)
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
                    <button onclick="orderPagination.loadPrevious()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    ` : ''}
                    ${this.currentPage < this.totalPages ? `
                    <button onclick="orderPagination.loadNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                <button onclick="orderPagination.loadPrevious()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
        }
        if (startPage > 1) {
            links += `
                <button onclick="orderPagination.loadPage(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</button>
                ${startPage > 2 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
            `;
        }
        for (let i = startPage; i <= endPage; i++) {
            links += `
                <button onclick="orderPagination.loadPage(${i})" class="${i === this.currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    ${i}
                </button>
            `;
        }
        if (endPage < this.totalPages) {
            links += `
                ${endPage < this.totalPages - 1 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
                <button onclick="orderPagination.loadPage(${this.totalPages})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">${this.totalPages}</button>
            `;
        }
        if (this.currentPage < this.totalPages) {
            links += `
                <button onclick="orderPagination.loadNext()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

// Order logic
let orderPagination; // Global so HTML onclick works
let deletingOrderId = null;

document.addEventListener('DOMContentLoaded', function() {
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const orderList = document.getElementById('admin-order-list');
    const token = localStorage.getItem('api_token');

    orderPagination = new PaginationHelper(loadOrders, 'admin-order-list', { perPage: 6, maxVisiblePages: 5 });

    function loadOrders(page = 1) {
        loadingState.style.display = 'block';
        errorState.classList.add('hidden');
        orderList.innerHTML = '';
        axios.get(`/api/admin/orders?page=${page}&per_page=${orderPagination.perPage}`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            loadingState.style.display = 'none';
            const data = response.data;
            const orders = data.data || [];
            orderPagination.updateState(data);
            if (!orders.length) {
                orderList.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="p-3 rounded-lg bg-gray-50 text-gray-500 inline-block mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No orders found</h3>
                        <p class="text-gray-500">There are currently no orders in the system.</p>
                    </div>
                `;
                orderPagination.renderPagination();
                return;
            }
            renderOrderTable(orders);
            orderPagination.renderPagination();
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            loadingState.style.display = 'none';
            errorState.classList.remove('hidden');
        });
    }

    function renderOrderTable(orders) {
        let html = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;
        orders.forEach(order => {
            const orderDate = new Date(order.created_at).toLocaleDateString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric'
            });
            let statusClass = 'bg-gray-100 text-gray-800';
            if (order.status && order.status.toLowerCase() === 'confirmed') statusClass = 'bg-green-100 text-green-800';
            else if (order.status && order.status.toLowerCase() === 'pending') statusClass = 'bg-yellow-100 text-yellow-800';
            else if (order.status && (order.status.toLowerCase() === 'canceled' || order.status.toLowerCase() === 'cancelled')) statusClass = 'bg-red-100 text-red-800';
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${order.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                ${order.user ? order.user.name.charAt(0).toUpperCase() : 'G'}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${order.user ? order.user.name : 'Guest'}</div>
                                <div class="text-sm text-gray-500">${order.user ? order.user.email : ''}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${orderDate}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select class="order-status-select px-3 py-1 rounded-full border ${statusClass}" 
                                data-order-id="${order.id}" style="min-width:120px;">
                            <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="confirmed" ${order.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="canceled" ${order.status === 'canceled' || order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$${order.total}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" onclick="openOrderDetailsModal(${order.id})" class="text-indigo-600 hover:text-indigo-900 transition-colors mr-4">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <button onclick="openDeleteOrderModal(${order.id})" class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
        orderList.innerHTML = html;

        // Attach event to status dropdowns
        document.querySelectorAll('.order-status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const orderId = this.getAttribute('data-order-id');
                const newStatus = this.value;
                updateOrderStatus(orderId, newStatus);
            });
        });
    }

    function updateOrderStatus(orderId, newStatus) {
        axios.post(`/api/admin/orders/${orderId}`,
            { status: newStatus },
            {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            }
        ).then(() => {
            orderPagination.loadPage(orderPagination.currentPage);
        }).catch(() => {
            alert('Failed to update status. Please try again.');
        });
    }

    // Initial load
    orderPagination.loadPage(1);
    });

    function openOrderDetailsModal(orderId) {
        const token = localStorage.getItem('api_token');
        document.getElementById('order-details-modal-backdrop').classList.remove('hidden');
        const content = document.getElementById('order-details-content');
        content.innerHTML = `
            <div class="text-center p-4">
                <svg class="w-8 h-8 animate-spin mx-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <div class="text-gray-600 mt-3">Loading order details...</div>
            </div>
        `;

        axios.get(`/api/admin/orders/${orderId}`, {
            headers: { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' }
        })
        .then(response => {
            const order = response.data.data || response.data;
            let html = `
                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h4 class="text-lg font-semibold text-gray-900">Order Summary</h4>
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${
                            order.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                            order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-red-100 text-red-800'
                        }">${order.status}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Order #</p>
                            <p class="font-medium">${order.id}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium">${new Date(order.created_at).toLocaleString()}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="font-medium">${order.user ? order.user.name : 'Guest'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">${order.user ? order.user.email : 'N/A'}</p>
                        </div>
                    </div>
                    
                    <div class="pt-3 border-t">
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-xl font-bold text-indigo-600">$${order.total}</p>
                    </div>
                </div>
            `;

            // List items
            html += `
                <div class="mt-6">
                    <h4 class="font-bold text-lg mb-3 text-gray-900">Order Items</h4>
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
            `;
            
            if (order.order_items && order.order_items.length > 0) {
                html += `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                order.order_items.forEach(item => {
                    html += `
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${item.product ? item.product.name : 'N/A'}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-right">${item.quantity}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-right">$${item.price}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">$${(item.quantity * item.price).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                html += `</tbody></table>`;
            } else {
                html += `
                    <div class="p-4 text-center text-gray-500 bg-white">
                        No items found in this order
                    </div>
                `;
            }
            
            html += `</div></div>`; // Close the items container
            
            content.innerHTML = html;
        })
        .catch(() => {
            content.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                    <svg class="w-6 h-6 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="mt-2 text-red-600">Failed to load order details</p>
                </div>
            `;
        });
    }

    function closeOrderDetailsModal() {
        document.getElementById('order-details-modal-backdrop').classList.add('hidden');
    }

    // --- Modal delete logic ---
    function openDeleteOrderModal(orderId) {
        deletingOrderId = orderId;
        document.getElementById('delete-order-modal-backdrop').classList.remove('hidden');
    }
    function closeDeleteOrderModal() {
        deletingOrderId = null;
        document.getElementById('delete-order-modal-backdrop').classList.add('hidden');
    }
    document.getElementById('delete-order-modal-confirm').onclick = function() {
        if (!deletingOrderId) return;
        const token = localStorage.getItem('api_token');
        axios.delete(`/api/admin/orders/${deletingOrderId}`, {
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        }).then(() => {
            closeDeleteOrderModal();
            orderPagination.loadPage(orderPagination.currentPage);
        }).catch(() => {
            alert('Delete failed. Please try again.');
        });
    };
</script>
@endsection
