<?php

namespace App\Modules\Inquiry\Models;

use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $car_id
 * @property int|null $autosalon_id
 * @property int|null $property_id
 * @property int|null $agency_id
 * @property int|null $agent_id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $message
 * @property string $type
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\Car\Models\Car|null $car
 * @property-read \App\Modules\Car\Models\Autosalon|null $autosalon
 * @property-read \App\Modules\Shared\Models\User|null $user
 */
class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kütləvi doldurula bilən sütunlar (Mass Assignable)
     */
    protected $fillable = [
        'car_id',       // Müraciət edilən avtomobilin ID-si
        'autosalon_id', // Müraciət ünvanlanan avtosalonun ID-si
        'property_id',
        'agency_id',
        'agent_id',
        'user_id',      // Müraciət edən istifadəçinin ID-si
        'name',         // Müştərinin adı və soyadı
        'phone',        // Müştərinin əlaqə nömrəsi
        'email',        // Müştərinin e-poçt ünvanı
        'message',      // Müştərinin yazdığı mesaj / istək
        'type',         // Müraciət növü (Ümumi sorğu, Test-drive, Qiymət təklifi)
        'status',       // Müraciətin icra vəziyyəti (Yeni, Əlaqə saxlanıldı, Bağlandı)
        'notes',        // Daxili qeydlər
    ];

    /**
     * Müraciət edilən avtomobil
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Müraciətin aid olduğu avtosalon
     */
    public function autosalon(): BelongsTo
    {
        return $this->belongsTo(Autosalon::class);
    }

    /**
     * Müraciət edən sistem istifadəçisi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

