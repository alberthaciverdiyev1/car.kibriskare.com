<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarReportController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'reason' => 'required|string|in:' . implode(',', array_keys(CarReport::REASONS)),
            'description' => 'nullable|string|max:1000',
            'contact_info' => 'nullable|string|max:150',
        ]);

        CarReport::create([
            'car_id' => $validated['car_id'],
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'contact_info' => $validated['contact_info'] ?? null,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Bildiriminiz alındı. İlan incelenecektir. Təşəkkür edirik!'),
        ]);
    }
}
