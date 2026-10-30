
<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-xl font-semibold text-gray-800">
            Candidate Login
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Choose your preferred login method.
        </p>
    </div>

    <div class="flex gap-3 mb-6">
        <button
            type="button"
            id="otpTab"
            onclick="showLoginMethod('otp')"
            class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-white"
        >
            Email / Mobile OTP
        </button>

        <button
            type="button"
            id="passwordTab"
            onclick="showLoginMethod('password')"
            class="flex-1 rounded-md bg-gray-200 px-3 py-2 text-gray-700"
        >
            Candidate ID / Password
        </button>
    </div>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <!-- Method 1: Email or Mobile OTP -->
    <div id="otpLogin">
        <form method="POST" action="{{ route('login.otp.send') }}">
            @csrf

            <div>
                <x-input-label
                    for="identifier"
                    value="Registered Email or Mobile Number"
                />

                <x-text-input
                    id="identifier"
                    class="block mt-1 w-full"
                    type="text"
                    name="identifier"
                    :value="old('identifier')"
                    placeholder="Enter email or mobile number"
                    required
                    autofocus
                />

                <x-input-error
                    :messages="$errors->get('identifier')"
                    class="mt-2"
                />
            </div>

            <p class="text-xs text-gray-500 mt-2">
                During local testing, mobile OTPs are sent to your registered email.
            </p>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    Send OTP
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Method 2: Candidate ID and Password -->
    <div id="passwordLogin" class="hidden">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-input-label
                    for="user_id"
                    value="Candidate User ID"
                />

                <x-text-input
                    id="user_id"
                    class="block mt-1 w-full"
                    type="text"
                    name="user_id"
                    :value="old('user_id')"
                    placeholder="Example: CANDXXXXXXXX"
                    autocomplete="username"
                    required
                />

                <x-input-error
                    :messages="$errors->get('user_id')"
                    class="mt-2"
                />
            </div>

            <div class="mt-4">
                <x-input-label
                    for="password"
                    value="Password"
                />

                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                />

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />
            </div>

            <div class="block mt-4">
                <label for="remember" class="inline-flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm"
                    >

                    <span class="ms-2 text-sm text-gray-600">
                        Remember me
                    </span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a
                        class="underline text-sm text-gray-600"
                        href="{{ route('password.request') }}"
                    >
                        Forgot password?
                    </a>
                @endif

                <x-primary-button>
                    Log In
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?

        <a
            href="{{ route('register') }}"
            class="underline text-indigo-600"
        >
            Register here
        </a>
    </div>

    <script>
        function showLoginMethod(method) {
            const otpLogin = document.getElementById('otpLogin');
            const passwordLogin = document.getElementById('passwordLogin');

            const otpTab = document.getElementById('otpTab');
            const passwordTab = document.getElementById('passwordTab');

            if (method === 'otp') {
                otpLogin.classList.remove('hidden');
                passwordLogin.classList.add('hidden');

                otpTab.className =
                    'flex-1 rounded-md bg-indigo-600 px-3 py-2 text-white';

                passwordTab.className =
                    'flex-1 rounded-md bg-gray-200 px-3 py-2 text-gray-700';
            } else {
                passwordLogin.classList.remove('hidden');
                otpLogin.classList.add('hidden');

                passwordTab.className =
                    'flex-1 rounded-md bg-indigo-600 px-3 py-2 text-white';

                otpTab.className =
                    'flex-1 rounded-md bg-gray-200 px-3 py-2 text-gray-700';
            }
        }
    </script>
</x-guest-layout>
