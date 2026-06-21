<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;   // WAJIB ADA
use App\Models\User;

class AuthController extends Controller
{

public function showLogin()
{
    return view('auth.login');
}

public function showRegister()
{
    return view('auth.register');
}

public function register(Request $request)
{

    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|min:6'
    ]);

    User::create([

        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)

    ]);

    return redirect('/login');
}

public function login(Request $request)
{
    $credentials = $request->only('email','password');

    if(Auth::attempt($credentials)){
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Arahkan ke admin
        }

        // TAMBAHKAN BARIS INI:
        // Memberi sinyal agar modal muncul di welcome.blade.php
        session()->flash('show_survey', true);

        return redirect()->intended('/'); // User biasa ke halaman utama
    }

    return back()->with('error','Email atau password salah');
}

public function logout(Request $request)
{

    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
}


}