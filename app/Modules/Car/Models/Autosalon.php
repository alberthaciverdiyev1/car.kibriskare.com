<?php

namespace App\Modules\Car\Models;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Autosalon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'district_id',
        'name',
        'slug',
        'logo',
        'banner',
        'phone',
        'whatsapp',
        'email',
        'website',
        'address',
        'description',
        'working_hours',
        'lat',
        'lng',
        'is_verified',
        'is_active',
        'rating',
        'cars_count',
    ];

    protected $casts = [
        'description' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'cars_count' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $salon) {
            if (empty($salon->slug)) {
                $salon->slug = Str::slug($salon->name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'autosalon_id');
    }
}
