<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{

    protected $fillable = [
        'IdUnidade', 'Nome', 'Descricao'
    ];
    protected $table = 'unidade';

    public function  MatriculasPeriodo($dataIni,$dataFim){
        $x = $this->hasMany(Aluno::class, 'IdUnidade', 'IdUnidade')
            ->where('status','Estudando')
            ->whereRaw(DB::raw("DataMatricula between '" . $dataIni . "' and '" . $dataFim . "'"))
            ->toSql();
        echo $x;
        die();
        return null;
    }
    public function FaturamentoPeriodo(){

    }
}
