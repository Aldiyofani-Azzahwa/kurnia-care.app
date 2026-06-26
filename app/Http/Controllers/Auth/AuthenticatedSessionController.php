<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $role = $this->normalizeRole($request->user()?->role);

        return match ($role) {
            'admin' => $this->redirectByRole($request, '/admin', 'admin.dashboard'),
            'dokter' => $this->redirectByRole($request, '/doctor', 'doctor.dashboard'),
            'pasien' => $this->redirectByRole($request, '/user', 'user.dashboard'),
            default => $this->logoutUnknownRole($request),
        };
    }

    /**
     * Proses logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user sesuai role.
     */
    private function redirectByRole(Request $request, string $allowedPathPrefix, string $dashboardRoute): RedirectResponse
    {
        $intendedUrl = $request->session()->get('url.intended');
        $path = $intendedUrl ? parse_url($intendedUrl, PHP_URL_PATH) : null;

        if ($path && str_starts_with($path, $allowedPathPrefix)) {
            return redirect()->intended(route($dashboardRoute));
        }

        $request->session()->forget('url.intended');

        return redirect()->route($dashboardRoute);
    }

    /**
     * Logout jika role tidak dikenali.
     */
    private function logoutUnknownRole(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('error', 'Role akun tidak dikenali. Silakan hubungi admin.');
    }

    /**
     * Samakan role lama dan role baru.
     */
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