<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-green border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-green/90 focus:bg-brand-green/90 active:bg-brand-green focus:outline-none focus:ring-2 focus:ring-brand-yellow focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
