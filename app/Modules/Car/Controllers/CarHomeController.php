<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarModel;
use App\Modules\Car\Services\CarService;
use App\Modules\Location\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarHomeController extends Controller
{
    public function __construct(
        protected CarService $carService
    ) {}

    public function __invoke(Request $request, ?string $first = null, ?string $second = null, ?string $third = null)
    {
        // Handle localized route prefixes e.g. /tr, /en, /az, /ru
        if ($first !== null && in_array(strtolower($first), ['az', 'en', 'ru', 'tr'], true)) {
            $first = $second;
            $second = $third;
            $third = func_num_args() > 4 ? func_get_arg(4) : null;
        }

        // Deal type path mapping e.g. /satilik, /kiralik
        if ($first !== null) {
            $firstLow = strtolower($first);
            if (in_array($firstLow, ['satilik', 'satisi', 'sale', 'for-sale'])) {
                $request->merge(['adType' => 'sale', 'deal_type' => 'sale']);
            } elseif (in_array($firstLow, ['kiralik', 'kiraye', 'rent', 'for-rent'])) {
                $request->merge(['adType' => 'rent', 'deal_type' => 'rent']);
            }
        }

        $perPage = 30;
        $cars = $this->carService->paginate($request, $perPage);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'cars' => view('pages.car.partials.cards', compact('cars'))->render(),
                'pagination' => view('pages.car.partials.pagination', compact('cars'))->render(),
                'total' => $cars->total(),
            ]);
        }

        $brands = $this->carService->getAllBrands();
        $popularBrands = $this->carService->getPopularBrands();
        $bodyTypes = $this->carService->getBodyTypes();
        $cities = City::where('is_active', true)->orderBy('name->tr')->get();

        $selectedBrandId = $request->input('brand_id');
        $models = $selectedBrandId
            ? CarModel::where('brand_id', $selectedBrandId)->where('is_active', true)->orderBy('name')->get()
            : collect();

        $fuelTypes = FuelType::options();
        $transmissions = Transmission::options();
        $steeringWheels = SteeringWheel::options();

        return view('pages.car.list', compact(
            'cars',
            'brands',
            'popularBrands',
            'bodyTypes',
            'cities',
            'models',
            'fuelTypes',
            'transmissions',
            'steeringWheels'
        ));
    }

    /**
     * AJAX endpoint to fetch models for a specific brand.
     */
    public function modelsByBrand(int $brandId): JsonResponse
    {
        $models = CarModel::where('brand_id', $brandId)
            ->where('is_active', true)
            ->orderBy('is_popular', 'desc')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug', 'is_popular']);

        return response()->json([
            'success' => true,
            'models' => $models,
        ]);
    }
}
