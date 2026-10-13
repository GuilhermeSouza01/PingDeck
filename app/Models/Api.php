<?php

namespace App\Models;

use App\ApiMethod;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'name', 'url', 'method', 'expected_status', 'timeout', 'description', 'is_active'])]

class Api extends Model
{
    use HasFactory;

    protected $casts = [
        'method' => ApiMethod::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function latestCheck(): HasOne
    {
        return $this->checks()->one()->latestOfMany('checked_at');
    }
}
