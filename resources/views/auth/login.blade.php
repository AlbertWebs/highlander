<x-guest-layout :page-title="__('Sign in')">
    <header class="text-center sm:text-left">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/12 text-primary ring-1 ring-primary/20 sm:mx-0">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <p class="mt-5 text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-primary">{{ __('Secure sign-in') }}</p>
        <h1 class="mt-2 font-serif text-2xl font-semibold tracking-tight text-ink sm:text-[1.75rem] sm:leading-tight">{{ __('Welcome back') }}</h1>
        <p class="mt-3 text-sm leading-relaxed text-ink/70 sm:max-w-md">{{ __('Use the email and password issued for this dashboard. Need help? Contact your site owner.') }}</p>
    </header>

    <x-auth-session-status class="mt-8" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <div class="rounded-2xl border border-secondary/50 bg-surface/70 p-5 shadow-inner ring-1 ring-black/[0.02] sm:p-6">
            <p id="login-credentials-label" class="text-xs font-semibold uppercase tracking-wider text-ink/55">{{ __('Account credentials') }}</p>
            <div class="mt-5 space-y-5">
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
                        aria-describedby="login-credentials-label"
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
                                class="rounded-md text-xs font-semibold text-primary underline decoration-primary/30 underline-offset-2 transition hover:decoration-primary sm:text-sm"
                                href="{{ route('password.request') }}"
                            >{{ __('Forgot password?') }}</a>
                        @endif
                    </div>
                    <div class="relative mt-1">
                        <input
                            id="password"
                            class="form-input-interactive block w-full rounded-xl border border-secondary/60 bg-white px-4 py-3.5 pr-12 text-ink shadow-sm placeholder:text-ink/40 transition focus:border-primary/50 focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:bg-surface/80 disabled:opacity-60"
                            name="password"
                            x-bind:type="showPassword ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            aria-describedby="login-credentials-label"
                        />
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-xl text-ink/40 transition hover:bg-primary/[0.06] hover:text-primary focus:outline-none focus-visible:text-primary"
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
            </div>
        </div>

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <label for="remember_me" class="flex cursor-pointer select-none items-center gap-3 rounded-xl border border-transparent px-1 py-1 transition hover:border-secondary/40 hover:bg-surface/50">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="h-4 w-4 shrink-0 rounded border-secondary/70 text-primary shadow-sm focus:ring-2 focus:ring-primary/35 focus:ring-offset-0 sm:h-[1.125rem] sm:w-[1.125rem]"
                    name="remember"
                >
                <span class="text-sm font-medium leading-snug text-ink/85">{{ __('Remember me on this device') }}</span>
            </label>
        </div>

        <div class="pt-1">
            <x-primary-button class="w-full justify-center py-3.5 text-[0.9375rem] shadow-soft">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        @if(request()->secure())
            <p class="flex items-center justify-center gap-2 text-center text-xs text-ink/45">
                <svg class="h-3.5 w-3.5 shrink-0 text-primary/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                {{ __('Your connection to this page is encrypted.') }}
            </p>
        @endif
    </form>
</x-guest-layout>
