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
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        $userRole = $this->normalizeRole($request->user()->role);

        $allowedRoles = array_filter(array_map(function ($role) {
            return $this->normalizeRole($role);
        }, $roles));

        abort_if(
            ! $userRole || empty($allowedRoles) || ! in_array($userRole, $allowedRoles, true),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        return $next($request);
    }

    private function normalizeRole(?string $role): ?string
    {
        $role = $role ? strtolower(trim($role)) : null;

        return match ($role) {
            'user', 'pasien' => 'pasien',
            'doctor', 'dokter' => 'dokter',
            'admin' => 'admin',
            default => $role,
        };
    }
}