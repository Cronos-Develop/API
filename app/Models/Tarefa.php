<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        "descrição",
    ];
    public function t5w2hs(): HasMany
    {
        return $this->hasMany(T5w2h::class);
    }

    public function subtarefas(): HasMany
    {
        return $this->hasMany(Subtarefa::class);
    }
}
