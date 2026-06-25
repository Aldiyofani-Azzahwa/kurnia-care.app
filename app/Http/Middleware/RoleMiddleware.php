<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            abort(403);
        }

        $userRole = $this->normalizeRole($request->user()->role);

        $allowedRoles = array_map(function ($role) {
            return $this->normalizeRole($role);
        }, $roles);

        abort_if(! in_array($userRole, $allowedRoles, true), 403);

        return $next($request);
    }

    private function normalizeRole(?string $role): ?string
    {
        return match ($role) {
            'user', 'pasien' => 'pasien',
            'doctor', 'dokter' => 'dokter',
            'admin' => 'admin',
            default => $role,
        };
    }
}