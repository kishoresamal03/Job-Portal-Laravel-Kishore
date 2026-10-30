
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Enter the six-digit OTP sent to your registered email address.
        If you used your mobile number to request the OTP, it is sent
        to your email for local testing.
    </div>

    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login.otp.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" value="Enter OTP" />

            <x-text-input
                id="otp"
                class="block mt-1 w-full"
                type="text"
                name="otp"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                autocomplete="one-time-code"
                required
                autofocus
            />

            <x-input-error
                :messages="$errors->get('otp')"
                class="mt-2"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Verify OTP & Log In
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
