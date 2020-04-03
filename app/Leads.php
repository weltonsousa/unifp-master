<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $fillable = [
        'id_lead', 'nome', 'email','telefone','curso','unidade_id','contato'
    ];
    protected $table = 'leads';
}
