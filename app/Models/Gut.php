<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gut extends Model
{
    use HasFactory;

    protected $fillable = [
        'gravidade',
        'urgencia',
        'tendencia'
    ];

    function t5w2hs(): HasMany
    {
        return $this->hasMany(T5w2h::class)->withDefault();
    }
}
