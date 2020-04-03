<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagamentoOnline extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'nome', 'email',
    ];

    protected $primaryKey = 'pag_id';
    protected $table = 'pagamentos_online';
}