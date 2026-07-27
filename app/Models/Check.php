<?php

namespace App\Models;

use App\CheckStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[Fillable(['api_id', 'status', 'status_code', 'response_time', 'response_message', 'checked_at'])]

class Check extends Model
{
    use HasFactory;
    public function api(): BelongsTo
    {
        return $this->belongsTo(Api::class);
    }
}
