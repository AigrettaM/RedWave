<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cek role user
        if ($user->role === 'admin') {
            // Redirect admin ke dashboard admin
            return redirect()->route('admin.admin');
        }
        
        // User biasa tetap ke home
        return view('user.home');
    }
}
