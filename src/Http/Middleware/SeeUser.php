<?php

namespace ZepFietje\Seeable\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse as FileResponse;
use ZepFietje\Seeable\Concerns\Seeable;

class SeeUser
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|FileResponse
    {
        return $next($request);
    }

    public function terminate(Request $request, Response|RedirectResponse|JsonResponse|FileResponse $response): void
    {
        $user = $request->user();

        if ($user === null) {
            return;
        }

        if (! in_array(Seeable::class, class_uses_recursive($user))) {
            return;
        }

        $user->see();
    }
}
