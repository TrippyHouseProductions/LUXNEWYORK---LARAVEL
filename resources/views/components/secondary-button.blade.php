<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center px-6 py-2 bg-white border border-gray-300 rounded-md font-semibold text-base text-gray-700 shadow-sm hover:bg-gray-100 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 disabled:opacity-60'
]) }}>
    {{ $slot }}
</button>
