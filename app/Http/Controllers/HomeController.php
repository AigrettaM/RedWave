<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Profile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mendapatkan data analytics yang menggabungkan stok dan statistik donor
        $bloodAnalytics = BloodStock::getBloodAnalytics();
        
        // Mendapatkan stok kritis untuk alert
        $criticalStocks = BloodStock::getCriticalStock();
        
        // Mendapatkan detail stok untuk debugging
        $detailedStocks = BloodStock::getDetailedStock();
        
        // Mendapatkan total donor terdaftar
        $totalDonors = Profile::whereNotNull('blood_type')->count();
        
        // Mendapatkan statistik donor per golongan darah
        $donorStatistics = BloodStock::getDonorStatistics();
        
        // Debug: dump data untuk melihat apa yang sebenarnya diambil
        // dd($bloodAnalytics, $detailedStocks);
        
        return view('welcome', compact(
            'bloodAnalytics', 
            'criticalStocks', 
            'totalDonors', 
            'donorStatistics',
            'detailedStocks'
        ));
    }
}
