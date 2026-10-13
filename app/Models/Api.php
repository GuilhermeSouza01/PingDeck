<?php

namespace App\Models;

use App\ApiMethod;
use Database\Factories\ApiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'name', 'url', 'method', 'expected_status', 'timeout', 'description', 'is_active'])]

class Api extends Model
{
    /** @use HasFactory<ApiFactory> */
    use HasFactory;

    protected $casts = [
        'method' => ApiMethod::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Check, $this>
     */
    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    /**
     * @return HasOne<Check, $this>
     */
    public function latestCheck(): HasOne
    {
        return $this->checks()->one()->latestOfMany('checked_at');
    }
}
