@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-input-interactive rounded-xl border border-secondary/60 bg-white px-4 py-3 text-ink shadow-sm placeholder:text-ink/40 disabled:cursor-not-allowed disabled:bg-surface/80 disabled:opacity-60']) }}>
