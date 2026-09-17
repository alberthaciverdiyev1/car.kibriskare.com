<?php

namespace App\Modules\Car\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CarBodyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'applicable_types',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'applicable_types' => 'array',
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

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'body_type_id');
    }
}
