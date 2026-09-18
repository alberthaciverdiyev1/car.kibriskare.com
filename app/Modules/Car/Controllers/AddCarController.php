<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Enums\CarCondition;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Enums\Drivetrain;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\ImportOrigin;
use App\Modules\Car\Enums\PlateType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Car\Enums\VehicleType;
use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\CarBodyType;
use App\Modules\Car\Models\CarBrand;
use App\Modules\Car\Models\CarFeature;
use App\Modules\Car\Models\CarImage;
use App\Modules\Car\Models\CarModel;
use App\Modules\Car\Requests\StoreCarRequest;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
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

        $vehicleTypes = VehicleType::options();
        $plateTypes = PlateType::options();
        $importOrigins = ImportOrigin::options();
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
            'vehicleTypes',
            'plateTypes',
            'importOrigins',
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
                'vehicle_type' => $validated['vehicle_type'] ?? 'car',
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
                'steering_wheel' => $validated['steering_wheel'] ?? 'right',
                'color' => $validated['color'] ?? null,
                'is_metallic' => $request->boolean('is_metallic'),
                'doors' => ($validated['vehicle_type'] ?? '') === 'motorcycle' ? null : ($validated['doors'] ?? 4),
                'seats' => ($validated['vehicle_type'] ?? '') === 'motorcycle' ? ($validated['seats'] ?? 2) : ($validated['seats'] ?? 5),
                'condition' => $validated['condition'],
                'damage_parts' => $validated['damage_parts'] ?? null,
                'has_tramer' => $request->boolean('has_tramer'),
                'tramer_amount' => $validated['tramer_amount'] ?? null,
                'tramer_currency' => $validated['tramer_currency'] ?? 'GBP',
                'is_heavy_damaged' => $request->boolean('is_heavy_damaged'),
                'plate_type' => $validated['plate_type'] ?? 'kktc',
                'is_plate_masked' => $request->boolean('is_plate_masked'),
                'import_origin' => $validated['import_origin'] ?? null,
                'road_tax_valid_until' => $validated['road_tax_valid_until'] ?? null,
                'inspection_valid_until' => $validated['inspection_valid_until'] ?? null,
                'title_deed_ready' => $request->boolean('title_deed_ready', true),
                'video_url' => $validated['video_url'] ?? null,
                'is_customs_cleared' => $request->boolean('is_customs_cleared', true),
                'is_credit_available' => $request->boolean('is_credit_available'),
                'is_barter_available' => $request->boolean('is_barter_available'),
                'has_warranty' => $request->boolean('has_warranty'),
                'is_negotiable' => $request->boolean('is_negotiable'),
                'vin' => $validated['vin'] ?? null,
                'seller_type' => $validated['seller_type'],
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
                'contact_whatsapp' => $validated['contact_whatsapp'] ?? $validated['contact_phone'],
                'contact_email' => $validated['contact_email'] ?? null,
                'status' => CarStatus::Active, // Active or Pending based on site config
                'published_at' => now(),
            ]);

            // Handle inspection PDF upload
            if ($request->hasFile('inspection_pdf')) {
                $pdfPath = $request->file('inspection_pdf')->store('cars/inspections/' . date('Y/m'), 'public');
                $car->update(['inspection_pdf' => $pdfPath]);
            }

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
                'message' => __('Elanınız uğurla yerləşdirildi!'),
                'redirect' => url(app()->getLocale() . '/araba/' . $car->slug),
            ]);
        }

        return redirect()->to(url(app()->getLocale() . '/araba/' . $car->slug))
            ->with('success', __('Elanınız uğurla yerləşdirildi!'));
    }

    public function edit(Car $car): View
    {
        if (auth()->id() !== $car->user_id && !auth()->user()?->isAdmin()) {
            abort(403, __('Bu elanı redaktə etmək səlahiyyətiniz yoxdur.'));
        }

        $brands = CarBrand::where('is_active', true)->orderBy('name')->get();
        $models = CarModel::where('brand_id', $car->brand_id)->where('is_active', true)->orderBy('name')->get();
        $bodyTypes = CarBodyType::where('is_active', true)->orderBy('sort_order')->get();
        $features = CarFeature::where('is_active', true)->orderBy('sort_order')->get()->groupBy('category');
        $cities = City::where('is_active', true)->orderBy('name->tr')->get();
        $districts = $car->city_id ? District::where('city_id', $car->city_id)->where('is_active', true)->orderBy('name->tr')->get() : collect();

        $vehicleTypes = VehicleType::options();
        $plateTypes = PlateType::options();
        $importOrigins = ImportOrigin::options();
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

        $selectedFeatures = $car->features->pluck('id')->toArray();

        return view('pages.car.add', compact(
            'car',
            'brands',
            'models',
            'bodyTypes',
            'features',
            'selectedFeatures',
            'cities',
            'districts',
            'vehicleTypes',
            'plateTypes',
            'importOrigins',
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

    public function update(StoreCarRequest $request, Car $car): JsonResponse|RedirectResponse
    {
        if (auth()->id() !== $car->user_id && !auth()->user()?->isAdmin()) {
            abort(403, __('Bu elanı redaktə etmək səlahiyyətiniz yoxdur.'));
        }

        $validated = $request->validated();

        $mainCurrency = $validated['currency'] ?? ($car->main_currency ?: 'GBP');
        $prices = $this->currencyService->calculateAllCurrencies((float)$validated['price'], $mainCurrency);
        $newPriceGbp = $prices['GBP'] ?? (float)$validated['price'];

        $oldPriceGbp = $car->old_price_gbp;
        $oldPriceTry = $car->old_price_try;
        $oldPriceEur = $car->old_price_eur;
        $oldPriceUsd = $car->old_price_usd;
        $priceDroppedAt = $car->price_dropped_at;

        if ($newPriceGbp < (float)$car->price_gbp) {
            $oldPriceGbp = $car->price_gbp;
            $oldPriceTry = $car->price_try;
            $oldPriceEur = $car->price_eur;
            $oldPriceUsd = $car->price_usd;
            $priceDroppedAt = now();
        } elseif ($newPriceGbp > (float)$car->price_gbp) {
            $oldPriceGbp = null;
            $oldPriceTry = null;
            $oldPriceEur = null;
            $oldPriceUsd = null;
            $priceDroppedAt = null;
        }

        $car->update([
            'vehicle_type' => $validated['vehicle_type'] ?? $car->vehicle_type->value,
            'brand_id' => $validated['brand_id'],
            'model_id' => $validated['model_id'],
            'body_type_id' => $validated['body_type_id'] ?? null,
            'city_id' => $validated['city_id'],
            'district_id' => $validated['district_id'] ?? null,
            'deal_type' => $validated['deal_type'],
            'price_gbp' => $newPriceGbp,
            'price_try' => $prices['TRY'] ?? null,
            'price_eur' => $prices['EUR'] ?? null,
            'price_usd' => $prices['USD'] ?? null,
            'old_price_gbp' => $oldPriceGbp,
            'old_price_try' => $oldPriceTry,
            'old_price_eur' => $oldPriceEur,
            'old_price_usd' => $oldPriceUsd,
            'price_dropped_at' => $priceDroppedAt,
            'main_currency' => $mainCurrency,
            'year' => $validated['year'],
            'mileage' => $validated['mileage'],
            'engine_volume' => $validated['engine_volume'] ?? null,
            'engine_power' => $validated['engine_power'] ?? null,
            'fuel_type' => $validated['fuel_type'],
            'transmission' => $validated['transmission'],
            'drivetrain' => $validated['drivetrain'] ?? null,
            'steering_wheel' => $validated['steering_wheel'] ?? 'right',
            'color' => $validated['color'] ?? null,
            'is_metallic' => $request->boolean('is_metallic'),
            'doors' => ($validated['vehicle_type'] ?? '') === 'motorcycle' ? null : ($validated['doors'] ?? 4),
            'seats' => ($validated['vehicle_type'] ?? '') === 'motorcycle' ? ($validated['seats'] ?? 2) : ($validated['seats'] ?? 5),
            'condition' => $validated['condition'],
            'damage_parts' => $validated['damage_parts'] ?? $car->damage_parts,
            'has_tramer' => $request->boolean('has_tramer'),
            'tramer_amount' => $validated['tramer_amount'] ?? null,
            'tramer_currency' => $validated['tramer_currency'] ?? 'GBP',
            'is_heavy_damaged' => $request->boolean('is_heavy_damaged'),
            'plate_type' => $validated['plate_type'] ?? 'kktc',
            'is_plate_masked' => $request->boolean('is_plate_masked'),
            'import_origin' => $validated['import_origin'] ?? null,
            'road_tax_valid_until' => $validated['road_tax_valid_until'] ?? null,
            'inspection_valid_until' => $validated['inspection_valid_until'] ?? null,
            'title_deed_ready' => $request->boolean('title_deed_ready', true),
            'video_url' => $validated['video_url'] ?? null,
            'is_customs_cleared' => $request->boolean('is_customs_cleared', true),
            'is_credit_available' => $request->boolean('is_credit_available'),
            'is_barter_available' => $request->boolean('is_barter_available'),
            'has_warranty' => $request->boolean('has_warranty'),
            'is_negotiable' => $request->boolean('is_negotiable'),
            'vin' => $validated['vin'] ?? null,
            'seller_type' => $validated['seller_type'],
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_whatsapp' => $validated['contact_whatsapp'] ?? $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,
        ]);

        if (isset($validated['description'])) {
            $desc = is_array($car->description) ? $car->description : [];
            $desc[app()->getLocale()] = $validated['description'];
            $car->update(['description' => $desc]);
        }

        if ($request->hasFile('inspection_pdf')) {
            $pdfPath = $request->file('inspection_pdf')->store('cars/inspections/' . date('Y/m'), 'public');
            $car->update(['inspection_pdf' => $pdfPath]);
        }

        // Sync features
        if (isset($validated['features'])) {
            $car->features()->sync($validated['features']);
        }

        // Upload additional images
        if ($request->hasFile('images')) {
            $lastOrder = (int)$car->images()->max('sort_order') ?: 0;
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('cars/' . date('Y/m'), 'public');
                CarImage::create([
                    'car_id' => $car->id,
                    'image_path' => $path,
                    'thumbnail_url' => Storage::disk('public')->url($path),
                    'is_main' => $car->images()->count() === 0 && $index === 0,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Elanınız uğurla yeniləndi!'),
                'redirect' => url(app()->getLocale() . '/araba/' . $car->slug),
            ]);
        }

        return redirect()->to(url(app()->getLocale() . '/araba/' . $car->slug))
            ->with('success', __('Elanınız uğurla yeniləndi!'));
    }

    public function markSold(Car $car): JsonResponse
    {
        if (auth()->id() !== $car->user_id && !auth()->user()?->isAdmin()) {
            return response()->json(['success' => false, 'message' => __('Yetkiniz yoxdur.')], 403);
        }

        $newStatus = $car->status === CarStatus::Sold ? CarStatus::Active : CarStatus::Sold;
        $car->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus->value,
            'message' => $newStatus === CarStatus::Sold ? __('İlan "Satıldı" olaraq qeyd edildi.') : __('İlan yenidən aktiv edildi.'),
        ]);
    }

    public function deleteImage(CarImage $image): JsonResponse
    {
        $car = $image->car;
        if (auth()->id() !== $car->user_id && !auth()->user()?->isAdmin()) {
            return response()->json(['success' => false, 'message' => __('Yetkiniz yoxdur.')], 403);
        }

        if ($image->is_main) {
            $next = $car->images()->where('id', '!=', $image->id)->first();
            if ($next) {
                $next->update(['is_main' => true]);
            }
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true, 'message' => __('Şəkil silindi.')]);
    }
}

