<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $role = $request->user()?->normalizedRole();

        if (! in_array($role, ['admin', 'dokter', 'pasien'], true)) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Role akun tidak dikenali. Silakan hubungi admin.',
                ]);
        }

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dokter' => redirect()->route('doctor.dashboard'),
            'pasien' => redirect()->route('user.dashboard'),
        };
    }
}