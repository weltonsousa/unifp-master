<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assinaturas extends Model
{
    protected $primaryKey = 'assinatura_id';

    protected $fillable = [
        'assinatura_nome','assinatura_email','assinatura_telefone',
        'assinatura_status','assinatura_data','unidade_id'
    ];
    protected $table = 'assinaturas';
}
