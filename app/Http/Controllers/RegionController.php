<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class RegionController extends Controller
{
    public function provinces(): JsonResponse
    {
        $provinces = Province::orderBy('name')->get([
            'code',
            'name',
        ]);

        return response()->json($provinces);
    }

    public function cities(string $provinceCode): JsonResponse
    {
        $cities = City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get([
                'code',
                'name',
            ]);

        return response()->json($cities);
    }

    public function districts(string $cityCode): JsonResponse
    {
        $districts = District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get([
                'code',
                'name',
            ]);

        return response()->json($districts);
    }

    public function villages(string $districtCode): JsonResponse
    {
        $villages = Village::where('district_code', $districtCode)
            ->orderBy('name')
            ->get([
                'code',
                'name',
            ]);

        return response()->json($villages);
    }
}