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

        $user = $request->user();
        $role = $user?->role ?? 'member';

        // Pilih route tujuan berdasarkan role
        if ($role === 'admin') {
            $target = route('dashboard.admin', absolute: false);
        } 
        elseif ($role === 'tenant') {
            // default untuk member/penyewa/tenant dsb.
            $target = route('dashboard.tenant', absolute: false);
        }
        elseif ($role === 'penyewa') {
            $target = route('dashboard.penyewa', absolute: false);
        }
        else {
            $target = route('dashboard.tenant', absolute: false);
        }

        return redirect()->intended($target);
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
