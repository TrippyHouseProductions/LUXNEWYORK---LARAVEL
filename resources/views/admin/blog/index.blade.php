@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Manage Blog Posts</h2>

    <!-- Post Form -->
    <div class="bg-white rounded shadow p-4 mb-8">
        <h3 class="font-semibold mb-2" id="form-title">Create New Post</h3>
        <form id="post-form">
            <input type="hidden" id="post-id">
            <div class="mb-2">
                <input type="text" id="title" class="w-full border rounded px-2 py-1" placeholder="Title" required>
            </div>
            <div class="mb-2">
                <input type="text" id="author" class="w-full border rounded px-2 py-1" placeholder="Author" required>
            </div>
            <div class="mb-2">
                <input type="text" id="tags" class="w-full border rounded px-2 py-1" placeholder="Tags (comma separated)">
            </div>
            <div class="mb-2">
                <textarea id="content" class="w-full border rounded px-2 py-1" placeholder="Content" required></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                <button type="button" id="cancel-edit" class="bg-gray-300 px-4 py-2 rounded hidden">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded shadow p-4">
        <h3 class="font-semibold mb-4">All Posts</h3>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="py-2 px-3 text-left">Title</th>
                    <th class="py-2 px-3 text-left">Author</th>
                    <th class="py-2 px-3 text-left">Tags</th>
                    <th class="py-2 px-3"></th>
                </tr>
            </thead>
            <tbody id="posts-table"></tbody>
        </table>
    </div>
</div>

<!-- Axios & Script -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const apiUrl = '/api/posts'; // Adjust if using a different prefix

    let editingId = null;

    // Fetch and render posts
    // function loadPosts() {
    //     axios.get(apiUrl).then(res => {
    //         const tbody = document.getElementById('posts-table');
    //         tbody.innerHTML = '';
    //         res.data.forEach(post => {
    //         console.log('Tags:', post.tags, typeof post.tags); // <-- Debug!
    //         tbody.innerHTML += `
    //             <tr>
    //                 <td class="border px-3 py-1">${post.title}</td>
    //                 <td class="border px-3 py-1">${post.author}</td>
    //                 <td class="border px-3 py-1">${(Array.isArray(post.tags) ? post.tags : [post.tags || '']).join(', ')}</td>
    //                 <td class="border px-3 py-1 flex gap-2">
    //                     <button onclick="editPost('${post._id}')" class="text-indigo-600 hover:underline">Edit</button>
    //                     <button onclick="deletePost('${post._id}')" class="text-red-600 hover:underline">Delete</button>
    //                 </td>
    //             </tr>
    //         `;
    //     });

    //     });
    // }

async function loadPosts() {
    try {
        const response = await axios.get(apiUrl);
        
        if (response.data.success) {
            renderPosts(response.data.data);
        } else {
            console.error('API Error:', response.data.message);
            alert('Failed to load posts: ' + (response.data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Network Error:', error);
        alert('Network error occurred. Check console for details.');
    }
}

function renderPosts(posts) {
    const tbody = document.getElementById('posts-table');
    tbody.innerHTML = '';
    
    posts.forEach(post => {
        // Safely handle MongoDB _id field
        const postId = post._id?.$oid || post._id || post.id;
        
        tbody.innerHTML += `
            <tr>
                <td class="border px-3 py-1">${post.title || ''}</td>
                <td class="border px-3 py-1">${post.author || ''}</td>
                <td class="border px-3 py-1">${Array.isArray(post.tags) ? post.tags.join(', ') : ''}</td>
                <td class="border px-3 py-1 flex gap-2">
                    <button onclick="editPost('${postId}')" class="text-indigo-600 hover:underline">Edit</button>
                    <button onclick="deletePost('${postId}')" class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        `;
    });
}

    // Create or update post
    document.getElementById('post-form').onsubmit = function(e) {
        e.preventDefault();
        const data = {
            title: document.getElementById('title').value,
            author: document.getElementById('author').value,
            content: document.getElementById('content').value,
            tags: document.getElementById('tags').value
        };
        if (editingId) {
            axios.put(apiUrl + '/' + editingId, data)
                .then(() => {
                    resetForm();
                    loadPosts();
                });
        } else {
            axios.post(apiUrl, data)
                .then(() => {
                    resetForm();
                    loadPosts();
                });
        }
    };

    // Edit post
    window.editPost = function(id) {
        axios.get(apiUrl + '/' + id).then(res => {
            const post = res.data;
            editingId = post._id;
            document.getElementById('title').value = post.title;
            document.getElementById('author').value = post.author;
            document.getElementById('content').value = post.content;
            document.getElementById('tags').value = (post.tags || []).join(', ');
            document.getElementById('form-title').textContent = 'Edit Post';
            document.getElementById('cancel-edit').classList.remove('hidden');
        });
    };

    // Delete post
    window.deletePost = function(id) {
        if (confirm('Are you sure?')) {
            axios.delete(apiUrl + '/' + id).then(() => loadPosts());
        }
    };

    // Cancel editing
    document.getElementById('cancel-edit').onclick = function() {
        resetForm();
    };

    function resetForm() {
        editingId = null;
        document.getElementById('post-form').reset();
        document.getElementById('form-title').textContent = 'Create New Post';
        document.getElementById('cancel-edit').classList.add('hidden');
    }

    loadPosts();
</script>
@endsection
