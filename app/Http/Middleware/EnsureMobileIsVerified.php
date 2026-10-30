<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMobileIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->mobile_verified_at) {
            return redirect()->route('mobile.verification.notice');
        }

        return $next($request);
    }
}