<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Verify your mobile number to continue.
    </div>

    @if (session('status'))
        <div class="mb-4 text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('mobile.verification.send') }}">
        @csrf

        <button type="submit" style="background:red;padding:10px 20px"
            class="w-full rounded-md bg-blue-600 px-4 py-2 text-white">
            Generate Mobile OTP
        </button>
    </form>

    <form method="POST"
        action="{{ route('mobile.verification.verify') }}"
        class="mt-6">
        @csrf

        <label for="otp" class="block text-sm font-medium text-gray-700">
            Enter 6-digit OTP
        </label>

        <input
            id="otp"
            name="otp"
            type="text"
            inputmode="numeric"
            maxlength="6"
            required
            class="mt-1 w-full rounded-md border-gray-300"
        >

        @error('otp')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <button type="submit" style="background:green;padding:10px 20px"
            class="mt-4 w-full rounded-md bg-green-600 px-4 py-2 text-white">
            Verify Mobile
        </button>
    </form>
</x-guest-layout>