@extends('layouts.mylayout')

@section('content')
    <h2 class="text-2xl font-semibold mb-6">Categories</h2>
    <div id="category-list" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/categories', {
            headers: {'Accept': 'application/json'}
        })
        .then(res => res.json())
        .then(categories => {
            let html = '';
            categories.forEach(category => {
                html += `
                <a href="/categories/${category.id}" class="block p-6 bg-white rounded-lg shadow hover:bg-indigo-50 transition">
                    <span class="text-lg font-medium">${category.name}</span>
                </a>
                `;
            });
            document.getElementById('category-list').innerHTML = html;
        });
    });
    </script>
@endsection
