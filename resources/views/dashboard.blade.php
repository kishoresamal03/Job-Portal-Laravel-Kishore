<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Candidate Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">
                        Welcome, {{ Auth::user()->name }}!
                    </h3>
<a href="{{ route('candidate.profile.edit') }}"  style="background:blue;padding:10px 20px"
   class="inline-block mt-4 rounded-md bg-indigo-600 px-4 py-2 text-white">
    View / Edit My Profile
</a>
                    <p class="mt-2">
                        Your Candidate ID: {{ Auth::user()->user_id }}
                    </p>

                    <div class="mt-6">
                        @if (Auth::user()->mobile_verified_at)
                            <p class="text-green-600">
                                Mobile number verified.
                            </p>
                        @else
                            <p class="text-red-600">
                                Your mobile number is not verified yet.
                            </p>

                            <a href="{{ route('mobile.verification.notice') }}"
                               class="inline-block mt-3 rounded-md bg-blue-600 px-4 py-2 text-white">
                                Verify Mobile Number
                            </a>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>