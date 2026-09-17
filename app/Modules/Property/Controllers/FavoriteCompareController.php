<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Models\Car;
use App\Modules\Property\Models\Compare;
use App\Modules\Property\Models\Favorite;
use App\Modules\Property\Models\Property;
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
        $id = (int) ($request->input('car_id') ?: ($request->input('property_id') ?: $request->input('id')));
        
        $isCar = $id && Car::where('id', $id)->exists();
        $isProperty = ! $isCar && $id && Property::where('id', $id)->exists();

        if (! $isCar && ! $isProperty) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Favorite::query();
        if ($isCar) {
            $query->where('car_id', $id);
        } else {
            $query->where('property_id', $id);
        }

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
                'car_id' => $isCar ? $id : null,
                'property_id' => $isProperty ? $id : null,
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

        $ids = (clone $countQuery)->selectRaw('COALESCE(car_id, property_id) as target_id')
            ->pluck('target_id')
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

        $ids = $query->selectRaw('COALESCE(car_id, property_id) as target_id')
            ->pluck('target_id')
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
        $id = (int) ($request->input('car_id') ?: ($request->input('property_id') ?: $request->input('id')));
        
        $isCar = $id && Car::where('id', $id)->exists();
        $isProperty = ! $isCar && $id && Property::where('id', $id)->exists();

        if (! $isCar && ! $isProperty) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'default-session';

        $query = Compare::query();
        if ($isCar) {
            $query->where('car_id', $id);
        } else {
            $query->where('property_id', $id);
        }

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
                    'message' => 'Ən çox 4 elan müqayisə edilə bilər.',
                    'limit_reached' => true,
                ], 422);
            }

            Compare::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'car_id' => $isCar ? $id : null,
                'property_id' => $isProperty ? $id : null,
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

        $ids = (clone $countQuery)->selectRaw('COALESCE(car_id, property_id) as target_id')
            ->pluck('target_id')
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

        $ids = $query->selectRaw('COALESCE(car_id, property_id) as target_id')
            ->pluck('target_id')
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
