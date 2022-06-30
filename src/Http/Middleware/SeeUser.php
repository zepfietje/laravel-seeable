<?php

namespace ZepFietje\Seeable\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeeUser
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        return $next($request);
    }

    public function terminate(Request $request, Response|RedirectResponse|JsonResponse $response): void
    {
        $request->user()?->see();
    }
}
