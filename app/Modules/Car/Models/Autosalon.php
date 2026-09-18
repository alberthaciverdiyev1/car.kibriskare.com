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
        'consultants',
        'lat',
        'lng',
        'is_verified',
        'is_active',
        'rating',
        'cars_count',
    ];

    protected $casts = [
        'description' => 'array',
        'consultants' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'cars_count' => 'integer',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function isOpenNow(): bool
    {
        if (empty($this->working_hours)) {
            return false;
        }

        $now = now()->setTimezone('Asia/Nicosia');
        $text = mb_strtolower($this->working_hours);

        // Check Sunday
        if ($now->isSunday()) {
            if (str_contains($text, 'pazar kapalı') || str_contains($text, 'bazar bağlı') || str_contains($text, 'sunday closed')) {
                return false;
            }
        }

        if (str_contains($text, '24/7') || str_contains($text, '7/24')) {
            return true;
        }

        if (preg_match('/(\d{1,2}):(\d{2})\s*[-–]\s*(\d{1,2}):(\d{2})/', $text, $m)) {
            $startMinutes = (int)$m[1] * 60 + (int)$m[2];
            $endMinutes = (int)$m[3] * 60 + (int)$m[4];
            $currentMinutes = (int)$now->format('H') * 60 + (int)$now->format('i');

            return $currentMinutes >= $startMinutes && $currentMinutes <= $endMinutes;
        }

        // Default open during typical business hours 09:00 - 18:00 on weekdays
        return !$now->isSunday() && $now->hour >= 9 && $now->hour < 18;
    }

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

    public function getLogoUrlAttribute(): string
    {
        if (empty($this->logo)) {
            return 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=300&auto=format&fit=crop&q=80';
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo);
    }

    public function getBannerUrlAttribute(): string
    {
        if (empty($this->banner)) {
            return 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=1200&auto=format&fit=crop&q=80';
        }

        if (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://')) {
            return $this->banner;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->banner);
    }
}
