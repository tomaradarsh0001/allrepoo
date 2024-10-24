<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user = User::find(Auth::id());
            $passwordUpdated = $user->update(['password' => Hash::make($request->new_password)]);
            if ($passwordUpdated) {
                return redirect()->back()->with('success', 'Password changed successfully.');
            } else {
                return redirect()->back()->with('failure', config('messages.general.error.unknown'));
            }
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Current password do not match our records.']);
        }
    }

    public function reset()
    {
        return view('auth.reset-password');
    }
}
