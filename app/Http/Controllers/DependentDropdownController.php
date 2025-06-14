<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Facades\Log;

class DependentDropdownController extends Controller
{
    public function provinces()
    {
        try {
            Log::info('Fetching provinces...');
            $provinces = Province::all();
            Log::info('Provinces found:', ['count' => $provinces->count()]);
            return response()->json($provinces);
        } catch (\Exception $e) {
            Log::error('Error in provinces method:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cities(Request $request)
    {
        try {
            Log::info('Cities request received:', $request->all());
            
            // Cari province berdasarkan ID untuk mendapatkan CODE
            $province = Province::find($request->province_id);
            
            if (!$province) {
                return response()->json(['error' => 'Province not found'], 404);
            }
            
            Log::info('Province found:', ['code' => $province->code, 'name' => $province->name]);
            
            // Cari cities berdasarkan province_code
            $cities = City::where('province_code', $province->code)->get();
            
            Log::info('Cities query result:', [
                'province_code' => $province->code,
                'count' => $cities->count(),
                'cities' => $cities->toArray()
            ]);
            
            return response()->json($cities);
        } catch (\Exception $e) {
            Log::error('Error in cities method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function districts(Request $request)
    {
        try {
            Log::info('Districts request received:', $request->all());
            
            // Cari city berdasarkan ID untuk mendapatkan CODE
            $city = City::find($request->city_id);
            
            if (!$city) {
                return response()->json(['error' => 'City not found'], 404);
            }
            
            Log::info('City found:', ['code' => $city->code, 'name' => $city->name]);
            
            // Cari districts berdasarkan city_code
            $districts = District::where('city_code', $city->code)->get();
            
            Log::info('Districts query result:', [
                'city_code' => $city->code,
                'count' => $districts->count(),
                'districts' => $districts->toArray()
            ]);
            
            return response()->json($districts);
        } catch (\Exception $e) {
            Log::error('Error in districts method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function villages(Request $request)
    {
        try {
            Log::info('Villages request received:', $request->all());
            
            // Cari district berdasarkan ID untuk mendapatkan CODE
            $district = District::find($request->district_id);
            
            if (!$district) {
                return response()->json(['error' => 'District not found'], 404);
            }
            
            Log::info('District found:', ['code' => $district->code, 'name' => $district->name]);
            
            // Cari villages berdasarkan district_code
            $villages = Village::where('district_code', $district->code)->get();
            
            Log::info('Villages query result:', [
                'district_code' => $district->code,
                'count' => $villages->count(),
                'villages' => $villages->toArray()
            ]);
            
            return response()->json($villages);
        } catch (\Exception $e) {
            Log::error('Error in villages method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
