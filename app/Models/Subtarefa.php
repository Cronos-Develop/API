<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtarefa extends Model
{
    use HasFactory;

    function usuario() : BelongsTo {
        return $this->belongsTo(Usuario::class)->withDefault();
    }

    function tarefa() : BelongsTo {
        return $this->belongsTo(Tarefa::class)->withDefault();
    }
}
