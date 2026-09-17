<?php

namespace App\Modules\Car\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'country',
        'applicable_types',
        'is_popular',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'applicable_types' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeForVehicleType($query, ?string $type = null)
    {
        if (empty($type) || $type === 'all') {
            return $query;
        }

        return $query->where(function ($q) use ($type) {
            $q->whereJsonContains('applicable_types', $type)
              ->orWhereJsonContains('applicable_types', 'all')
              ->orWhereNull('applicable_types');
        });
    }

    protected static function booted(): void
    {
        static::creating(function (self $brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id')->orderBy('sort_order')->orderBy('name');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'brand_id');
    }
}
