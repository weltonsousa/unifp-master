<?php

namespace App\Http\Controllers;

use App\Leads;
use App\Unidade;
use App\PagamentoOnline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use DB;

class LeadController extends Controller
{
    public function index()
    {

        $unidade_id = Auth::user()->unidade_id;
        $unidades = Unidade::all()->where("sophia_id", "=", $unidade_id);

        return view('leads-externos', compact("unidades"));
    }

    public function listaAlunosLeads()
    {
        $unidade_id = Auth::user()->unidade_id;
        $tipo = Auth::user()->tipo_unidade;
        if ($unidade_id > 0) {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id','pagamentos_online.pag_id','pagamentos_online.und_destino')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '=', null)
                ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                ->get();
        } else if ($unidade_id == 0 && $tipo == 2) {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id','pagamentos_online.pag_id','pagamentos_online.und_destino')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '=', null)
                ->where('pagamentos_online.unidade_id', '=', 0)
                ->get();
        } else {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id','pagamentos_online.pag_id','pagamentos_online.und_destino')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '=', null)
                ->get();
        }
        
        return Datatables::of($alunos)->addColumn('action', function ($alunos) {
            
            if ($alunos->und_destino != "") {
                $button = '<button type="button"  class="btn btn-success btn-md"> <i class="fa fa-check"></i> Encaminhado </button>';
            } else {
                $button = '<button type="button" name="edit_lead_aluno" data-id="' . $alunos->pag_id . '" class="edit_lead_aluno btn btn-primary btn-md"> <i class="fa fa-external-link"></i> Encaminhar </button>';
            }
            return $button;

        })->rawColumns(['action'])->make(true);
    }

    public function listaLeads()
    {
        $leads = Leads::all();

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
        })->addColumn('situacao', function ($leads) {
            if ($leads->situacao == 1) {
                $situacao = "Matriculado";
            } elseif ($leads->situacao == 2) {
                $situacao = "Em Negociacao";
            } elseif ($leads->situacao == 3) {
                $situacao = "Desistiu";
            } else {
                $situacao = "Indefinido";
            }
            return $situacao;

        })->addColumn('conheceu', function ($leads) {

            if ($leads->contato == 1) {
                $conheceu = "Facebook";
            } elseif ($leads->curso == 2) {
                $conheceu = "Instagram";
            } elseif ($leads->curso == 3) {
                $conheceu = "Eventos";
            } else {
                $conheceu = "Outros";
            }
            return $conheceu;
        })->addColumn('unidade', function ($leads) {
            $unidade = $leads->unidade->Nome;
            return $unidade;
        })->rawColumns(['action', 'curso', 'unidade', 'conheceu', 'situacao'])->make(true);
    }

    public function edit_lead($id)
    {
        $data = Leads::find($id);
        return response()->json(['data' => $data]);
    }

    public function edit_lead_aluno($id)
    {
        $data = PagamentoOnline::find($id);
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

    public function update_aluno(Request $request)
    {

        $rules = array(
            'email' => 'required|unique:leads',
            'telefone' => 'required|unique:leads'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'und_destino' => $request->unidade,
        );

        $form_lead = array(
            'nome' => $request->nome,
            'email' => $request->email,
            'curso' => $request->curso,
            'unidade_id' => $request->unidade,
            'contato' => 0
        );

        Leads::create($form_lead);
        PagamentoOnline::where("pag_id", "=", $request->id)->update($form_data);

        return response()->json(['success' => 'Lead Atualizado com Sucesso']);
    }

    public function situacaoLead()
    {
        $mat = Leads::where('situacao', '=', '1')->get()->count();
        $des = Leads::where('situacao', '=', '2')->get()->count();
        $neg = Leads::where('situacao', '=', '3')->get()->count();

        $data = [
            'matricula' => $mat,
            'negociado' => $neg,
            'desistente' => $des,
        ];
        return response()->json($data);
    }
}
