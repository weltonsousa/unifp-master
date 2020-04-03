<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{

    protected $fillable = [
        'IdAluno', 'Nome', 'Email','Matricula','Status','DataSicronizacao','IdUnidade','DataMatricula'
    ];
    protected $table = 'aluno';

    public function  unidade(){
        return $this->HasOne(Unidade::class, 'IdUnidade', 'IdUnidade')->select();
    }

    public function alunoUnidade()
    {
        return $this->HasOne(Unidade::class, 'IdUnidade', 'IdUnidade')->morphTo();
    }
}
