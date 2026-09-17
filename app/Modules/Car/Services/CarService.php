<?php

namespace App\Modules\Car\Services;

use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarBodyType;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CarService
{
    public function paginate(Request $request, int $perPage = 30): LengthAwarePaginator
    {
        $query = Car::query()
            ->with(['brand', 'model', 'bodyType', 'city', 'images' => fn ($q) => $q->orderBy('sort_order'), 'autosalon'])
            ->where('status', CarStatus::Active->value);

        // Filter by Vehicle Type (car, suv, motorcycle, commercial, classic, damaged)
        if ($vehicleType = $request->input('vehicle_type')) {
            if ($vehicleType !== 'all') {
                $query->where('vehicle_type', $vehicleType);
            }
        }

        // Filter by Deal Type
        if ($adType = $request->input('adType') ?? $request->input('deal_type')) {
            if ($adType === 'sale') {
                $query->where('deal_type', 'sale');
            } elseif ($adType === 'rent' || $adType === 'rent_monthly') {
                $query->whereIn('deal_type', ['rent_monthly', 'rent_daily']);
            } elseif ($adType === 'rent_daily') {
                $query->where('deal_type', 'rent_daily');
            }
        }

        // Filter by Brand & Model
        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }
        if ($modelId = $request->input('model_id')) {
            $query->where('model_id', $modelId);
        }

        // Filter by Body Type
        if ($bodyTypeId = $request->input('body_type_id')) {
            $query->where('body_type_id', $bodyTypeId);
        }

        // Filter by City & District
        if ($cityId = $request->input('city_id')) {
            $query->where('city_id', $cityId);
        }
        if ($districtId = $request->input('district_id')) {
            $query->where('district_id', $districtId);
        }

        // Filter by Fuel Type
        if ($fuelType = $request->input('fuel_type')) {
            $query->where('fuel_type', $fuelType);
        }

        // Filter by Transmission
        if ($transmission = $request->input('transmission')) {
            $query->where('transmission', $transmission);
        }

        // Filter by Steering Wheel
        if ($steering = $request->input('steering_wheel')) {
            $query->where('steering_wheel', $steering);
        }

        // Filter by Drivetrain
        if ($drivetrain = $request->input('drivetrain')) {
            $query->where('drivetrain', $drivetrain);
        }

        // Filter by Plate Type (KKTC, Foreign, Z Plate, T Plate)
        if ($plateType = $request->input('plate_type')) {
            $query->where('plate_type', $plateType);
        }

        // Filter by Color
        if ($color = $request->input('color')) {
            $query->where('color', $color);
        }

        // Filter by Condition
        if ($condition = $request->input('condition')) {
            if ($condition !== 'all' && in_array($condition, ['new', 'used', 'damaged', 'for_parts', 'classic'])) {
                $query->where('condition', $condition);
            }
        }

        // Filter by Year Range
        if ($yearMin = $request->input('year_min')) {
            $query->where('year', '>=', (int)$yearMin);
        }
        if ($yearMax = $request->input('year_max')) {
            $query->where('year', '<=', (int)$yearMax);
        }

        // Filter by Mileage Range
        if ($mileageMin = $request->input('mileage_min')) {
            $query->where('mileage', '>=', (int)$mileageMin);
        }
        if ($mileageMax = $request->input('mileage_max')) {
            $query->where('mileage', '<=', (int)$mileageMax);
        }

        // Filter by Engine Volume (cc) Range
        if ($engVolMin = $request->input('engine_volume_min')) {
            $query->where('engine_volume', '>=', (int)$engVolMin);
        }
        if ($engVolMax = $request->input('engine_volume_max')) {
            $query->where('engine_volume', '<=', (int)$engVolMax);
        }

        // Filter by Engine Power (hp) Range
        if ($engPwrMin = $request->input('engine_power_min')) {
            $query->where('engine_power', '>=', (int)$engPwrMin);
        }
        if ($engPwrMax = $request->input('engine_power_max')) {
            $query->where('engine_power', '<=', (int)$engPwrMax);
        }

        // Filter by Price Range (GBP by default or current currency)
        $curr = session('currency', 'GBP');
        $priceCol = match ($curr) {
            'TRY' => 'price_try',
            'EUR' => 'price_eur',
            'USD' => 'price_usd',
            default => 'price_gbp',
        };

        if ($priceMin = $request->input('price_min')) {
            $query->where($priceCol, '>=', (float)$priceMin);
        }
        if ($priceMax = $request->input('price_max')) {
            $query->where($priceCol, '<=', (float)$priceMax);
        }

        // Commercial & Special Feature Flags
        if ($request->boolean('is_barter_available') || $request->input('barter')) {
            $query->where('is_barter_available', true);
        }
        if ($request->boolean('is_credit_available') || $request->input('credit')) {
            $query->where('is_credit_available', true);
        }
        if ($request->boolean('has_warranty') || $request->input('warranty')) {
            $query->where('has_warranty', true);
        }
        if ($request->boolean('is_negotiable') || $request->input('negotiable')) {
            $query->where('is_negotiable', true);
        }
        if ($request->boolean('is_customs_cleared') || $request->input('customs_cleared')) {
            $query->where('is_customs_cleared', true);
        }
        if ($request->input('seller_type')) {
            $query->where('seller_type', $request->input('seller_type'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        $orderClause = match ($sort) {
            'price_asc' => "is_premium DESC, is_urgent DESC, {$priceCol} ASC, id DESC",
            'price_desc' => "is_premium DESC, is_urgent DESC, {$priceCol} DESC, id DESC",
            'year_desc' => 'is_premium DESC, is_urgent DESC, year DESC, id DESC',
            'year_asc' => 'is_premium DESC, is_urgent DESC, year ASC, id DESC',
            'mileage_asc' => 'is_premium DESC, is_urgent DESC, mileage ASC, id DESC',
            'mileage_desc' => 'is_premium DESC, is_urgent DESC, mileage DESC, id DESC',
            default => 'is_premium DESC, is_urgent DESC, id DESC',
        };

        return $query
            ->orderByRaw($orderClause)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getPopularBrands(): array
    {
        return CarBrand::where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function getAllBrands()
    {
        return CarBrand::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'is_popular']);
    }

    public function getBodyTypes()
    {
        return CarBodyType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
