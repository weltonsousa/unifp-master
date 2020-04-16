<?php

namespace App\Http\Controllers;

use App\Leads;
use App\PagamentoOnline;
use App\Unidade;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

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
                ->select('clientes.nome',
                    'clientes.email',
                    'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status',
                    'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto',
                    'pagamentos_online.pag_valor',
                    'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id',
                    'pagamentos_online.pag_tipo',
                    'pagamentos_online.cliente_id',
                    'pagamentos_online.pag_id',
                    'pagamentos_online.und_destino')
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id', 'pagamentos_online.pag_id', 'pagamentos_online.und_destino')
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id', 'pagamentos_online.pag_id', 'pagamentos_online.und_destino')
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

    public function listaLeads(Request $request)
    {
        // $leads = PagamentoOnline::all();
        if (request()->ajax()) {
            if (!empty($request->from_date)) {

                $leads = DB::connection('mysql2')
                    ->table('unidade')
                    ->join('pagamentos_online', 'pagamentos_online.unidade_id', 'unidade.codUnidade')
                    ->select(
                        'pagamentos_online.pag_nome',
                        'pagamentos_online.situacao',
                        'pagamentos_online.pag_email',
                        'pagamentos_online.pag_cpf_cnpj',
                        'pagamentos_online.pag_status',
                        'pagamentos_online.pag_data',
                        'pagamentos_online.pag_data',
                        'pagamentos_online.pag_produto',
                        'pagamentos_online.pag_valor',
                        'pagamentos_online.pag_telefone',
                        'pagamentos_online.unidade_id',
                        'pagamentos_online.pag_tipo',
                        'pagamentos_online.pag_id',
                        'pagamentos_online.und_destino',
                        'pagamentos_online.contato',
                        'unidade.unidade')
                    ->whereBetween('pag_data', array($request->from_date, $request->to_date))
                    ->get();

                return Datatables::of($leads)->addColumn('action', function ($lead) {
                    if ($lead->unidade_id == 0) {
                        if ($lead->und_destino != "") {
                            $button = '<button type="button"  class="btn btn-success btn-md"> <i class="fa fa-check"></i> Encaminhado </button>';
                        } else {
                            $button = '<button type="button" name="encaminhar_aluno" data-id="' . $lead->pag_id . '" class="encaminhar_aluno btn btn-primary btn-md"> <i class="fa fa-external-link"></i> Encaminhar </button>';
                        }
                    }else{
                        $button ="";
                    }
                    $button .= '<button type="button" name="edit_situacao" data-id="' . $lead->pag_id . '" class="edit_situacao btn btn-warning btn-md"> <i class="fa fa-pencil"></i> Editar </button>';
                    return $button;

                })->addColumn('formapgto', function ($lead) {
                    if ($lead->pag_tipo == "boleto") {
                        $formaPagamento = "Boleto";
                    } else if ($lead->pag_tipo == "cartao") {
                        $formaPagamento = "Cartão";
                    } else {
                        $formaPagamento = "Indefinido";
                    }
                    return $formaPagamento;

                })->addColumn('tipo', function ($lead) {
                    if ($lead->pag_status == 0) {
                        $status = "Processando";
                    } else if ($lead->pag_status == 2) {
                        $status = "Pago";
                    } else {
                        $status = "Indefinido";
                    }
                    return $status;

                })->addColumn('situacaoaluno', function ($lead) {
                    if ($lead->situacao == 1) {
                        $staluno = "Matriculado";
                    } else if ($lead->situacao == 2) {
                        $staluno = "Em Negociação";
                    } else if ($lead->situacao == 3) {
                        $staluno = "Desistiu";
                    } else {
                        $staluno = "Indefinido";
                    }
                    return $staluno;

                })->addColumn('conheceu', function ($lead) {
                    if ($lead->contato == 0) {
                        $contato = "Site";
                    } else if ($lead->situacao == 1) {
                        $contato = "Facebook";
                    } else if ($lead->situacao == 2) {
                        $contato = "Instagram";
                    } else if ($lead->situacao == 3) {
                        $contato = "Eventos";
                    } else {
                        $contato = "Outros";
                    }
                    return $contato;

                })->addColumn('destino', function ($lead) {
                    if ($lead->und_destino == "") {
                        $destino = "Indefinido";
                    } else {
                        $dest = DB::connection('mysql2')->table('unidade')
                            ->select('unidade')->where('codUnidade', '=', $lead->und_destino)->pluck('unidade');
                        $destino = $dest[0];
                    }
                    return $destino;

                })->rawColumns(['action', 'formapgto', 'tipo', 'situacaoaluno', 'conheceu', 'destino'])->make(true);

            } else {

                $leads = DB::connection('mysql2')
                    ->table('unidade')
                    ->join('pagamentos_online', 'pagamentos_online.unidade_id', 'unidade.codUnidade')
                    ->select(
                        'pagamentos_online.pag_nome',
                        'pagamentos_online.situacao',
                        'pagamentos_online.pag_email',
                        'pagamentos_online.pag_cpf_cnpj',
                        'pagamentos_online.pag_status',
                        'pagamentos_online.pag_data',
                        'pagamentos_online.pag_data',
                        'pagamentos_online.pag_produto',
                        'pagamentos_online.pag_valor',
                        'pagamentos_online.pag_telefone',
                        'pagamentos_online.unidade_id',
                        'pagamentos_online.pag_tipo',
                        'pagamentos_online.pag_id',
                        'pagamentos_online.und_destino',
                        'pagamentos_online.contato',
                        'unidade.unidade')
                    ->get();

                return Datatables::of($leads)->addColumn('action', function ($lead) {
                    if ($lead->unidade_id == 0) {
                        if ($lead->und_destino != "") {
                            $button = '<button type="button"  class="btn btn-success btn-md"> <i class="fa fa-check"></i> Encaminhado </button>';
                        } else {
                            $button = '<button type="button" name="encaminhar_aluno" data-id="' . $lead->pag_id . '" class="encaminhar_aluno btn btn-primary btn-md"> <i class="fa fa-external-link"></i> Encaminhar </button>';
                        }
                    }else{
                        $button ="";
                    }
                    $button .= '<button type="button" name="edit_situacao" data-id="' . $lead->pag_id . '" class="edit_situacao btn btn-warning btn-md"> <i class="fa fa-pencil"></i> Editar </button>';
                    return $button;

                })->addColumn('formapgto', function ($lead) {
                    if ($lead->pag_tipo == "boleto") {
                        $formaPagamento = "Boleto";
                    } else if ($lead->pag_tipo == "cartao") {
                        $formaPagamento = "Cartão";
                    } else {
                        $formaPagamento = "Indefinido";
                    }
                    return $formaPagamento;

                })->addColumn('tipo', function ($lead) {
                    if ($lead->pag_status == 0) {
                        $status = "Processando";
                    } else if ($lead->pag_status == 2) {
                        $status = "Pago";
                    } else {
                        $status = "Indefinido";
                    }
                    return $status;

                })->addColumn('situacaoaluno', function ($lead) {
                    if ($lead->situacao == 1) {
                        $staluno = "Matriculado";
                    } else if ($lead->situacao == 2) {
                        $staluno = "Em Negociação";
                    } else if ($lead->situacao == 3) {
                        $staluno = "Desistiu";
                    } else {
                        $staluno = "Indefinido";
                    }
                    return $staluno;

                })->addColumn('conheceu', function ($lead) {
                    if ($lead->contato == 0) {
                        $contato = "Site";
                    } else if ($lead->situacao == 1) {
                        $contato = "Facebook";
                    } else if ($lead->situacao == 2) {
                        $contato = "Instagram";
                    } else if ($lead->situacao == 3) {
                        $contato = "Eventos";
                    } else {
                        $contato = "Outros";
                    }
                    return $contato;

                })->addColumn('destino', function ($lead) {
                    if ($lead->und_destino == "") {
                        $destino = "Indefinido";
                    } else {
                        $dest = DB::connection('mysql2')->table('unidade')
                            ->select('unidade')->where('codUnidade', '=', $lead->und_destino)->pluck('unidade');
                        $destino = $dest[0];
                    }
                    return $destino;

                })->rawColumns(['action', 'formapgto', 'tipo', 'situacaoaluno', 'conheceu', 'destino'])->make(true);

            }
        }
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

    public function matriculados()
    {
        $unidade_id = Auth::user()->unidade_id;
        $unidades = Unidade::all()->where("sophia_id", "=", $unidade_id);

        return view('matriculas', compact("unidades"));

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
            'pag_nome' => $request->nome,
            'pag_email' => $request->email,
            'pag_telefone' => $request->telefone,
            'pag_produto' => $request->curso,
            'unidade_id' => $request->unidade,
            'contato' => 0,
        );

        PagamentoOnline::create($form_data);

        return response()->json(['success' => 'Lead Adicionado com Sucesso.']);
    }

    public function update(Request $request)
    {

        $rules = array(
            'nome' => 'required',
            'email' => 'required',
            'telefone' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'und_destino' => $request->unidade,
        );

        PagamentoOnline::where("pag_id", "=", $request->id)->update($form_data);

        return response()->json(['success' => 'Lead Atualizado com Sucesso']);
    }

    public function status(Request $request)
    {

        $rules = array(
            'situacao' => 'required',
            'contato' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'situacao' => $request->situacao,
            'contato' => $request->contato,
        );

        PagamentoOnline::where("pag_id", "=", $request->id)->update($form_data);

        return response()->json(['success' => 'Lead Atualizado com Sucesso']);
    }

    public function update_aluno(Request $request)
    {

        $rules = array(
            'email' => 'required|unique:leads',
            'telefone' => 'required|unique:leads',
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
            'contato' => 0,
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
        $leads = Leads::all()->count();

        $totalMat = (1 + ($mat * 100)) / $leads;
        $totalDes = (1 + ($des * 100)) / $leads;
        $totalNeg = (1 + ($neg * 100)) / $leads;

        $data = [
            'matricula' => $totalMat,
            'negociado' => $totalNeg,
            'desistente' => $totalDes,
        ];
        return response()->json($data);
    }
}
