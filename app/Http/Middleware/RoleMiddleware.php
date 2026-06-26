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
            return redirect()->route('login');
        }

        $userRole = $request->user()->normalizedRole();

        $allowedRoles = array_filter(array_map(function ($role) {
            return $this->normalizeRole($role);
        }, $roles));

        if (! $userRole || empty($allowedRoles) || ! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    private function normalizeRole(?string $role): ?string
    {
        $role = $role ? strtolower(trim($role)) : null;

        return match ($role) {
            'admin' => 'admin',
            'dokter', 'doctor' => 'dokter',
            'pasien', 'user' => 'pasien',
            default => null,
        };
    }
}