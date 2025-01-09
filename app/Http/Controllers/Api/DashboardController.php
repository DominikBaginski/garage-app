<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garage;

class DashboardController extends Controller
{
    public function stats()
    {
        $garages = Garage::all();

        return response()->json([
            'stats' => [
                'totalGarages' => $garages->count(),
                'availableGarages' => $garages->where('is_available', 1)->count(),
            ],
        ]);
    }
}
