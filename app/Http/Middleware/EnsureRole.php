<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, int ...$roles): Response
    {
        abort_unless(
            $request->user() && in_array((int) $request->user()->role_id, $roles, true),
            403,
            'لا تملك صلاحية الوصول إلى هذه الصفحة.'
        );

        return $next($request);
    }
}
