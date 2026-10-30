<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpDeliveryService
{
    /**
     * Send OTP to the user's email.
     */
    public function sendEmailOtp(User $user, string $otp): void
    {
        Mail::raw(
            "Your Job Portal verification code is: {$otp}\n\n"
            . "This code expires in 5 minutes. Do not share it with anyone.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your Job Portal OTP');
            }
        );
    }

    /**
     * Local testing only: send mobile OTP to email.
     */
    public function sendMobileOtpToEmailForTesting(
        User $user,
        string $otp
    ): void {
        if (!app()->environment('local')) {
            throw new \RuntimeException(
                'Email-based mobile OTP testing is only allowed locally.'
            );
        }

        Mail::raw(
            "Your mobile verification OTP is: {$otp}\n\n"
            . "This code expires in 5 minutes. Do not share it.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Mobile Verification OTP - Test');
            }
        );
    }

    /**
     * Send OTP through SMSGatewayCenter.
     * The provider generates its own OTP.
     */
    public function sendMobileOtp(User $user, string $otp): void
    {
        $mobile = preg_replace('/\D/', '', $user->mobile);

        // Add India's country code if it is not already present.
        if (!str_starts_with($mobile, '91')) {
            $mobile = '91' . $mobile;
        }

        // Read the configured DLT template ID.
        $templateId = config('services.smsgateway.template_id');

        if (empty($templateId)) {
            throw new \RuntimeException(
                'SMSGatewayCenter DLT template ID is not configured.'
            );
        }

        $response = Http::asForm()
            ->timeout(30)
            ->post(config('services.smsgateway.url'), [
                'userid' => config('services.smsgateway.userid'),
                'password' => config('services.smsgateway.password'),
                'mobile' => $mobile,
                'sendMethod' => 'generate',
                'msgType' => 'text',
                'msg' => 'Your SMSGatewayCenter OTP code is $otp$ Please use the code within 2 minutes. - Demo Message.',
                'medium' => 'sms',
                'codeType' => 'num',
                'codeExpiry' => 300,
                'codeLength' => 6,
                'senderid' => config('services.smsgateway.senderid'),
                'dltTemplateId' => config('services.smsgateway.template_id'),
                'output' => 'json',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'SMSGatewayCenter HTTP error: ' . $response->body()
            );
        }

        $body = trim($response->body());

        Log::info('SMSGatewayCenter OTP Generation Response', [
            'http_status' => $response->status(),
            'response_body' => $body,
            'template_id' => $templateId,
        ]);

        // The provider may return JSON or plain text.
        $data = $response->json();

        if (is_array($data)) {
            $status = $data['response']['status']
                ?? $data['status']
                ?? null;

            $reason = $data['response']['reason']
                ?? $data['reason']
                ?? $body;
        } else {
            $status = null;
            $reason = $body;
        }

        // Handle the provider's plain-text response.
        if ($status === null) {
            if (preg_match('/(?:^|\s)status\s*=\s*success(?:\s|$)/i', $body)) {
                $status = 'success';
            } elseif (preg_match('/(?:^|\s)status\s*=\s*error(?:\s|$)/i', $body)) {
                $status = 'error';
            }
        }

        if ($status !== 'success') {
            throw new \RuntimeException(
                'SMSGatewayCenter OTP failed: ' . $reason
            );
        }
    }

    /**
     * Verify OTP through SMSGatewayCenter.
     */
    public function verifyMobileOtp(User $user, string $otp): bool
    {
        $mobile = preg_replace('/\D/', '', $user->mobile);

        if (!str_starts_with($mobile, '91')) {
            $mobile = '91' . $mobile;
        }

        $response = Http::asForm()
            ->timeout(30)
            ->post(config('services.smsgateway.verify_url'), [
                'userid' => config('services.smsgateway.userid'),
                'password' => config('services.smsgateway.password'),
                'mobile' => $mobile,
                'sendMethod' => 'verify',
                'otp' => $otp,
                'output' => 'json',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                'SMSGatewayCenter verification error: ' . $response->body()
            );
        }

        $body = trim($response->body());

        Log::info('SMSGatewayCenter OTP Verification Response', [
            'http_status' => $response->status(),
            'response_body' => $body,
        ]);

        // The provider may return JSON or plain text.
        $data = $response->json();

        if (is_array($data)) {
            $status = $data['response']['status']
                ?? $data['status']
                ?? null;
        } else {
            $status = null;
        }

        if ($status === null) {
            if (preg_match('/(?:^|\s)status\s*=\s*success(?:\s|$)/i', $body)) {
                $status = 'success';
            } elseif (preg_match('/(?:^|\s)status\s*=\s*error(?:\s|$)/i', $body)) {
                $status = 'error';
            }
        }

        return $status === 'success';
    }
}
