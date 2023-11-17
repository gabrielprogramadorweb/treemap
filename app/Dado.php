<?php

// app/Dado.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dado extends Model
{
    protected $table = 'dados';

    protected $fillable = [
        'cidade',
        'populacao',
        'populacao_ano_passado',
    ];
}
