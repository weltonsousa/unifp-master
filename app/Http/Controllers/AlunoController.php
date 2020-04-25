<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Leads;
use App\PagamentoOnline;
use App\Unidade;
use App\Assinaturas;
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
        $leads = Leads::all();
        return view('funil-vendas', [
            'unidades' => Unidade::All(),
            'unidade' => Unidade::where('IdUnidade')->first(),
            'leads' => $leads,
        ]);
    }

    public function leadsAssinaturas(){
        $assinaturas = $alunos = DB::connection('mysql2')
        ->table('assinaturas')
        ->where('assinatura_status','!=',2)
        ->get();
        return view('leads-assinaturas',compact('assinaturas'));
    }

    public function leadsExternos()
    {
        return view('leads-externos');
    }

    public function vendasOnline()
    {
        $unidade_id = Auth::user()->unidade_id;
        $tipo = Auth::user()->tipo_unidade;
        $nivel = Auth::user()->nivel;

        $periodo = request("periodo");
        $unidade = request('unidade');

        if ($unidade_id > 0) {

            if (request('unidade') !== null || request('periodo') !== null) {
                if (request('unidade') > 0 && request('periodo') === null) {
                    $alunos = PagamentoOnline::where("unidade_id", '=', $unidade_id)->get();

                } else if (request('unidade') === "0" && request('periodo') !== null) {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('unidade_id', '=', $unidade_id)
                        ->where('pag_status', '=', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();
                } else {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('unidade_id', '=', $unidade_id)
                        ->where('pag_status', '=', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();

                }
                return view(
                    'vendas-online',
                    [
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                        'alunos' => $alunos,
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
            }

        } else {

            if (request('unidade') !== null || request('periodo') !== null) {
                if (request('unidade') == 0 && request('periodo') === null) {
                    $alunos = PagamentoOnline::where("unidade_id", '=', $unidade_id)->get();

                } else if (request('unidade') === "0" && request('periodo') !== null) {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('pag_status', '=', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();
                } else {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('pag_status', '=', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();

                }
                return view(
                    'vendas-online',
                    [
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                        'alunos' => $alunos,
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
            }
        }
    }
    public function leads()
    {
        $unidades = Unidade::all();

        return view('leads', compact("unidades"));
    }

    public function boletos()
    {
        $unidade_id = Auth::user()->unidade_id;
        $tipo = Auth::user()->tipo_unidade;

        $periodo = request("periodo");
        $unidade = request('unidade');

        if ($unidade_id > 0) {

            if (request('unidade') !== null || request('periodo') !== null) {
                if (request('unidade') > 0 && request('periodo') === null) {

                    $alunos = PagamentoOnline::where('pag_status', '<>', 2)->where('unidade_id', '=', $unidade_id)->get();

                } else if (request('unidade') === "0" && request('periodo') !== null) {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('unidade_id', '=', $unidade_id)
                        ->where('pag_status', '<>', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();
                } else {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('unidade_id', '=', $unidade_id)
                        ->where('pag_status', '<>', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();

                }
                return view(
                    'boletos',
                    [
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                        'alunos' => $alunos,
                    ]);

            } else {
                return view(
                    'boletos',
                    [
                        'alunos' => [],
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                    ]
                );
            }

        } else {

            if (request('unidade') !== null || request('periodo') !== null) {
                if (request('unidade') == 0 && request('periodo') === null) {
                    $alunos = PagamentoOnline::where('pag_status', '<>', 2)->get();

                } else if (request('unidade') === "0" && request('periodo') !== null) {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('pag_status', '<>', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();
                } else {
                    $datas = explode("-", request('periodo'));
                    $datas[0] = explode("/", trim($datas[0], " "));
                    $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                    $datas[1] = explode("/", trim($datas[1], " "));
                    $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
                    $alunos = PagamentoOnline::whereRaw(DB::raw("DATE(pag_data) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('pag_status', '<>', 2)
                        ->orderby('cliente_id', 'ASC')
                        ->get();

                }
                return view(
                    'boletos',
                    [
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                        'alunos' => $alunos,
                    ]);

            } else {
                return view(
                    'boletos',
                    [
                        'alunos' => [],
                        'unidades' => Unidade::All(),
                        'unidade' => Unidade::where('IdUnidade', $unidade)->first(),
                        'periodo' => $periodo,
                    ]
                );
            }

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
