<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /*
    Funciones para la Web
    */
    //Funcion para el login
    function login(Request $req) {
        $remember = $req->filled('remember');
                
        if (Auth::attempt($req->only('email', 'password'), $remember)) {
            $req->session()->regenerate();
            return redirect()->intended('/')->with('status', __('auth.login'));
        }
        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    //Funcion para el logout
    function logout(Request $req) {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->to('/')->with('status', __('auth.logout'));
    }

    //Funcion para el registro
    function register(Request $req) {
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
        return redirect()->to('/')->with('status', __('auth.register'));
    }

    /*
    Funciones para la App
    */
    //Funcion para el login
    function loginMovil(Request $req){
        if (Auth::attempt($req->only('email', 'password'), true)) {
            // Si el usuario se logueó correctamente, devuelve el usuario
            $Usuario = User::where('email', $req->email)->select('id', 'name', 'email', 'created_at')->first();
            return response()->json(['status' => 'ok', 'user' => $Usuario]);
        } else {
            // Si el usuario no se logueó correctamente, regresa un mensaje de error
            return response()->json(['status' => 'error']);

        }
    }

    //Funcion para el registro
    function registerMovil(Request $req) {
        $req->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->save();

        //Auth::login($user);
        $Usuario = User::where('email', $req->email)->select('id', 'name', 'email', 'created_at')->first();
        return response()->json(['status' => 'ok', 'user' => $Usuario]);
    }
}