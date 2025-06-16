<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Profile;
use App\Models\Donor;
use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Sekarang aman karena hanya untuk authenticated users
    }

    public function userHome()
    {
        $user = auth()->user();
        $profile = $user->profile;
        
        // Basic stats for user dashboard
        $userStats = [
            'has_profile' => $profile ? true : false,
            'donor_count' => $user->donors()->count(),
            'last_donation' => $user->donors()->latest()->first(),
            'upcoming_events' => Event::where('status', 'published')
                                    ->where('event_date', '>=', now())
                                    ->latest()
                                    ->limit(3)
                                    ->get(),
            'recent_articles' => Article::where('status', 'published')
                                       ->latest()
                                       ->limit(3)
                                       ->get(),
        ];
        
        return view('user.home', compact('userStats'));
    }

    public function adminDashboard()
    {
        // Hitung statistik untuk dashboard
        $totalUsers = User::count();
        $totalProfiles = Profile::count();
        $totalDonors = Donor::count();
        $totalEvents = Event::count();
        $totalLokasi = \App\Models\Lokasi::count();
        
        // Recent activity
        $recentUsers = User::latest()->limit(5)->get();
        $recentDonors = Donor::with('user')->latest()->limit(5)->get();
        
        // Monthly statistics
        $monthlyStats = [
            'users' => User::whereMonth('created_at', now()->month)->count(),
            'donors' => Donor::whereMonth('created_at', now()->month)->count(),
            'events' => Event::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalProfiles', 
            'totalDonors', 
            'totalEvents', 
            'totalLokasi',
            'recentUsers',
            'recentDonors',
            'monthlyStats'
        ));
    }
}
