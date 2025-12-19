<style>
/* === GREEN / WOODY THEME FOR BREEZE AUTH === */

/* Page background */
body {
    background: linear-gradient(
        rgba(59, 93, 80, 0.85),
        rgba(59, 93, 80, 0.85)
    ),
    url('{{ asset("vendor/furni/images/wood-bg.jpg") }}') center / cover no-repeat;
}

/* Card (Breeze default container) */
.max-w-md {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
}

/* Titles */
h2, h3 {
    color: #3b5d50;
    font-weight: 700;
}

/* Inputs */
input {
    border-radius: 8px !important;
}

input:focus {
    border-color: #3b5d50 !important;
    box-shadow: 0 0 0 0.15rem rgba(59,93,80,.25) !important;
}

/* Primary button */
button {
    background-color: #3b5d50 !important;
    border: none !important;
}

button:hover {
    background-color: #2f4b41 !important;
}

/* Links */
a {
    color: #3b5d50 !important;
}
</style>

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
