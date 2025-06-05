<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(){
        return view("auth/register");
    }

    public function registerPost(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Tambahkan 'confirmed' untuk validasi konfirmasi password
        ]);

        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return back()->with('success', 'Register successfully');

    }

    public function login(){
        return view('auth/login');
    }

    public function loginPost(Request $request){
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);
        
         $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect('/home')->with('success', 'Login Berhasil');
        }
        
        return back()->with('error', 'Email or Password salah');
    }
}
