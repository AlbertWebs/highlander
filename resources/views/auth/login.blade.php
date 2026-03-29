<x-guest-layout :page-title="__('Sign in')">
    <header class="mb-8 text-center">
        <h1 class="font-serif text-2xl font-semibold tracking-tight text-primary sm:text-3xl">{{ __('Sign in') }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-ink/70">{{ __('Enter your credentials to access the admin area.') }}</p>
    </header>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                autocomplete="username"
                placeholder="name@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div
            class="space-y-1.5"
            x-data="{
                showPassword: false,
                ariaShow: {{ json_encode(__('Show password')) }},
                ariaHide: {{ json_encode(__('Hide password')) }},
            }"
        >
            <div class="flex flex-wrap items-end justify-between gap-2">
                <x-input-label for="password" :value="__('Password')" class="mb-0" />
                @if (Route::has('password.request'))
                    <a
                        class="text-xs font-medium text-primary underline decoration-primary/30 underline-offset-2 transition hover:decoration-primary sm:text-sm"
                        href="{{ route('password.request') }}"
                    >{{ __('Forgot password?') }}</a>
                @endif
            </div>
            <div class="relative mt-1">
                <input
                    id="password"
                    class="form-input-interactive block w-full rounded-xl border border-secondary/60 bg-white px-4 py-3 pr-12 text-ink shadow-sm placeholder:text-ink/40 disabled:cursor-not-allowed disabled:bg-surface/80 disabled:opacity-60"
                    name="password"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center rounded-r-xl text-ink/45 transition hover:text-primary focus:outline-none focus-visible:text-primary"
                    x-bind:aria-pressed="showPassword"
                    x-on:click="showPassword = !showPassword"
                    x-bind:aria-label="showPassword ? ariaHide : ariaShow"
                >
                    <svg x-show="!showPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m12.874 12.874L21 21M9.88 9.88a3 3 0 1 0 4.24 4.24m.01-.01L9.88 9.88Z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-3 pt-1">
            <label for="remember_me" class="flex cursor-pointer items-center gap-3">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 rounded border-secondary/60 text-primary shadow-sm focus:ring-2 focus:ring-primary/30"
                    name="remember"
                >
                <span class="text-sm text-ink/80">{{ __('Remember me on this device') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
