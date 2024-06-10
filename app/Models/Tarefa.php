<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarefa extends Model
{
    use HasFactory;


    public function t5w2hs(): HasMany
    {
        return $this->hasMany(T5w2h::class);
    }
}
