<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    use HasFactory;

    public function empresas() : HasMany {
        return $this->hasMany(Empresa::class);
    }

    public function FunctionName() : Returntype {
        
    }
}
