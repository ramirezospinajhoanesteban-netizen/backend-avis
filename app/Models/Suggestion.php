<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $table = 'sugerencias';

    protected $fillable = [
        'tipo',
        'titulo',
        'descripcion',
        'email',
        'estado',
    ];
}
