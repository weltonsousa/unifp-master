<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use App\Leads;
use App\Unidade;

class LeadController extends Controller
{
    public function index(){
        $unidade_id = Auth::user()->unidade_id;
        $unidades = Unidade::all()->where("sophia_id","=",$unidade_id);
        return view('leads-externos',compact("unidades"));
    }

    public function listaLeads(){
        $leads = Leads::all();
        return Datatables::of($leads)->addColumn('action', function ($lead) {
                $button = '<button type="button" name="edit_lead" id="'.$lead->id_lead.'" class="edit_lead btn btn-warning btn-md"> <i class="fa fa-pencil"></i> Editar </button>';
            return $button;
        })->make(true);
    }

    public function store(Request $request){
        $rules = array(
            'nome' =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()){
            return response()->json(['errors' => $error->errors()->all()]);
        }
    }
}