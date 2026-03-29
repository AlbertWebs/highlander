<x-guest-layout :page-title="__('Reset password')">
    <header class="mb-8 text-center">
        <h1 class="font-serif text-2xl font-semibold tracking-tight text-primary sm:text-3xl">{{ __('Reset password') }}</h1>
        <p class="mt-3 text-sm leading-relaxed text-ink/70">
            {{ __('Forgot your password? Enter the email address for your administrator account and we will send you a link to choose a new password.') }}
        </p>
    </header>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input
                id="email"
                class="block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="email"
                placeholder="name@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    @if (Route::has('login'))
        <p class="mt-8 border-t border-secondary/30 pt-6 text-center text-sm text-ink/65">
            <a
                href="{{ route('login') }}"
                class="font-medium text-primary underline decoration-primary/30 underline-offset-2 transition hover:decoration-primary"
            >{{ __('Back to sign in') }}</a>
        </p>
    @endif
</x-guest-layout>
