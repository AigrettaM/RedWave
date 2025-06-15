<?php
// app/Models/BloodStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BloodStock extends Model
{
    protected $fillable = [
        'blood_type', 
        'rhesus', 
        'stock_quantity', 
        'last_updated_date', 
        'notes'
    ];

    protected $casts = [
        'last_updated_date' => 'date',
    ];

    // Method untuk mendapatkan stok gabungan per golongan darah (POSITIF + NEGATIF)
    public static function getStockWithStatus()
    {
        return self::selectRaw('
            blood_type,
            SUM(stock_quantity) as stock_quantity,
            CASE 
                WHEN SUM(stock_quantity) < 15 THEN "Kritis"
                WHEN SUM(stock_quantity) >= 15 AND SUM(stock_quantity) < 30 THEN "Rendah"
                WHEN SUM(stock_quantity) >= 30 AND SUM(stock_quantity) < 50 THEN "Normal"
                ELSE "Aman"
            END as status,
            CASE 
                WHEN SUM(stock_quantity) < 15 THEN 1
                WHEN SUM(stock_quantity) >= 15 AND SUM(stock_quantity) < 30 THEN 2
                WHEN SUM(stock_quantity) >= 30 AND SUM(stock_quantity) < 50 THEN 3
                ELSE 4
            END as priority_rank
        ')
        ->groupBy('blood_type')
        ->orderBy('priority_rank', 'asc')
        ->orderBy('stock_quantity', 'asc')
        ->get();
    }

    // Method untuk mendapatkan detail stok per rhesus
    public static function getDetailedStock()
    {
        return self::selectRaw('
            blood_type,
            rhesus,
            stock_quantity,
            CASE 
                WHEN stock_quantity < 15 THEN "Kritis"
                WHEN stock_quantity >= 15 AND stock_quantity < 30 THEN "Rendah"
                WHEN stock_quantity >= 30 AND stock_quantity < 50 THEN "Normal"
                ELSE "Aman"
            END as status
        ')
        ->orderBy('blood_type')
        ->orderBy('rhesus')
        ->get();
    }

    // Method untuk mendapatkan hanya stok kritis (gabungan)
    public static function getCriticalStock()
    {
        return self::selectRaw('
            blood_type,
            SUM(stock_quantity) as total_stock
        ')
        ->groupBy('blood_type')
        ->havingRaw('SUM(stock_quantity) < 15')
        ->get();
    }

    // Method untuk mendapatkan statistik donor berdasarkan golongan darah
    public static function getDonorStatistics()
    {
        return DB::table('profiles')
            ->select('blood_type', DB::raw('COUNT(*) as donor_count'))
            ->whereNotNull('blood_type')
            ->groupBy('blood_type')
            ->get();
    }

    // Method untuk menggabungkan stok dan statistik donor
    public static function getBloodAnalytics()
    {
        $stocks = self::getStockWithStatus();
        $donorStats = self::getDonorStatistics();
        
        $analytics = [];
        
        foreach(['A', 'B', 'AB', 'O'] as $bloodType) {
            $stock = $stocks->where('blood_type', $bloodType)->first();
            $donorStat = $donorStats->where('blood_type', $bloodType)->first();
            
            $analytics[] = (object)[
                'blood_type' => $bloodType,
                'stock_quantity' => $stock ? $stock->stock_quantity : 0,
                'status' => $stock ? $stock->status : 'Tidak Ada Data',
                'priority_rank' => $stock ? $stock->priority_rank : 5,
                'donor_count' => $donorStat ? $donorStat->donor_count : 0,
            ];
        }
        
        return collect($analytics)->sortBy('priority_rank');
    }
}
