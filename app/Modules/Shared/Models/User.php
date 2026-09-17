<?php

namespace App\Modules\Shared\Models;

use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\Compare;
use App\Modules\Car\Models\Favorite;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Car\Models\Autosalon|null $autosalon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Car\Models\Autosalon> $autosalons
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Car\Models\Car> $cars
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'pgsql';

    public const ADMIN_EMAIL = 'admin@araba.kibriskare.com';

    /**
     * Kütləvi doldurula bilən sütunlar
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Serializasiyada gizlədilən atributlar
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * İstifadəçinin admin olub-olmadığını yoxlayır.
     */
    public function isAdmin(): bool
    {
        return $this->email === self::ADMIN_EMAIL;
    }

    /**
     * Filament panellərinə giriş icazəsi.
     * - Admin panelinə yalnız sistem admini daxil ola bilər.
     * - Agency / Avtosalon / İstifadəçi panelinə bütün daxil olmuş istifadəçilər daxil ola bilər.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->isAdmin();
        }

        if ($panel->getId() === 'agency') {
            return true;
        }

        return false;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * İstifadəçinin avtosalonu (1-ə 1)
     */
    public function autosalon(): HasOne
    {
        return $this->hasOne(Autosalon::class, 'user_id');
    }

    /**
     * İstifadəçinin sahibi olduğu avtosalonlar
     */
    public function autosalons(): HasMany
    {
        return $this->hasMany(Autosalon::class, 'user_id');
    }

    /**
     * İstifadəçinin tenant avtosalonu
     */
    public function tenantAutosalon(): ?Autosalon
    {
        return $this->autosalon ?? $this->autosalons()->first();
    }

    /**
     * İstifadəçinin avtosalon sahibi olub-olmadığını yoxlayır.
     */
    public function isAutosalonOwner(): bool
    {
        return $this->autosalons()->exists();
    }

    /**
     * Köhnə metodlarla uyğunluq (Agency/Tenant fallback)
     */
    public function isTenantOwner(): bool
    {
        return $this->isAutosalonOwner();
    }

    public function tenantAgency(): ?Autosalon
    {
        return $this->tenantAutosalon();
    }

    /**
     * İstifadəçinin yerləşdirdiyi bütün avtomobil elanları
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'user_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteCars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'favorites');
    }

    public function compares(): HasMany
    {
        return $this->hasMany(Compare::class);
    }

    public function compareCars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'compares');
    }
}

