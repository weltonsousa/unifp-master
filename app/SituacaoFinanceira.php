<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SituacaoFinanceira extends Model
{

    protected $fillable = [
        'IdSituacao', 'Matricula', 'CodigoParcela','Parcela','Vencimento','Valor','Status','IdUnidade'
    ];
    protected $table = 'situacaofinanceira';

    public function  unidade(){
        return $this->HasOne(Unidade::class, 'IdUnidade', 'IdUnidade')->select();
    }
    public function  aluno(){
        return $this->HasOne(Aluno::class, 'Matricula', 'Matricula')->where('IdUnidade', $this->IdUnidade)->select();
    }
}
