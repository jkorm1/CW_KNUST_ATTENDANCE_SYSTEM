<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-orange-500 border border-orange-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow hover:bg-orange-600 focus:bg-orange-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
