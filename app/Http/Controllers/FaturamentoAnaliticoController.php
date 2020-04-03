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

class FaturamentoAnaliticoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if(request('export') == '1'){
            $this->GetExcel();
        }else{

            $periodo = request("periodo");
            $unidade = request('unidade');
            if(request('unidade') !== null || request('periodo') !== null){

                if(request('unidade') === "0" && request('periodo') !== null){
                    $datas = explode("-",request('periodo'));
                    $datas[0] = explode("/",trim($datas[0]," "));
                    $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                    $datas[1] = explode("/",trim($datas[1]," "));
                    $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];

                    $faturamentos = Faturamento::where('Tipo','RECEITA')
                        ->where('CentroCusto','Matrícula FP')
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade',DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->orderby(DB::raw('count(*)'),'DESC')
                        ->get();
                }else{
                    $datas = explode("-",request('periodo'));
                    $datas[0] = explode("/",trim($datas[0]," "));
                    $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                    $datas[1] = explode("/",trim($datas[1]," "));
                    $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
                    $faturamentos = Faturamento::where('Tipo','RECEITA')
                        ->where('CentroCusto','Matrícula FP')
                        ->where('IdUnidade',$unidade)
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade',DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->orderby(DB::raw('count(*)'),'DESC')
                        ->get();

                }
                return view(
                    'faturamento-analitico',
                    [
                        'faturamentos' => $faturamentos,
                        'unidades' =>Unidade::All(),
                        'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                        'periodo'=>$periodo
                    ]
                );
            }else{
                return view(
                    'faturamento-analitico',
                    [
                        'faturamentos' => [],
                        'unidades' =>Unidade::All(),
                        'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                        'periodo'=>$periodo
                    ]
                );
            }
        }
    }

    public function GetExcel(){
        if(request('unidade') === "0" && request('periodo') !== null){
            $datas = explode("-",request('periodo'));
            $datas[0] = explode("/",trim($datas[0]," "));
            $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
            $datas[1] = explode("/",trim($datas[1]," "));
            $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];

            $faturamentos = Faturamento::where('Tipo','RECEITA')
                ->where('CentroCusto','Matrícula FP')
                ->whereNotNull('matricula')
                ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->with('unidade')
                ->groupby('IdUnidade')
                ->select('IdUnidade',DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                ->orderby(DB::raw('count(*)'),'DESC')
                ->get();
        }else{
            $datas = explode("-",request('periodo'));
            $datas[0] = explode("/",trim($datas[0]," "));
            $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
            $datas[1] = explode("/",trim($datas[1]," "));
            $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
            $faturamentos = Faturamento::where('Tipo','RECEITA')
                ->where('CentroCusto','Matrícula FP')
                ->where('IdUnidade',request('unidade'))
                ->whereNotNull('matricula')
                ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->with('unidade')
                ->groupby('IdUnidade')
                ->select('IdUnidade',DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                ->orderby(DB::raw('count(*)'),'DESC')
                ->get();

        }

        // file name that will be used in the download
        $fileName = "relatorio.csv";
        $content = "Unidade;Total Matriculas;Valor;Valor Total;Visitas";
        $content .= "\n";
        foreach ($faturamentos as $item) {
            $content .= $item->unidade->Nome .';';
            $content .= $item->matriculas .';';
            $content .= number_format($item->valorMatricula, 2, ',', '.') .';';
            $content .= number_format($item->total, 2, ',', '.') .';';
            $content .= $item->visitas;
            $content .= "\n";
        }

        echo $content;
        die();

        return response($content)
        ->withHeaders([
            'Content-Type' => 'application/vnd.ms-excel',
            'Cache-Control' => 'no-store, no-cache',
            'Content-Disposition' => 'attachment; filename='.$fileName ,
        ]);
    }
}
