@extends('layouts.mylayout')

@section('content')
    <div class="mb-8">
        <h2 id="category-name" class="text-2xl font-semibold mb-4">Category</h2>
    </div>
    <div id="product-list" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryId = window.location.pathname.split('/').pop();
        fetch(`/api/categories/${categoryId}`, {
            headers: {'Accept': 'application/json'}
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('category-name').textContent = data.name;
            fetch(`/api/products`, {headers: {'Accept': 'application/json'}})
            .then(res => res.json())
            .then(products => {
                let html = '';
                products.filter(product => product.category.id == categoryId)
                    .forEach(product => {
                        html += `
                        <div class="bg-white rounded-xl p-4 shadow hover:shadow-lg transition">
                            <h3 class="text-lg font-semibold mb-2">${product.name}</h3>
                            <div class="mb-2">${product.description ?? ''}</div>
                            <div class="font-bold text-indigo-600 mb-3">$${product.price}</div>
                            <a href="/products/${product.id}" class="text-indigo-600 hover:underline">View Details</a>
                        </div>
                        `;
                    });
                document.getElementById('product-list').innerHTML = html || '<div class="text-gray-500">No products in this category yet.</div>';
            });
        });
    });
    </script>
@endsection
