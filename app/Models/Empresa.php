<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nome_da_empresa',
        'nicho',
        'resumo'
    ];

    public function usuarios() : BelongsTo {
        return $this->belongsTo(Usuario::class);
    }

    function usuariosParceiros() : BelongsToMany {
        return $this->belongsToMany(Usuario::class);
    }
}
