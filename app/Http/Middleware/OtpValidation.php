<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OtpValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $recovery_token = $request->cookie('recovery_token');
        if (!$recovery_token) {
            return response()->json(["msg" => "We could not complete password recovery try again", Response::HTTP_UNAUTHORIZED]);
        }
        return $next($request);
    }
}
