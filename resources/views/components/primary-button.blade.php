<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-4 py-2 bg-secondary-green border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-green active:bg-primary-green focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-green disabled:opacity-50 transition ease-in-out duration-150'
    ]) }}>
    {{ $slot }}
</button>
