<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagamentoOnline extends Model
{
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'pag_id', 'pag_nome', 'pag_email', 'situacao', 'pag_telefone', 'pag_produto', 'unidade_id', 'contato',
    ];

    protected $primaryKey = 'pag_id';
    protected $table = 'pagamentos_online';

    public function unidade()
    {
        return $this->hasOne('App\Unidade', 'idUnidade', 'unidade_id');
    }
}
