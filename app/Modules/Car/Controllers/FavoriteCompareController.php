<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\Compare;
use App\Modules\Car\Models\Favorite;
use App\Modules\Shared\Controllers\StaticPageController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FavoriteCompareController extends Controller
{
    /**
     * Sevimlilərə əlavə et / çıxar (Backend Toggle)
     */
    public function toggleFavorite(Request $request): JsonResponse
    {
        $id = (int) ($request->input('car_id') ?: ($request->input('id') ?: $request->input('property_id')));

        $car = $id ? Car::find($id) : null;

        if (! $car) {
            return response()->json(['success' => false, 'message' => 'Car not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query()->where('car_id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $isFavorite = false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'car_id' => $id,
            ]);
            $isFavorite = true;
        }

        // Seçilmişlər səhifəsinin keşini təmizlə
        Cache::forget(StaticPageController::favoritesCacheKey($userId, $sessionId));

        $countQuery = Favorite::query();
        if ($userId) {
            $countQuery->where('user_id', $userId);
        } else {
            $countQuery->where('session_id', $sessionId);
        }

        $ids = $countQuery->pluck('car_id')
            ->filter()
            ->values()
            ->toArray();
        $count = count($ids);

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
            'count' => $count,
            'ids' => $ids,
        ]);
    }

    /**
     * Cari istifadəçinin bütün favorit ID-ləri
     */
    public function getFavorites(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $ids = $query->pluck('car_id')
            ->filter()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'count' => count($ids),
            'ids' => $ids,
        ]);
    }

    /**
     * Bütün favoritləri təmizlə
     */
    public function clearFavorites(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();
        Cache::forget(StaticPageController::favoritesCacheKey($userId, $sessionId));

        return response()->json([
            'success' => true,
            'count' => 0,
            'ids' => [],
        ]);
    }

    /**
     * Müqayisəyə əlavə et / çıxar (Backend Toggle)
     */
    public function toggleCompare(Request $request): JsonResponse
    {
        $id = (int) ($request->input('car_id') ?: ($request->input('id') ?: $request->input('property_id')));

        $car = $id ? Car::find($id) : null;

        if (! $car) {
            return response()->json(['success' => false, 'message' => 'Car not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query()->where('car_id', $id);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $isCompared = false;
        } else {
            $countQuery = Compare::query();
            if ($userId) {
                $countQuery->where('user_id', $userId);
            } else {
                $countQuery->where('session_id', $sessionId);
            }

            if ($countQuery->count() >= 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ən çox 4 avtomobil müqayisə edilə bilər.',
                    'limit_reached' => true,
                ], 422);
            }

            Compare::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'car_id' => $id,
            ]);
            $isCompared = true;
        }

        // Müqayisə səhifəsinin keşini təmizlə
        Cache::forget(StaticPageController::comparesCacheKey($userId, $sessionId));

        $countQuery = Compare::query();
        if ($userId) {
            $countQuery->where('user_id', $userId);
        } else {
            $countQuery->where('session_id', $sessionId);
        }

        $ids = $countQuery->pluck('car_id')
            ->filter()
            ->values()
            ->toArray();
        $count = count($ids);

        return response()->json([
            'success' => true,
            'is_compared' => $isCompared,
            'count' => $count,
            'ids' => $ids,
        ]);
    }

    /**
     * Cari istifadəçinin bütün müqayisə ID-ləri
     */
    public function getCompares(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $ids = $query->pluck('car_id')
            ->filter()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'count' => count($ids),
            'ids' => $ids,
        ]);
    }

    /**
     * Bütün müqayisə siyahısını təmizlə
     */
    public function clearCompares(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();
        Cache::forget(StaticPageController::comparesCacheKey($userId, $sessionId));

        return response()->json([
            'success' => true,
            'count' => 0,
            'ids' => [],
        ]);
    }
}
