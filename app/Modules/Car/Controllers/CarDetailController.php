<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use Illuminate\Http\Request;

class CarDetailController extends Controller
{
    public function __invoke(Request $request, string $slug)
    {
        $car = Car::with([
            'brand',
            'model',
            'bodyType',
            'city',
            'district',
            'images' => fn ($q) => $q->orderBy('sort_order'),
            'features',
            'autosalon',
            'user',
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        // Increment view count
        $car->increment('view_count');

        // Similar cars (same brand or body type)
        $similarCars = Car::with(['brand', 'model', 'bodyType', 'city', 'images', 'autosalon'])
            ->where('status', CarStatus::Active->value)
            ->where('id', '!=', $car->id)
            ->where(function ($q) use ($car) {
                $q->where('brand_id', $car->brand_id)
                  ->orWhere('body_type_id', $car->body_type_id);
            })
            ->limit(4)
            ->get();

        $breadcrumbs = [
            ['label' => 'Ana Səhifə', 'url' => url(app()->getLocale())],
            ['label' => 'Avtomobillər', 'url' => route('listing')],
            ['label' => $car->brand?->name ?? 'Marka', 'url' => route('listing', ['brand_id' => $car->brand_id])],
            ['label' => $car->display_title, 'url' => ''],
        ];

        return view('pages.car.detail', compact('car', 'similarCars', 'breadcrumbs'));
    }
}
