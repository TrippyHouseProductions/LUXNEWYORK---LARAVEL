<div>
    @if($showModal && $product)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-8 w-full max-w-lg shadow-lg relative">
                <button wire:click="close" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 text-2xl">&times;</button>
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full md:w-40 h-40 object-cover rounded-lg border">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">{{ $product->name }}</h3>
                        <p class="text-lg text-gray-600 mb-2">{{ $product->description }}</p>
                        <div class="font-semibold text-indigo-700 text-xl mb-2">${{ $product->price }}</div>
                        <div class="mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock > 0 ? "$product->stock in stock" : "Out of stock" }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">Category: {{ $product->category->name ?? 'Uncategorized' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
