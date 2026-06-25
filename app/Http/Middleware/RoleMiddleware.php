<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $roleAliases = [
            'user' => 'pasien',
            'patient' => 'pasien',
            'pasien' => 'pasien',
            'dokter' => 'dokter',
            'doctor' => 'dokter',
            'admin' => 'admin',
        ];

        $userRole = auth()->user()->role;

        $normalizedUserRole = $roleAliases[$userRole] ?? $userRole;

        $allowedRoles = collect($roles)
            ->map(fn ($role) => $roleAliases[$role] ?? $role)
            ->toArray();

        if (! in_array($normalizedUserRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}