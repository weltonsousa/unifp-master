<?php

namespace App\Http\Controllers;

use App\Leads;
use App\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function index()
    {

        $unidade_id = Auth::user()->unidade_id;
        $unidades = Unidade::all()->where("sophia_id", "=", $unidade_id);

        return view('leads-externos', compact("unidades"));
    }

    public function listaLeads()
    {
        $leads = Leads::all();

        dd($leads->unidade->Nome);

        return Datatables::of($leads)->addColumn('action', function ($lead) {
            $button = '<button type="button" name="edit_lead" data-id="' . $lead->id_lead . '" class="edit_lead btn btn-warning btn-md"> <i class="fa fa-pencil"></i> Editar </button>';
            return $button;

        })->addColumn('curso', function ($leads) {

            if ($leads->curso == 1) {
                $curso = "Escultura Tradicional";
            } elseif ($leads->curso == 25) {
                $curso = "Animaky";
            } else {
                $curso = "Indefinido";
            }
            return $curso;
        })->rawColumns(['action', 'curso'])->make(true);
    }

    public function edit_lead($id)
    {
        $data = Leads::find($id);
        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $rules = array(
            'nome' => 'required',
            'email' => 'required|unique:leads',
            'telefone' => 'required|unique:leads',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'curso' => $request->curso_id,
            'unidade_id' => $request->unidade,
            'contato' => $request->contato,
        );

        Leads::create($form_data);

        return response()->json(['success' => 'Lead Adicionado com Sucesso.']);
    }

    public function update(Request $request)
    {

        $rules = array(
            'situacao' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'situacao' => $request->situacao,
        );

        Leads::where("id_lead", "=", $request->id)->update($form_data);

        return response()->json(['success' => 'Lead Atualizado com Sucesso']);
    }
}
