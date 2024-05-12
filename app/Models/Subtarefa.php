<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtarefa extends Model
{
    use HasFactory;

    function usuario() : BelongsTo {
        return $this->belongsTo(Usuario::class);
    }

    function t5w2h() : BelongsTo {
        return $this->belongsTo(T5w2h::class);
    }
}
