<?php

namespace ZepFietje\Seeable\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ZepFietje\Seeable\Concerns\Seeable;

class SeeUser
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, mixed $response): void
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
