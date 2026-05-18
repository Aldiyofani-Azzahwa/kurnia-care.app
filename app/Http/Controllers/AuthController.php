<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    // HALAMAN LOGIN
    public function login()
    {
        return view('auth.login');
    }

    // HALAMAN REGISTER
    public function register()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')
        ->with('success', 'Register berhasil');
    }

    // PROSES LOGIN
    public function loginPost(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->with(
            'error',
            'Email atau password salah'
        );
    }

    // LOGOUT
    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}