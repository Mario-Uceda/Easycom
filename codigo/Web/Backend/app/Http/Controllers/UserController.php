<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //Funciones para la Web
    function login(Request $req)
    {
        $remember = $req->filled('remember');
                
        if (Auth::attempt($req->only('email', 'password'), $remember)) {
            $req->session()->regenerate();
            return redirect()->intended('/')->with('status', __('auth.login'));
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
        return redirect()->to('/')->with('status', __('auth.register'));
    }

    //Funciones para la App
    function loginMovil(Request $req)
    {
        $email = $req->validate(['email' => ['required','email','string']])['email'];
        $password = $req->validate(['password' => ['required','string']])['password'];
        
        // Busca el usuario con el email dado
        $usuario = User::where('email', $email)->first();

        // Si no se encontró un usuario con el email dado, regresa un mensaje de error
        if (!$usuario) {
            return response()->json(['mensaje' => 'El usuario no existe'], 401);
        }

        // Verifica si la contraseña dada coincide con la contraseña del usuario
        if (bcrypt($password) == $usuario->pass) {
            // Si la contraseña es correcta, devuelve el id_usuario
            //quiero devolver el id del usuario y la variable administrador
            return response()->json(['status' => 'ok', 'message' => __('auth.login')]);
        } else {
            // Si la contraseña no coincide, regresa un mensaje de error
            return response()->json(['status' => 'error', 'message' => __('auth.failed')]);
        }
    }

    function logoutMovil(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return response()->json(['status' => 'ok', 'message' => __('auth.logout')]);
    }

    function registerMovil(Request $req)
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
        return response()->json(['status' => 'ok', 'message' => __('auth.register')]);
    }
}