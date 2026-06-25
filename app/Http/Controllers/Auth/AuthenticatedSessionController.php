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

        $user = $request->user();
        $role = $this->normalizeRole($user->role);

        $intendedUrl = $request->session()->get('url.intended');
        $path = $intendedUrl ? parse_url($intendedUrl, PHP_URL_PATH) : null;

        if ($role === 'admin') {
            if ($path && str_starts_with($path, '/admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        if ($role === 'dokter') {
            if ($path && str_starts_with($path, '/doctor')) {
                return redirect()->intended(route('doctor.dashboard'));
            }

            $request->session()->forget('url.intended');

            return redirect()->route('doctor.dashboard');
        }

        if ($role === 'pasien') {
            if ($path && str_starts_with($path, '/user')) {
                return redirect()->intended(route('user.dashboard'));
            }

            $request->session()->forget('url.intended');

            return redirect()->route('user.dashboard');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('error', 'Role akun tidak dikenali. Silakan hubungi admin.');
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
     * Samakan role lama dan role baru.
     */
    private function normalizeRole(?string $role): ?string
    {
        return match ($role) {
            'admin' => 'admin',
            'dokter', 'doctor' => 'dokter',
            'pasien', 'user' => 'pasien',
            default => $role,
        };
    }
}