<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'cpf_cnpj',
        'senha',
        'email',
        'telefone',
        'endereco',
        'cep',
        'nascimento',
        'empresario',
        'nome_da_empresa'
    ];

    public function empresas() : HasMany {
        return $this->hasMany(Empresa::class);
    }
}
