<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if ($user && $user->role === User::ROLE_USER && str_starts_with($permission, 'update_')) {
            return $next($request);
        }

        if (! $user || ! $user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this action.');
        }

        return $next($request);
    }
}
