<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{

    protected $fillable = [
        'IdTurma', 'NomeTurma', 'DataInicio','DataTermino','IdUnidade','StatusTurma'
    ];
    protected $table = 'turma';

    public function  unidade(){
        return $this->HasOne(Unidade::class, 'IdUnidade', 'IdUnidade')->select();
    }
}
