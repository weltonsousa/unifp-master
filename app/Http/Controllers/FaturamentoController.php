<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Redirect;
use App\Faturamento;
use App\Message;
use App\Unidade;

class FaturamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $periodo = request("periodo");
        $unidade = request('unidade');
        $tipo = request('tipo');
        if(request('unidade') !== null || request('periodo') !== null){

            $arrTipo = explode(",",request('tipo'));
            if(request('unidade') === "0" && request('periodo') !== null){
                $datas = explode("-",request('periodo'));
                $datas[0] = explode("/",trim($datas[0]," "));
                $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                $datas[1] = explode("/",trim($datas[1]," "));
                $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
                DB::connection()->enableQueryLog();
                $faturamentos = Faturamento::
                    whereRaw(DB::raw("DATE(DataFaturamento) between '".$datas[0]."' and '" . $datas[1] . "'"))
                    ->whereIn("Tipo",$arrTipo)
                    ->leftJoin('aluno', function ($join) {
                        $join->on('aluno.Matricula', '=', 'faturamento.Matricula')->on('aluno.IdUnidade', '=', 'faturamento.IdUnidade');
                    })
                    ->with('unidade')
                    ->get([
                        'IdFaturamento',
                        'DataFaturamento',
                        'faturamento.Matricula',
                        'FormaPgto',
                        'ContaCaixa',
                        'CentroCusto',
                        'Valor',
                        'Tipo',
                        'faturamento.IdUnidade',
                        'DataSincronizacao',
                        'DataVencimento',
                        'aluno.Nome',
                        'aluno.Email',
                    ]);
                    /*$queries = DB::getQueryLog();
                    $last_query = end($queries);
                    print_r($queries);
                    die();
                    echo $faturamentos;
                    die();*/
            }else{
                $datas = explode("-",request('periodo'));
                $datas[0] = explode("/",trim($datas[0]," "));
                $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                $datas[1] = explode("/",trim($datas[1]," "));
                $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
                $faturamentos = Faturamento::where("faturamento.IdUnidade",request('unidade'))
                    ->whereIn("Tipo",$arrTipo)
                    ->whereRaw(DB::raw("DATE(DataFaturamento) between '".$datas[0]."' and '" . $datas[1] . "'"))
                    ->leftJoin('aluno', function ($join) {
                        $join->on('aluno.Matricula', '=', 'faturamento.Matricula')->on('aluno.IdUnidade', '=', 'faturamento.IdUnidade');
                    })
                    ->with('unidade')
                    ->get([
                        'IdFaturamento',
                        'DataFaturamento',
                        'faturamento.Matricula',
                        'FormaPgto',
                        'ContaCaixa',
                        'CentroCusto',
                        'Valor',
                        'Tipo',
                        'faturamento.IdUnidade',
                        'DataSincronizacao',
                        'DataVencimento',
                        'aluno.Nome',
                        'aluno.Email',
                    ]);
            }
            //return  response()->json($faturamentos);
            return view(
                'faturamento',
                [
                    'faturamentos' => $faturamentos,
                    'unidades' =>Unidade::All(),
                    'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                    'periodo'=>$periodo,
                    'tipo'=> $tipo
                ]
            );
            //return view('faturamento',['faturamentos' => $faturamentos,'unidades' =>Unidade::All()]);
        }else{
            return view(
                'faturamento',
                [
                    'faturamentos' => [],
                    'unidades' =>Unidade::All(),
                    'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                    'periodo'=>$periodo,
                    'tipo'=> $tipo
                ]
            );
            //return view('faturamento',['faturamentos' => [],'unidades' =>Unidade::All()]);
        }
    }
}
