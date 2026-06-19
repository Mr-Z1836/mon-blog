<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->hasVerifiedEmail()) {
            return $next($request);
        }

        return back()
            ->withErrors([
                'email_verification' => 'Veuillez vérifier votre email pour accéder à cette fonctionnalité.',
            ])
            ->with('verification_resend_available', true);
    }
}
