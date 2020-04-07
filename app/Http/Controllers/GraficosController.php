<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Faturamento;
use App\Unidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class GraficosController extends Controller
{
    private $periodo = null;
    private $unidade = null;
    public function __construct()
    {
        $this->middleware('auth');
        $this->periodo = request("periodo");
        $this->unidade = request('unidade');
    }
    public function FrmAlunosAtivosInativos()
    {
        return view(
            'grafico-alunos-status',
            [
                'unidades' => Unidade::All(),
                'unidade' => Unidade::where('IdUnidade', $this->unidade)->first(),
                'periodo' => $this->periodo,
            ]
        );
    }
    public function GetAlunosAtivosInativos()
    {

        $datas = explode("-", request('periodo'));
        $datas[0] = explode("/", trim($datas[0], " "));
        $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
        $datas[1] = explode("/", trim($datas[1], " "));
        $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
        $dt = Carbon::now();
        if (request("unidade") === "0") {
            $data = Aluno::whereRaw(DB::raw("DataMatricula between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->groupBy(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'Status')
                ->select(DB::raw('count(*) as total'), DB::raw('concat(month(DataMatricula),"/",year(DataMatricula)) as mes'), 'Status')
                ->orderby(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'Status')
                ->get();
        } else {
            $data = Aluno::whereRaw(DB::raw("DataMatricula between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->where("IdUnidade", request("unidade"))
                ->groupBy(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'Status')
                ->select(DB::raw('count(*) as total'), DB::raw('concat(month(DataMatricula),"/",year(DataMatricula)) as mes'), 'Status')
                ->orderby(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'Status')
                ->get();
        }
        return response()->json($data);
    }

    public function FrmReceitasDespesas()
    {
        return view(
            'grafico-receitas-despesas',
            [
                'unidades' => Unidade::All(),
                'unidade' => Unidade::where('IdUnidade', $this->unidade)->first(),
                'periodo' => $this->periodo,
            ]
        );
    }

    public function GetReceitasDespesas()
    {
        $datas = explode("-", request('periodo'));
        $datas[0] = explode("/", trim($datas[0], " "));
        $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
        $datas[1] = explode("/", trim($datas[1], " "));
        $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
        $dt = Carbon::now();
        if (request("unidade") === "0") {
            $data = Faturamento::whereRaw(DB::raw("DataFaturamento between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->groupBy(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->select(DB::raw('FORMAT(sum(valor),2) as total'), DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento)) as mes'), 'Tipo')
                ->orderby(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->get();
        } else {
            $data = Faturamento::whereRaw(DB::raw("DataFaturamento between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->where("IdUnidade", request("unidade"))
                ->groupBy(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->select(DB::raw('FORMAT(sum(valor),2) as total'), DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento)) as mes'), 'Tipo')
                ->orderby(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->get();
        }
        return response()->json($data);
    }

}
