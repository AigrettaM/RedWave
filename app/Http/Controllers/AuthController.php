<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(){
        return view("auth.register"); // Gunakan dot notation
    }

    public function registerPost(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user'; // Set default role

        $user->save();

        // Auto login setelah register
        Auth::login($user);
        
        return redirect()->route('user.home')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function login(){
        return view('auth.login'); // Gunakan dot notation
    }

    public function loginPost(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security: regenerate session
            
            $user = Auth::user();
            
            // Redirect berdasarkan role dengan pengecekan yang lebih robust
            if ($user->role === 'admin' || (isset($user->is_admin) && $user->is_admin == 1)) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Login Berhasil - Selamat datang Admin!');
            } else {
                return redirect()->intended(route('user.home'))
                    ->with('success', 'Login Berhasil');
            }
        }
        
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request){
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome')->with('success', 'Logout berhasil');
    }
}
