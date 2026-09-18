<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Models\CarSavedSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarSavedSearchController extends Controller
{
    public function index(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['searches' => []]);
        }

        $searches = CarSavedSearch::where('user_id', auth()->id())
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'searches' => $searches,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'require_auth' => true,
                'message' => __('Axtarışı yadda saxlamaq üçün zəhmət olmasa daxil olun.'),
            ], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'url_query' => 'required|string',
            'criteria' => 'nullable|array',
        ]);

        $savedSearch = CarSavedSearch::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'url_query' => $validated['url_query'],
            'criteria' => $validated['criteria'] ?? null,
            'alert_enabled' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Axtarışınız uğurla yadda saxlanıldı!'),
            'search' => $savedSearch,
        ]);
    }

    public function destroy(CarSavedSearch $savedSearch): JsonResponse
    {
        if (!auth()->check() || $savedSearch->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => __('İcazə yoxdur.')], 403);
        }

        $savedSearch->delete();

        return response()->json([
            'success' => true,
            'message' => __('Yadda saxlanılmış axtarış silindi.'),
        ]);
    }
}
