<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    function login(Request $req)
    {
        $remember = $req->filled('remember');
                
        if (Auth::attempt($req->only('email', 'password'), $remember)) {
            $req->session()->regenerate();
            return redirect()->intended('dashboard')->with('status', __('auth.login'));
        }
        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->to('/')->with('status', __('auth.logout'));
    }

    function register(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->save();

        Auth::login($user);
        return redirect()->to('dashboard')->with('status', __('auth.register'));
    }
}