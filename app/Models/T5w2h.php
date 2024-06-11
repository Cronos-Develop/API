<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class T5w2h extends Model
{
    use HasFactory;

    protected $fillable = [
        "pergunta_id",
        "tarefa_id",
        "resposta",
        "empresa_id"
        //"tarefa"
    ];

    function pergunta(): BelongsTo
    {
        return $this->belongsTo(Pergunta::class)->withDefault();
    }

    function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class)->withDefault();
    }

    function subtarefas(): HasMany
    {
        return $this->hasMany(Subtarefa::class, '5w2h_id');
    }

    function gut(): BelongsTo
    {
        return $this->belongsTo(Gut::class);
    }

    function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }
}
