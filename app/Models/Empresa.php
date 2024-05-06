<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'usuario_parceiro_id',
        'nome_da_empresa',
        'nicho',
        'resumo'
    ];

    public function usuarios() : BelongsTo {
        return $this->belongsTo(Usuario::class);
    }
}
