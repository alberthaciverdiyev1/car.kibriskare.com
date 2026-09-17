<?php

namespace App\Modules\Car\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class CarFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $item) {
            if (empty($item->slug)) {
                $nameTr = is_array($item->name) ? ($item->name['tr'] ?? reset($item->name)) : $item->name;
                $item->slug = Str::slug($nameTr);
            }
        });
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        if (is_array($this->name)) {
            return $this->name[$locale] ?? $this->name['tr'] ?? reset($this->name) ?? '';
        }
        return (string)$this->name;
    }

    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'car_feature_car');
    }
}
