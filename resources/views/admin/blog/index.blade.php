@extends('layouts.admin')

@section('content')
<div class="flex bg-gray-50">
    <div class="flex-1 overflow-auto">
        <div class="p-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Blog Management</h2>
                <div class="w-24 h-1 bg-indigo-600"></div>
            </div>

            <!-- Add Blog Button -->
            <div class="mb-8">
                <button onclick="openBlogModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Blog
                </button>
            </div>

            <!-- Blog List Loading State -->
            <div id="loading-state" class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-700 inline-block mb-4">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading blogs...</p>
            </div>

            <!-- Blog List -->
            <div id="admin-blog-list" class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm"></div>

            <!-- Error State -->
            <div id="error-state" class="hidden bg-white border border-gray-200 rounded-xl p-8 shadow-sm text-center">
                <div class="p-3 rounded-lg bg-red-50 text-red-600 inline-block mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load blogs</h3>
                <p class="text-gray-500 mb-4">We're having trouble loading blog data. Please try again later.</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Blog Modals (Add/Edit) -->
<div id="blog-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-8 relative">
        <button type="button" onclick="closeBlogModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <h3 id="blog-modal-title" class="text-2xl font-bold mb-4">Add Blog</h3>
        <form id="blog-modal-form">
            <input type="hidden" id="blog-id">
            <div class="mb-4">
                <label class="block mb-2 font-medium">Title</label>
                <input type="text" id="blog-title" class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-200" maxlength="255" required>
                <div id="blog-error-title" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Tags (comma separated)</label>
                <input type="text" id="blog-tags" class="w-full border px-3 py-2 rounded">
                <div id="blog-error-tags" class="text-red-600 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-medium">Content</label>
                <textarea id="blog-content" class="w-full border px-3 py-2 rounded" rows="5" required></textarea>
                <div id="blog-error-content" class="text-red-600 text-sm mt-1"></div>
            </div>
            <button type="submit" id="blog-modal-submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl hover:bg-indigo-700 w-full">
                Save Blog
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-blog-modal-backdrop" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-8 text-center relative">
        <button type="button" onclick="closeDeleteBlogModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Blog?</h3>
        <p class="text-gray-500 mb-4">Are you sure you want to delete this blog? This action cannot be undone.</p>
        <div class="flex gap-2 justify-center">
            <button type="button" onclick="closeDeleteBlogModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
            <button type="button" id="delete-blog-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
// --- Set your API token here if needed for Authorization ---
const apiToken = localStorage.getItem('api_token'); // or set manually: 'your_api_token_here'

class PaginationHelper {
    constructor(loadFunction, containerId, options = {}) {
        this.loadFunction = loadFunction;
        this.containerId = containerId;
        this.currentPage = 1;
        this.totalPages = 1;
        this.perPage = options.perPage || 6;
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
                    <button onclick="blogPagination.loadPrevious()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    ` : ''}
                    ${this.currentPage < this.totalPages ? `
                    <button onclick="blogPagination.loadNext()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
        // Previous
        if (this.currentPage > 1) {
            links += `
                <button onclick="blogPagination.loadPrevious()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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
                <button onclick="blogPagination.loadPage(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    1
                </button>
                ${startPage > 2 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
            `;
        }
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            links += `
                <button onclick="blogPagination.loadPage(${i})" class="${i === this.currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'} relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    ${i}
                </button>
            `;
        }
        // Last page and ellipsis
        if (endPage < this.totalPages) {
            links += `
                ${endPage < this.totalPages - 1 ? '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>' : ''}
                <button onclick="blogPagination.loadPage(${this.totalPages})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ${this.totalPages}
                </button>
            `;
        }
        // Next
        if (this.currentPage < this.totalPages) {
            links += `
                <button onclick="blogPagination.loadNext()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

window.openBlogModal = function(blog = null) {
    editingBlogId = blog ? blog.id : null;
    document.getElementById('blog-modal-title').textContent = blog ? 'Edit Blog' : 'Add Blog';
    document.getElementById('blog-modal-submit').textContent = blog ? 'Update Blog' : 'Add Blog';
    document.getElementById('blog-id').value = blog?.id || '';
    document.getElementById('blog-title').value = blog?.title || '';
    document.getElementById('blog-tags').value = blog?.tags ? blog.tags.join(', ') : '';
    document.getElementById('blog-content').value = blog?.content || '';
    document.getElementById('blog-modal-backdrop').classList.remove('hidden');
};
window.closeBlogModal = function() {
    document.getElementById('blog-modal-backdrop').classList.add('hidden');
    editingBlogId = null;
};
window.openDeleteBlogModal = function(id) {
    deletingBlogId = id;
    document.getElementById('delete-blog-modal-backdrop').classList.remove('hidden');
};
window.closeDeleteBlogModal = function() {
    deletingBlogId = null;
    document.getElementById('delete-blog-modal-backdrop').classList.add('hidden');
};

function renderBlogList(blogs) {
    const list = document.getElementById('admin-blog-list');
    if (!blogs.length) {
        list.innerHTML = `<div class="p-8 text-center text-gray-500">No blogs found.</div>`;
        return;
    }
    list.innerHTML = blogs.map(blog => `
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">${blog.title}</h3>
                <p class="text-gray-600 text-sm mt-1">${blog.tags ? blog.tags.join(', ') : ''}</p>
                <div class="text-gray-400 text-xs mt-1">${blog.published_at ? 'Published: ' + blog.published_at : ''}</div>
            </div>
            <div class="flex gap-2">
                <button onclick='openBlogModal(${JSON.stringify(blog)})' class="text-indigo-600 hover:text-indigo-900">Edit</button>
                <button onclick='openDeleteBlogModal("${blog.id}")' class="text-red-600 hover:text-red-900">Delete</button>
            </div>
        </div>
    `).join('');
}

function loadBlogs(page = 1) {
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    if (loadingState) loadingState.style.display = 'block';
    if (errorState) errorState.classList.add('hidden');

    axios.get(`/api/posts?page=${page}`, apiToken ? { headers: { Authorization: 'Bearer ' + apiToken } } : {})
        .then(res => {
            const data = res.data;
            renderBlogList(data.data);
            blogPagination.updateState(data);
            blogPagination.renderPagination();
        })
        .catch(() => {
            if (errorState) errorState.classList.remove('hidden');
        })
        .finally(() => {
            if (loadingState) loadingState.style.display = 'none';
        });
}

function submitBlogForm(e) {
    e.preventDefault();
    const id = document.getElementById('blog-id').value;
    const title = document.getElementById('blog-title').value;
    const tags = document.getElementById('blog-tags').value.split(',').map(t => t.trim()).filter(Boolean);
    const content = document.getElementById('blog-content').value;
    const payload = { title, tags, content };
    const method = id ? 'put' : 'post';
    const url = id ? `/api/posts/${id}` : '/api/posts';
    axios({ method, url, data: payload, headers: apiToken ? { Authorization: 'Bearer ' + apiToken } : {} })
        .then(() => {
            closeBlogModal();
            blogPagination.loadPage(blogPagination.currentPage);
        });
}

let blogPagination;
let editingBlogId = null;
let deletingBlogId = null;

document.addEventListener('DOMContentLoaded', function () {
    blogPagination = new PaginationHelper(loadBlogs, 'admin-blog-list', { perPage: 6 });
    blogPagination.loadPage(1);
    const blogForm = document.getElementById('blog-modal-form');
    if (blogForm) blogForm.addEventListener('submit', submitBlogForm);
    const deleteConfirm = document.getElementById('delete-blog-modal-confirm');
    if (deleteConfirm) deleteConfirm.addEventListener('click', () => {
        if (!deletingBlogId) return;
        axios.delete(`/api/posts/${deletingBlogId}`, apiToken ? { headers: { Authorization: 'Bearer ' + apiToken } } : {}).then(() => {
            closeDeleteBlogModal();
            blogPagination.loadPage(blogPagination.currentPage);
        });
    });
});
</script>
@endpush
