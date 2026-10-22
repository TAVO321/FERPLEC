<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tip_id', 'name', 'email', 'content', 'is_approved', 'ip',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function tip(): BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
