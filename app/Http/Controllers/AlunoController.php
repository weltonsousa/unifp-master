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
            //     //return view('alunos',['alunos' => $alunos,'unidades' =>Unidade::All()]);

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

    public function funil()
    {
        return view('funil-vendas');
    }

    public function leadsExternos()
    {
        return view('leads-externos');
    }

    public function vendasOnline()
    {
        $unidade_id = Auth::user()->unidade_id;
        $tipo = Auth::user()->tipo_unidade;

        $periodo = request("periodo");
        $unidade = request('unidade');

        if (request('unidade') !== null || request('periodo') !== null) {
            if (request('unidade') > 0 && request('periodo') === null) {
                // if ($unidade_id > 0) {

                $alunos = DB::connection('mysql2')
                    ->table('clientes')
                    ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                    ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                        'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                        'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                        'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                    ->where('pagamentos_online.pag_status', '=', 2)
                    ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                    ->get();

                // } else if (request('unidade') === "0" && request('periodo') !== null) {
            } else if (request('unidade') === "0" && request('periodo') !== null) {
                $datas = explode("-", request('periodo'));
                $datas[0] = explode("/", trim($datas[0], " "));
                $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                $datas[1] = explode("/", trim($datas[1], " "));
                $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];

                $alunos = DB::connection('mysql2')
                    ->table('clientes')
                    ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                    ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                        'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                        'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                        'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                    ->where('pagamentos_online.pag_status', '=', 2)
                    ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                    ->whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                    ->get();

            } else {
                $datas = explode("-", request('periodo'));
                $datas[0] = explode("/", trim($datas[0], " "));
                $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                $datas[1] = explode("/", trim($datas[1], " "));
                $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];

                $alunos = DB::connection('mysql2')
                    ->table('clientes')
                    ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                    ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                        'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                        'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                        'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                    ->where('pagamentos_online.pag_status', '=', 2)
                    ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                    ->whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                    ->get();
            }
            return view(
                'vendas-online',
                [
                    'alunos' => $alunos,
                    'unidades' => Unidade::All(),
                    'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                    'periodo' => $periodo,
                ]);

        } else {
            return view(
                'vendas-online',
                [
                    'alunos' => [],
                    'unidades' => Unidade::All(),
                    'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                    'periodo' => $periodo,
                ]
            );

            //    if ($unidade_id > 0) {
            //         $alunos = DB::connection('mysql2')
            //             ->table('clientes')
            //             ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
            //             ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
            //                 'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
            //                 'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
            //                 'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
            //             ->where('pagamentos_online.pag_status', '=', 2)
            //             ->where('pagamentos_online.unidade_id', '=', $unidade_id)
            //             ->get();

            //     //     return view(
            //     //         'vendas-online',
            //     //         [
            //     //             'alunos' => $alunos,
            //     //             'unidades' => Unidade::All(),
            //     //             'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
            //     //             'periodo' => $periodo,
            //     //         ]);

            if (request('unidade') == 0 || request('periodo') !== null) {
                // if (request('unidade') == 0 && request('periodo') === null) {
                if ($unidade_id == 0 && $tipo == 1) {

                    $alunos = DB::connection('mysql2')
                        ->table('clientes')
                        ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                        ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                            'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                            'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                            'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                        ->where('pagamentos_online.pag_status', '=', 2)
                    // ->where('pagamentos_online.unidade_id', '=', 0)
                        ->get();
                } else if (request('unidade') == "0" && request('periodo') !== null) {

                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];

                    $alunos = DB::connection('mysql2')
                        ->table('clientes')
                        ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                        ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                            'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                            'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                            'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                        ->where('pagamentos_online.pag_status', '=', 2)
                        ->where('pagamentos_online.unidade_id', '=', 0)
                        ->whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get();

                } else {

                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];

                    $alunos = DB::connection('mysql2')
                        ->table('clientes')
                        ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                        ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                            'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                            'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                            'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                        ->where('pagamentos_online.pag_status', '=', 2)
                        ->whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get();
                }
                return view(
                    'vendas-online',
                    [
                        'alunos' => $alunos,
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                    ]);

            }
        }
        //     return view(
        //         'vendas-online',
        //         [
        //             'alunos' => [],
        //             'unidades' => Unidade::All(),
        //             'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
        //             'periodo' => $periodo,
        //         ]
        //     );
        // }
        //     // $alunos = DB::connection('mysql2')
        //     //     ->table('clientes')
        //     //     ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
        //     //     ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
        //     //         'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
        //     //         'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
        //     //         'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
        //     //     ->where('pagamentos_online.pag_status', '=', 2)
        //     //     ->whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
        //     //     ->get();
        //     // // $unidades = Unidade::all();
        //     // // return view('vendas-online', compact('alunos', 'unidades'));

        //     // return view(
        //     //     'vendas-online',
        //     //     [
        //     //         'alunos' => $alunos,
        //     //         'unidades' => Unidade::All(),
        //     //         'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
        //     //         'periodo' => $periodo,
        //     //     ]);
    }
    public function leads()
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id')
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id')
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo', 'pagamentos_online.cliente_id')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '=', null)
                ->get();
        }
        $unidades = Unidade::all();
        return view('leads', compact('alunos', 'unidades'));
    }

    public function boletos()
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
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '!=', null)
                ->where('pagamentos_online.unidade_id', '=', $unidade_id)
                ->get();
        } else if ($unidade_id == 0 && $tipo == 2) {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                ->where('pagamentos_online.pag_status', '<>', 2)
                ->where('pagamentos_online.pag_codigo', '!=', null)
                ->where('pagamentos_online.unidade_id', '=', 0)
                ->get();
        } else {
            $alunos = DB::connection('mysql2')
                ->table('clientes')
                ->join('pagamentos_online', 'pagamentos_online.cliente_id', 'clientes.id_cliente')
                ->select('clientes.nome', 'clientes.email', 'pagamentos_online.pag_cpf_cnpj',
                    'pagamentos_online.pag_status', 'pagamentos_online.pag_data',
                    'pagamentos_online.pag_produto', 'pagamentos_online.pag_valor', 'pagamentos_online.pag_telefone',
                    'pagamentos_online.unidade_id', 'pagamentos_online.pag_tipo')
                ->where('pagamentos_online.pag_status', '=', 0)
                ->where('pagamentos_online.pag_codigo', '!=', null)
                ->get();
        }

        $unidades = Unidade::all();

        return view('boletos', compact('alunos', 'unidades'));
    }

    public function remover()
    {
        // $message = new Message();
        // $message->message = "";
        // $message->type = "";
        // try {
        //     DB::beginTransaction();

        //     User::whereId(request("cliente_id"))->delete();

        //     DB::commit();
        //     $message->message = 'lead excluído com sucesso';
        //     $message->type = "success";
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $message->message = $e->getMessage();
        //     $message->type = "error";
        // }
        // return $this->index()->with(['message' => $message]);
    }
}
