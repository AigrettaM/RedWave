<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Profile;
use App\Models\Donor;
use App\Models\User;
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
        
        return view('welcome', compact(
            'bloodAnalytics', 
            'criticalStocks', 
            'totalDonors', 
            'donorStatistics',
            'detailedStocks'
        ));
    }

    // Dashboard admin untuk blood bank management system [[0]](#__0)
    public function adminDashboard()
    {
        // Dashboard statistics untuk blood bank administrators [[1]](#__1)
        $dashboardStats = [
            'pending_donations' => Donor::where('status', 'pending')->count(),
            'total_donors' => Donor::count(),
            'today_registrations' => Donor::whereDate('created_at', today())->count(),
            'completed_donations' => Donor::where('status', 'completed')->count(),
        ];

        // Blood stock alerts untuk critical stock management [[2]](#__2)
        $stockAlerts = collect();
        try {
            $criticalStocks = BloodStock::getCriticalStock();
            foreach($criticalStocks as $stock) {
                if($stock->quantity <= 5) {
                    $stockAlerts->push([
                        'blood_type' => $stock->blood_type,
                        'message' => $stock->quantity == 0 ? 'Stok habis!' : "Stok tinggal {$stock->quantity} unit"
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Handle jika BloodStock model belum ada
            $stockAlerts = collect();
        }

        // Blood type distribution untuk donor analytics [[3]](#__3)
        $bloodTypeDistribution = Donor::select('blood_type')
            ->selectRaw('count(*) as count')
            ->groupBy('blood_type')
            ->get();

        // Recent activities untuk activity tracking
        $recentActivities = collect();
        $recentDonors = Donor::with('user')->latest()->limit(10)->get();
        
        foreach($recentDonors as $donor) {
            $recentActivities->push([
                'time' => $donor->created_at,
                'message' => ($donor->user->name ?? 'User') . ' mendaftar sebagai donor ' . $donor->blood_type,
                'status' => $donor->status,
                'icon' => 'fa-tint',
                'color' => $donor->status == 'pending' ? 'warning' : ($donor->status == 'approved' ? 'success' : 'primary')
            ]);
        }

        // Monthly donations untuk trend analysis
        $monthlyDonations = collect();
        for($i = 1; $i <= 12; $i++) {
            $count = Donor::whereMonth('created_at', $i)
                         ->whereYear('created_at', date('Y'))
                         ->count();
            $monthlyDonations->push(['month' => $i, 'count' => $count]);
        }

        // Detailed stocks dengan fallback
        try {
            $detailedStocks = BloodStock::getDetailedStock();
        } catch (\Exception $e) {
            // Fallback jika BloodStock belum ada
            $detailedStocks = collect([
                (object)['blood_type' => 'A+', 'quantity' => 0],
                (object)['blood_type' => 'A-', 'quantity' => 0],
                (object)['blood_type' => 'B+', 'quantity' => 0],
                (object)['blood_type' => 'B-', 'quantity' => 0],
                (object)['blood_type' => 'AB+', 'quantity' => 0],
                (object)['blood_type' => 'AB-', 'quantity' => 0],
                (object)['blood_type' => 'O+', 'quantity' => 0],
                (object)['blood_type' => 'O-', 'quantity' => 0],
            ]);
        }

        return view('admin.dashboard', compact(
            'dashboardStats',
            'stockAlerts', 
            'bloodTypeDistribution',
            'recentActivities',
            'monthlyDonations',
            'detailedStocks'
        ));
    }

    // API endpoint untuk real-time data updates
    public function dashboardData(Request $request)
    {
        $type = $request->get('type');
        
        switch($type) {
            case 'critical_stocks':
                try {
                    $criticalStocks = BloodStock::getCriticalStock()
                        ->where('quantity', '<=', 5);
                    return response()->json($criticalStocks);
                } catch (\Exception $e) {
                    return response()->json([]);
                }
                break;
                
            case 'pending_count':
                return response()->json([
                    'count' => Donor::where('status', 'pending')->count()
                ]);
                break;
                
            default:
                return response()->json(['error' => 'Invalid type']);
        }
    }
}
