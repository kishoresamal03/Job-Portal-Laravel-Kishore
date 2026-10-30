<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function generate(User $user, string $purpose): string
    {
        $otp = (string) random_int(100000, 999999);

        $user->otps()
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        $user->otps()->create([
            'otp_hash' => Hash::make($otp),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(5),
        ]);

        return $otp;
    }

    public function verify(
        User $user,
        string $purpose,
        string $enteredOtp
    ): bool {
        // Retrieve an actual Eloquent Otp model.
        $record = Otp::query()
            ->where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$record) {
            return false;
        }

        if ($record->expires_at->isPast()) {
            return false;
        }

        if ($record->attempts >= 5) {
            return false;
        }

        // Count the verification attempt.
        $record->attempts = $record->attempts + 1;
        $record->save();

        if (!Hash::check($enteredOtp, $record->otp_hash)) {
            return false;
        }

        $record->verified_at = now();
        $record->save();

        return true;
    }
}
