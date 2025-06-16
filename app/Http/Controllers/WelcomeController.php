<?php
// app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Profile;
use App\Models\Event;
use App\Models\Article;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get critical stocks - sesuai dengan method di model
        $criticalStocks = BloodStock::getCriticalStock();
        
        // Get blood analytics - sudah ada di model
        $bloodAnalytics = BloodStock::getBloodAnalytics();
        
        // Get detailed stocks untuk debug view
        $detailedStocks = BloodStock::getDetailedStock();
        
        // Get total donors
        $totalDonors = Profile::whereNotNull('blood_type')->count();
        
        // Stats untuk halaman welcome
        $stats = [
            'total_donors' => $totalDonors,
            'total_events' => Event::where('status', 'published')->count(),
            'recent_articles' => Article::where('status', 'published')
                                      ->latest()
                                      ->limit(3)
                                      ->get(),
        ];

        return view('welcome', compact(
            'criticalStocks',
            'bloodAnalytics', 
            'detailedStocks',
            'totalDonors',
            'stats'
        ));
    }
}
