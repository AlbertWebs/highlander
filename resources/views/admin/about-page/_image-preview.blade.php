{{-- Expects: $url (nullable string), optional $label --}}
@if(filled($url ?? null))
    <div class="mt-3 rounded-xl border border-secondary/40 bg-secondary/[0.06] p-3">
        <p class="text-xs font-medium text-ink/60">{{ $label ?? __('Current image') }}</p>
        <img src="{{ $url }}" alt="" class="mt-2 max-h-52 w-full max-w-xl rounded-lg border border-secondary/50 object-contain object-left">
    </div>
@endif
