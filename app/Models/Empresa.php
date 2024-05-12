<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_da_empresa',
        'nicho',
        'resumo'
    ];

    public function usuario() : BelongsTo {
        return $this->belongsTo(Usuario::class);
    }

    function usuariosParceiros() : BelongsToMany {
        return $this->belongsToMany(Usuario::class)->withTimestamps();
    }

    function t5w2hs() : HasMany {
        return $this->hasMany(T5w2h::class);
    }
}
