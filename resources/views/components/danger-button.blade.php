<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white rounded-md font-semibold text-base shadow-sm hover:bg-red-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 disabled:opacity-60'
]) }}>
    {{ $slot }}
</button>
