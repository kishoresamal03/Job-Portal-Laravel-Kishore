<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MobileVerificationController extends Controller
{
    /**
     * Show mobile verification page.
     */
    public function show()
    {
        return view('auth.verify-mobile');
    }

    /**
     * Send OTP through SMSGatewayCenter.
     */
    public function send(OtpDeliveryService $deliveryService)
    {
        $user = Auth::user();

        if ($user->mobile_verified_at) {
            return redirect()->route('dashboard');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $deliveryService->sendMobileOtp($user, '');

        return back()->with(
            'status',
            'Your mobile verification OTP has been sent to your mobile number.'
        );
    }

    /**
     * Verify OTP through SMSGatewayCenter.
     */
    public function verify(
        Request $request,
        OtpDeliveryService $deliveryService
    ) {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        if ($user->mobile_verified_at) {
            return redirect()->route('dashboard');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $verified = $deliveryService->verifyMobileOtp(
            $user,
            $request->otp
        );

        if (!$verified) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ]);
        }

        $user->update([
            'mobile_verified_at' => now(),
        ]);

        return redirect()->route('dashboard')->with(
            'status',
            'Mobile number verified successfully.'
        );
    }
}
