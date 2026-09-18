<?php

namespace App\Modules\Car\Models;

use App\Modules\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarSavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'url_query',
        'criteria',
        'alert_enabled',
    ];

    protected $casts = [
        'criteria' => 'array',
        'alert_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
