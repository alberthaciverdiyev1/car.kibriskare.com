<?php

namespace App\Modules\Car\Models;

use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compare extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'property_id',
        'car_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Car\Models\Car::class, 'car_id');
    }
}
