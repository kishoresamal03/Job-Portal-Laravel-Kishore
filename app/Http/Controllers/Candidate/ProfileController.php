<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('candidate.profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'mobile')->ignore($user->id),
            ],
        ]);

        if ($validated['mobile'] !== $user->mobile) {
            $validated['mobile_verified_at'] = null;
        }

        $user->update($validated);

        return back()->with('status', 'Profile updated successfully.');
    }
}