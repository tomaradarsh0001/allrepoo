<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (!Auth::check())
            return view('auth.login');
        else
            return redirect()->route('dashboard');
    }
    public function register()
    {
        return view('auth.register');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            "name" => 'required',
            'email' => 'required|email|unique:users'
        ]);
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('pass')
        ];

        $savedUser = User::create($user);
        if ($savedUser) {
            return back()->with('status', 'user saved successfully');
        } else {
            return back()->with('error', 'user can not be saved');
        }
    }

    // SwatiMishra 04-05-2024 11:00AM login checks Start

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->status == 1) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            } else {
                return back()->withErrors([
                    'password' => 'The password you entered is incorrect.',
                ])->onlyInput('email');
            }
        } else if ($user && $user->status == 0) {
            return back()->withErrors([
                'email' => 'Your account is not active. Please contact support.',
            ])->onlyInput('email');
        } else {
            return back()->withErrors([
                'email' => 'No account found with this email.',
            ])->onlyInput('email');
        }
    }

    // SwatiMishra 04-05-2024 11:00AM login checks end

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect()->route('login');
    }
}
