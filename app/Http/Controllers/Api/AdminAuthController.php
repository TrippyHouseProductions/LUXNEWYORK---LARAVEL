<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{

    // ...inside your controller...
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Only allow admins
        $credentials['user_type'] = 'admin';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            // NOTE this is not harmful this error is from intelephense
            $token = $user->createToken('web-login-token')->plainTextToken;
            // Pass this token to the view (as a session flash or as a JS variable)
            return redirect()->intended('admin/dashboard')->with('api_token', $token);
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not an admin.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Delete all tokens for this user
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }

        // Log out from the web guard and invalidate the session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('logged_out', true);
    }


}
