<?php

namespace App\Models;

use App\CheckStatus;
use Database\Factories\CheckFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['api_id', 'status', 'status_code', 'response_time', 'response_message', 'checked_at'])]

class Check extends Model
{
    /** @use HasFactory<CheckFactory> */
    use HasFactory;

    protected $casts = [
        'status' => CheckStatus::class,
    ];

    /**
     * @return BelongsTo<Api, $this>
     */
    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match (true) {
            $this->status_code >= 200 && $this->status_code < 300 => 'badge-success',
            $this->status_code >= 300 && $this->status_code < 400 => 'badge-info',
            $this->status_code >= 400 && $this->status_code < 500 => 'badge-warning',
            $this->status_code >= 500 => 'badge-error',
            default => 'badge-error',
        };
    }
}
