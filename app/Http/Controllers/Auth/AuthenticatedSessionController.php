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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        $intendedUrl = redirect()->getIntendedUrl();
        $path = $intendedUrl ? parse_url($intendedUrl, PHP_URL_PATH) : null;

        if ($user->role === 'admin') {
            if ($path && str_starts_with($path, '/admin')) {
                return redirect()->intended('/admin/dashboard');
            }
            $request->session()->forget('url.intended');
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'dokter') {
            if ($path && str_starts_with($path, '/doctor')) {
                return redirect()->intended('/doctor/dashboard');
            }
            $request->session()->forget('url.intended');
            return redirect()->route('doctor.dashboard');
        }

        if ($path && str_starts_with($path, '/user')) {
            return redirect()->intended('/user/dashboard');
        }
        $request->session()->forget('url.intended');
        return redirect()->route('user.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
