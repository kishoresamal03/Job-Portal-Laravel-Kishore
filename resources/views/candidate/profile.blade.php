<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Candidate Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                @if (session('status'))
                    <p class="mb-4 text-green-600">
                        {{ session('status') }}
                    </p>
                @endif

                <form method="POST" action="{{ route('candidate.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block font-medium">Candidate ID</label>
                        <input type="text" value="{{ $user->user_id }}"
                            readonly class="mt-1 w-full rounded-md border-gray-300 bg-gray-100">
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block font-medium">Full Name</label>
                        <input id="name" name="name" type="text"
                            value="{{ old('name', $user->name) }}"
                            required class="mt-1 w-full rounded-md border-gray-300">
                        @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Email Address</label>
                        <input type="email" value="{{ $user->email }}"
                            readonly class="mt-1 w-full rounded-md border-gray-300 bg-gray-100">
                    </div>

                    <div class="mb-4">
                        <label for="mobile" class="block font-medium">Mobile Number</label>
                        <input id="mobile" name="mobile" type="text"
                            value="{{ old('mobile', $user->mobile) }}"
                            required class="mt-1 w-full rounded-md border-gray-300">
                        @error('mobile')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <p class="font-medium">Email Verification</p>
                        <p class="text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->email_verified_at ? 'Verified' : 'Not verified' }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="font-medium">Mobile Verification</p>
                        <p class="text-sm {{ $user->mobile_verified_at ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->mobile_verified_at ? 'Verified' : 'Not verified' }}
                        </p>
                    </div>

                    <button type="submit" style="background:blue;padding:10px 20px"
                        class="rounded-md bg-blue-600 px-4 py-2 text-white">
                        Save Profile
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>