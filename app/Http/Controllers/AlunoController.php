<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $periodo = request("periodo");
        $unidade = request('unidade');
        if (request('unidade') !== null || request('periodo') !== null) {

            if (request('unidade') > 0 && request('periodo') === null) {
                $alunos = Aluno::where("IdUnidade", request('unidade'))->with('unidade')->get();
            } else if (request('unidade') === "0" && request('periodo') !== null) {
                $datas = explode("-", request('periodo'));
                $datas[0] = explode("/", trim($datas[0], " "));
                $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                $datas[1] = explode("/", trim($datas[1], " "));
                $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                $alunos = Aluno::whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                    ->with('unidade')
                    ->orderby('IdAluno', 'ASC')
                    ->get();

            } else {
                $datas = explode("-", request('periodo'));
                $datas[0] = explode("/", trim($datas[0], " "));
                $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                $datas[1] = explode("/", trim($datas[1], " "));
                $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                $alunos = Aluno::where("IdUnidade", request('unidade'))->
                    whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                    ->with('unidade')
                    ->orderby('IdAluno', 'ASC')
                    ->get();

            }
            return view(
                'alunos',
                [
                    'alunos' => $alunos,
                    'unidades' => Unidade::All(),
                    'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                    'periodo' => $periodo,
                ]
            );
            //return view('alunos',['alunos' => $alunos,'unidades' =>Unidade::All()]);
        } else {
            return view(
                'alunos',
                [
                    'alunos' => [],
                    'unidades' => Unidade::All(),
                    'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                    'periodo' => $periodo,
                ]
            );
            //return view('alunos',['alunos' => [],'unidades' =>Unidade::All()]);
        }
    }

    public function vendasOnline()
    {
        $unidade_id = Auth::user()->unidade_id;
        if ($unidade_id > 0) {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id')
                ->where('pagamentos_online.pag_status', '=', 2)
                ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                ->get();
        } else {

            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id')
                ->where('pagamentos_online.pag_status', '=', 2)
                ->get();

        }
        $unidades = Unidade::all();
        return view('vendas-online', compact('alunos', 'unidades'));
    }

    public function leads()
    {
        $unidade_id = Auth::user()->unidade_id;
        if ($unidade_id > 0) {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                ->get();
        } else {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->get();
        }
        $unidades = Unidade::all();
        return view('leads', compact('alunos', 'unidades'));
    }
}
