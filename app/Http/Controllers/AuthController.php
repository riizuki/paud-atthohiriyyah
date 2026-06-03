<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PPDB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Check if user with same email exists
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link account
                    $user->update([
                        'google_id' => $googleUser->id,
                        'social_type' => 'google',
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'social_type' => 'google',
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(Str::random(24)), // Random password
                        'role' => 'user',
                    ]);
                }
            }

            Auth::login($user);

            return redirect('/'); // Redirect to home or dashboard
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong while logging in with Google.');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json([
                'success' => true,
                'user' => Auth::user()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return response()->json(['success' => true]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect('/login');
    }

    public function me()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ppdb = null;
            if ($user->role === 'siswa' && $user->account_id) {
                $ppdb = PPDB::where('nik', $user->account_id)->first();
            }
            return response()->json([
                'success' => true,
                'user' => $user,
                'ppdb_data' => $ppdb
            ]);
        }
        return response()->json(['success' => false, 'user' => null]);
    }
}
