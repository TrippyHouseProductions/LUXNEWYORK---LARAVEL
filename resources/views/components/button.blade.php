<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center px-6 py-2 bg-indigo-600 text-white rounded-md font-semibold text-base shadow-sm hover:bg-indigo-700 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 disabled:opacity-60'
]) }}>
    {{ $slot }}
</button>
