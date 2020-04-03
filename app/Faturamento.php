<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faturamento extends Model
{

    protected $fillable = [
        'IdFaturamento', 'DataFaturamento', 'Matricula','FormaPgto','ContaCaixa','CentroCusto','Valor','Tipo','IdUnidade','DataSincronizacao','DataVencimento'
    ];
    protected $table = 'faturamento';

    public function  aluno(){
        return $this->HasMany(Aluno::class, 'Matricula', 'Matricula');
    }

    public function  unidade(){
        return $this->HasOne(Unidade::class, 'IdUnidade', 'IdUnidade');
    }

}
