<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Enums\CarCondition;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Enums\Drivetrain;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarBodyType;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarFeature;
use App\Modules\Car\Models\CarImage;
use App\Modules\Car\Requests\StoreCarRequest;
use App\Modules\Location\Models\City;
use App\Modules\Shared\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AddCarController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {}

    public function create(): View
    {
        $brands = CarBrand::where('is_active', true)->orderBy('name')->get();
        $bodyTypes = CarBodyType::where('is_active', true)->orderBy('sort_order')->get();
        $features = CarFeature::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category');
        $cities = City::where('is_active', true)->orderBy('name->tr')->get();

        $fuelTypes = FuelType::options();
        $transmissions = Transmission::options();
        $steeringWheels = SteeringWheel::options();
        $conditions = CarCondition::options();
        $drivetrains = Drivetrain::options();
        $dealTypes = CarDealType::options();
        $currencies = $this->currencyService->getCurrencies();

        $userSalons = auth()->check()
            ? Autosalon::where('user_id', auth()->id())->where('is_active', true)->get()
            : collect();

        return view('pages.car.add', compact(
            'brands',
            'bodyTypes',
            'features',
            'cities',
            'fuelTypes',
            'transmissions',
            'steeringWheels',
            'conditions',
            'drivetrains',
            'dealTypes',
            'currencies',
            'userSalons'
        ));
    }

    public function store(StoreCarRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $car = DB::transaction(function () use ($request, $validated) {
            $mainCurrency = strtoupper($request->input('currency', 'GBP'));
            $enteredPrice = (float) $validated['price'];
            $baseGbp = $this->currencyService->getBaseGbp($enteredPrice, $mainCurrency);

            $prices = $this->currencyService->convertFromCurrency($enteredPrice, $mainCurrency);
            $prices[$mainCurrency] = $enteredPrice;
            $prices['GBP'] = $baseGbp;

            $brand = CarBrand::find($validated['brand_id']);
            $model = $brand?->models()->find($validated['model_id']);

            $titleText = trim("{$validated['year']} {$brand?->name} {$model?->name}");
            $slug = Str::slug($titleText) . '-' . Str::lower(Str::random(6));

            $car = Car::create([
                'user_id' => auth()->id(),
                'autosalon_id' => $request->input('autosalon_id'),
                'brand_id' => $validated['brand_id'],
                'model_id' => $validated['model_id'],
                'body_type_id' => $validated['body_type_id'] ?? null,
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'] ?? null,
                'title' => [
                    'tr' => $titleText,
                    'az' => $titleText,
                    'en' => $titleText,
                    'ru' => $titleText,
                ],
                'slug' => $slug,
                'description' => [
                    'tr' => $validated['description'] ?? '',
                    'az' => $validated['description'] ?? '',
                ],
                'deal_type' => $validated['deal_type'],
                'price_gbp' => $prices['GBP'] ?? $baseGbp,
                'price_try' => $prices['TRY'] ?? null,
                'price_eur' => $prices['EUR'] ?? null,
                'price_usd' => $prices['USD'] ?? null,
                'main_currency' => $mainCurrency,
                'year' => $validated['year'],
                'mileage' => $validated['mileage'],
                'mileage_unit' => 'km',
                'engine_volume' => $validated['engine_volume'] ?? null,
                'engine_power' => $validated['engine_power'] ?? null,
                'fuel_type' => $validated['fuel_type'],
                'transmission' => $validated['transmission'],
                'drivetrain' => $validated['drivetrain'] ?? null,
                'steering_wheel' => $validated['steering_wheel'],
                'color' => $validated['color'] ?? null,
                'is_metallic' => $request->boolean('is_metallic'),
                'doors' => $validated['doors'] ?? 4,
                'seats' => $validated['seats'] ?? 5,
                'condition' => $validated['condition'],
                'is_customs_cleared' => $request->boolean('is_customs_cleared', true),
                'is_credit_available' => $request->boolean('is_credit_available'),
                'is_barter_available' => $request->boolean('is_barter_available'),
                'vin' => $validated['vin'] ?? null,
                'seller_type' => $validated['seller_type'],
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
                'contact_whatsapp' => $validated['contact_whatsapp'] ?? $validated['contact_phone'],
                'contact_email' => $validated['contact_email'] ?? null,
                'status' => CarStatus::Active, // Active or Pending based on site config
                'published_at' => now(),
            ]);

            // Sync features
            if (!empty($validated['features'])) {
                $car->features()->sync($validated['features']);
            }

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('cars/' . date('Y/m'), 'public');
                    CarImage::create([
                        'car_id' => $car->id,
                        'image_path' => $path,
                        'thumbnail_url' => Storage::disk('public')->url($path),
                        'is_main' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $car;
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Elanınız uğurla yerləşdirildi!',
                'redirect' => url(app()->getLocale() . '/araba/' . $car->slug),
            ]);
        }

        return redirect()->to(url(app()->getLocale() . '/araba/' . $car->slug))
            ->with('success', 'Elanınız uğurla yerləşdirildi!');
    }
}
