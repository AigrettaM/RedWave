<?php

namespace Database\Seeders;

use App\Models\BloodStock;
use Illuminate\Database\Seeder;

class BloodStockSeeder extends Seeder
{
    public function run()
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['POSITIF', 'NEGATIF'];
        
        foreach($bloodTypes as $bloodType) {
            foreach($rhesusTypes as $rhesus) {
                BloodStock::create([
                    'blood_type' => $bloodType,
                    'rhesus' => $rhesus,
                    'stock_quantity' => match($bloodType) {
                        'A' => $rhesus === 'POSITIF' ? 45 : 12,
                        'B' => $rhesus === 'POSITIF' ? 30 : 8,
                        'AB' => $rhesus === 'POSITIF' ? 25 : 5,
                        'O' => $rhesus === 'POSITIF' ? 5 : 5,
                        default => 20
                    },
                    'last_updated_date' => now(),
                    'notes' => 'Initial stock data'
                ]);
            }
        }
    }
}
