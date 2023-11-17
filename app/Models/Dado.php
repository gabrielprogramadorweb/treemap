<?php
// app/Models/Dado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dado extends Model
{
    protected $table = 'dados'; // Substitua 'nome_da_tabela' pelo nome real da tabela no banco de dados

    protected $fillable = ['cidade', 'populacao', 'populacao_ano_passado']; // Campos que podem ser preenchidos em massa

    // Se você não tiver colunas de data de criação/atualização no seu banco de dados, você pode desabilitar automaticamente:
    public $timestamps = false;
}

