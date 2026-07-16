<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if ($user && $user->status) {
            if ($user->auth_source === 'ad') {
                $ldapService = app(\App\Services\LdapService::class);
                if ($ldapService->authenticate($credentials['username'], $credentials['password'])) {
                    Auth::login($user);
                    $request->session()->regenerate();

                    if ($user->isTechnician() && $user->personnel_status !== 'ready') {
                        $user->update([
                            'personnel_status' => 'ready',
                            'status_estimated_time' => null,
                            'status_note' => null,
                        ]);
                    }

                    return redirect()->intended('dashboard');
                }
            } else {
                if (Auth::attempt($credentials + ['status' => true])) {
                    $request->session()->regenerate();

                    /** @var \App\Models\User|null $user */
                    $loggedInUser = Auth::user();
                    if ($loggedInUser && $loggedInUser->isTechnician() && $loggedInUser->personnel_status !== 'ready') {
                        $loggedInUser->update([
                            'personnel_status' => 'ready',
                            'status_estimated_time' => null,
                            'status_note' => null,
                        ]);
                    }

                    return redirect()->intended('dashboard');
                }
            }
        }

        return back()->withErrors([
            'username' => 'Kombinasi username dan password tidak ditemukan.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
