<?php

namespace App\Models;

use App\Enums\LinkStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => LinkStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    public function shortUrl(): string
    {
        return url($this->short_code);
    }
}
