<?php

namespace App\Modules\Car\Models;

use App\Modules\Car\Enums\CarCondition;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Enums\Drivetrain;
use App\Modules\Car\Enums\FuelType;
use App\Modules\Car\Enums\SteeringWheel;
use App\Modules\Car\Enums\Transmission;
use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'autosalon_id',
        'brand_id',
        'model_id',
        'body_type_id',
        'city_id',
        'district_id',
        'title',
        'slug',
        'description',
        'deal_type',
        'price_gbp',
        'price_try',
        'price_eur',
        'price_usd',
        'main_currency',
        'year',
        'mileage',
        'mileage_unit',
        'engine_volume',
        'engine_power',
        'fuel_type',
        'transmission',
        'drivetrain',
        'steering_wheel',
        'color',
        'is_metallic',
        'doors',
        'seats',
        'condition',
        'is_customs_cleared',
        'is_credit_available',
        'is_barter_available',
        'vin',
        'seller_type',
        'contact_name',
        'contact_phone',
        'contact_whatsapp',
        'contact_email',
        'address',
        'lat',
        'lng',
        'status',
        'rejection_reason',
        'is_vip',
        'is_premium',
        'is_urgent',
        'view_count',
        'phone_view_count',
        'favorite_count',
        'published_at',
        'expired_at',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'deal_type' => CarDealType::class,
        'fuel_type' => FuelType::class,
        'transmission' => Transmission::class,
        'drivetrain' => Drivetrain::class,
        'steering_wheel' => SteeringWheel::class,
        'condition' => CarCondition::class,
        'status' => CarStatus::class,
        'price_gbp' => 'decimal:2',
        'price_try' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'engine_volume' => 'integer',
        'engine_power' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'is_metallic' => 'boolean',
        'is_customs_cleared' => 'boolean',
        'is_credit_available' => 'boolean',
        'is_barter_available' => 'boolean',
        'is_vip' => 'boolean',
        'is_premium' => 'boolean',
        'is_urgent' => 'boolean',
        'view_count' => 'integer',
        'phone_view_count' => 'integer',
        'favorite_count' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
        'published_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $car) {
            if (empty($car->slug)) {
                $rawTitle = $car->display_title ?: "{$car->year} car";
                $car->slug = Str::slug($rawTitle) . '-' . Str::lower(Str::random(6));
            }
            if (empty($car->published_at) && $car->status === CarStatus::Active) {
                $car->published_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function autosalon(): BelongsTo
    {
        return $this->belongsTo(Autosalon::class, 'autosalon_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function bodyType(): BelongsTo
    {
        return $this->belongsTo(CarBodyType::class, 'body_type_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class, 'car_id')->orderBy('sort_order');
    }

    public function mainImage()
    {
        return $this->hasOne(CarImage::class, 'car_id')->where('is_main', true);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(CarFeature::class, 'car_feature_car');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', CarStatus::Active->value);
    }

    public function scopeVip(Builder $query): Builder
    {
        return $query->where('is_vip', true);
    }

    public function scopeSale(Builder $query): Builder
    {
        return $query->where('deal_type', CarDealType::Sale->value);
    }

    public function scopeRent(Builder $query): Builder
    {
        return $query->whereIn('deal_type', [CarDealType::RentDaily->value, CarDealType::RentMonthly->value]);
    }

    // Accessors
    public function getDisplayTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if (is_array($this->title) && !empty($this->title[$locale])) {
            return $this->title[$locale];
        }
        if (is_array($this->title) && !empty($this->title['tr'])) {
            return $this->title['tr'];
        }

        $brand = $this->brand?->name ?? '';
        $model = $this->model?->name ?? '';
        $engine = $this->engine_volume ? number_format($this->engine_volume / 1000, 1) . 'L' : '';

        return trim("{$this->year} {$brand} {$model} {$engine}");
    }

    public function getDisplayDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if (is_array($this->description)) {
            return $this->description[$locale] ?? $this->description['tr'] ?? reset($this->description) ?? '';
        }
        return (string)$this->description;
    }

    public function getFormattedPriceAttribute(): string
    {
        $curr = session('currency', $this->main_currency ?: 'GBP');
        $amount = match ($curr) {
            'TRY' => $this->price_try ?? $this->price_gbp,
            'EUR' => $this->price_eur ?? $this->price_gbp,
            'USD' => $this->price_usd ?? $this->price_gbp,
            default => $this->price_gbp,
        };

        $symbol = match ($curr) {
            'TRY' => '₺',
            'EUR' => '€',
            'USD' => '$',
            default => '£',
        };

        return number_format((float)$amount, 0, '.', ',') . ' ' . $symbol;
    }

    public function getEngineVolumeLAttribute(): string
    {
        if (!$this->engine_volume) {
            return '';
        }
        return number_format($this->engine_volume / 1000, 1) . ' L';
    }

    public function getFormattedMileageAttribute(): string
    {
        return number_format($this->mileage, 0, '.', ',') . ' ' . $this->mileage_unit;
    }
}
