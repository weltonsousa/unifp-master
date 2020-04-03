<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlunoPagamento extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'nome', 'email',
    ];

    protected $primaryKey = 'id_cliente';
    protected $table = 'clientes';
}
