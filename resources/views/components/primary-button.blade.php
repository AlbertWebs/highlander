<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-[2.75rem] items-center justify-center rounded-xl border border-transparent bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-soft transition duration-200 ease-out hover:bg-primary/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
