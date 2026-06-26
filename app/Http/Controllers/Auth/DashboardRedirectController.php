<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $role = $this->normalizeRole($request->user()?->role);

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dokter' => redirect()->route('doctor.dashboard'),
            'pasien' => redirect()->route('user.dashboard'),
            default => abort(403, 'Role akun tidak dikenali.'),
        };
    }

    private function normalizeRole(?string $role): ?string
    {
        $role = $role ? strtolower(trim($role)) : null;

        return match ($role) {
            'admin' => 'admin',
            'dokter', 'doctor' => 'dokter',
            'pasien', 'user' => 'pasien',
            default => $role,
        };
    }
}