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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $role = $user?->normalizedRole();

        if (!in_array($role, ['admin', 'dokter', 'pasien'], true)) {
            return $this->logoutUnknownRole($request);
        }

        return match ($role) {
            'admin' => $this->redirectByRole($request, '/admin', 'admin.dashboard'),
            'dokter' => $this->redirectByRole($request, '/doctor', 'doctor.dashboard'),
            'pasien' => $this->redirectByRole($request, '/user', 'user.dashboard'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Berhasil logout.');
    }

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

    private function logoutUnknownRole(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Role akun tidak dikenali. Silakan hubungi admin.',
            ]);
    }
}