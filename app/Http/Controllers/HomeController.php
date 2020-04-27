<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\DashboardViewModel;
use App\Faturamento;
use App\SituacaoFinanceira;
use App\Turma;
use App\Unidade;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->getPeriodo();
    }

    private $dataIni = null;
    private $dataFim = null;

    private function getPeriodo()
    {
        $dia = 25; /*este é o dia de matriculas a ser considerao no mês exemplo mês Dez: 26/11/2018 até 26/12/2018*/
        $dataAtual = Carbon::now();
        $anoAtual = $dataAtual->year;
        if ($dataAtual->day > $dia) {
            $mesAtual = Carbon::parse($dia . '-' . $dataAtual->month . '-' . $anoAtual)->addMonth(1)->month;
        } else {
            $mesAtual = $dataAtual->month;
        }
        $this->dataIni = Carbon::parse($dia . '-' . $mesAtual . '-' . $anoAtual);
        $this->dataFim = Carbon::parse($dia . '-' . $mesAtual . '-' . $anoAtual)->addDays(-30);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tipo_unidade = Auth::user()->tipo_unidade;
        $unidade_id = Auth::user()->unidade_id;

        if ($tipo_unidade == 1) {
            $data = new DashboardViewModel();
            $periodo = request("periodo");
            $periodoFiltro = $periodo;

            if (request('periodo') === null) {
                $periodo = Carbon::now()->format("d/m/Y") . ' - ' . Carbon::now()->format("d/m/Y");
                $periodoFiltro = Carbon::now()->format("d/m/Y") . ' - ' . Carbon::now()->format("d/m/Y");

                $unidade = new Unidade();
                // $unidade = 0;

                /*alunos ativos*/
                $data->alunos = Aluno::where('status', 'Estudando')
                    ->whereRaw(DB::raw("DATE(DataMatricula) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->count();
                /*pega a última semana*/
                $totUltSem = Aluno::where('status', 'Estudando')->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                if ($data->alunos > 0) {
                    $data->alunosUltSem = ($totUltSem * 100) / $data->alunos;
                } else {
                    $data->alunosUltSem = 0;
                }

                /*desistentes */
                $data->alunosDesistentes = Aluno::where('status', 'Desistente')
                    ->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->count();
                /*pega a última semana*/
                $totUltSem = Aluno::where('status', 'Desistente')->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                if ($data->alunosDesistentes > 0) {
                    $data->alunosDesitentesUltSem = ($totUltSem * 100) / $data->alunosDesistentes;
                } else {
                    $data->alunosDesitentesUltSem = 0;
                }

                /*turmas*/
                $data->turmas = Turma::where('StatusTurma', 'Ativa')
                    ->whereRaw(DB::raw("DATE(DataInicio) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->get()->count();
                /*pega a última semana*/
                $totUltSem = Turma::where('StatusTurma', 'Ativa')->whereRaw(DB::raw("DATE(DataInicio) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                if ($data->turmas > 0) {
                    $data->turmasUltSem = ($totUltSem * 100) / $data->turmas;
                } else {
                    $data->turmasUltSem = 0;
                }

                /*inadinplentes*/
                $data->alunosInadimplentes = SituacaoFinanceira::where('Status', 'Inadimplente')
                    ->whereRaw(DB::raw("DATE(Vencimento) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->get()->count();
                /*pega a última semana*/
                $totUltSem = SituacaoFinanceira::where('Status', 'Inadimplente')->whereRaw(DB::raw("DATE(Vencimento) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                if ($data->alunosInadimplentes > 0) {
                    $data->inadinpletesUltSem = ($totUltSem * 100) / $data->alunosInadimplentes;
                } else {
                    $data->inadinpletesUltSem = 0;
                }

                /*top 5 analitico*/
                $data->faturamentoAnalitico = Faturamento::where('Tipo', 'RECEITA')
                    ->where('CentroCusto', 'Matrícula FP')
                    ->whereNotNull('matricula')
                    ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->with('unidade')
                    ->groupby('IdUnidade')
                    ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "') as visitas"))
                    ->limit(5)
                    ->orderby(DB::raw('count(*)'), 'DESC')
                    ->get();

                $data->faturamentoAnaliticoMenos = Faturamento::where('Tipo', 'RECEITA')
                    ->where('CentroCusto', 'Matrícula FP')
                    ->whereNotNull('matricula')
                    ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->with('unidade')
                    ->groupby('IdUnidade')
                    ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "') as visitas"))
                    ->limit(5)
                    ->orderby(DB::raw('count(*)'), 'ASC')
                    ->get();

                /*saldo*/
                $receita = Faturamento::where('tipo', 'RECEITA')
                    ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->sum('valor');
                $despesa = Faturamento::where('tipo', 'DESPESA')
                    ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . Carbon::now()->format("Y-m-d") . "' and '" . Carbon::now()->format("Y-m-d") . "'"))
                    ->sum('valor');
                $data->saldo = number_format(($receita + $despesa), 2, ',', '.');
            } else { /*COM FILTRO POR PERÍODO INFORMADO*/
                $datas = explode("-", request('periodo'));
                $datas[0] = explode("/", trim($datas[0], " "));
                $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
                $datas[1] = explode("/", trim($datas[1], " "));
                $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];

                /*alunos ativos*/
                if (request("unidade") === "0") {
                    $data->alunos = Aluno::where('status', 'Estudando')
                        ->whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->count();
                    /*pega a última semana*/
                    $totUltSem = Aluno::where('status', 'Estudando')
                        ->whereRaw(DB::raw("DATE(DataMatricula) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))
                        ->get()->count();
                    if ($data->alunos > 0) {
                        $data->alunosUltSem = ($totUltSem * 100) / $data->alunos;
                    } else {
                        $data->alunosUltSem = 0;
                    }
                } else {
                    $data->alunos = Aluno::where('status', 'Estudando')
                        ->whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where('IdUnidade', request("unidade"))
                        ->count();
                    /*pega a última semana*/
                    $totUltSem = Aluno::where('status', 'Estudando')
                        ->whereRaw(DB::raw("DATE(DataMatricula) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))
                        ->where('IdUnidade', request("unidade"))
                        ->get()->count();
                    if ($data->alunos > 0) {
                        $data->alunosUltSem = ($totUltSem * 100) / $data->alunos;
                    } else {
                        $data->alunosUltSem = 0;
                    }
                }

                /*desistentes */
                if (request("unidade") === "0") {
                    $data->alunosDesistentes = Aluno::where('status', 'Desistente')
                        ->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->count();
                    /*pega a última semana*/
                    $totUltSem = Aluno::where('status', 'Desistente')
                        ->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))
                        ->get()->count();
                    if ($data->alunosDesistentes > 0) {
                        $data->alunosDesitentesUltSem = ($totUltSem * 100) / $data->alunosDesistentes;
                    } else {
                        $data->alunosDesitentesUltSem = 0;
                    }
                } else {
                    $data->alunosDesistentes = Aluno::where('status', 'Desistente')
                        ->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->where("IdUnidade", request("unidade"))
                        ->count();
                    /*pega a última semana*/
                    $totUltSem = Aluno::where('status', 'Desistente')
                        ->whereRaw(DB::raw("DATE(DataSicronizacao) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))
                        ->where("IdUnidade", request("unidade"))
                        ->get()->count();
                    if ($data->alunosDesistentes > 0) {
                        $data->alunosDesitentesUltSem = ($totUltSem * 100) / $data->alunosDesistentes;
                    } else {
                        $data->alunosDesitentesUltSem = 0;
                    }
                }

                /*turmas*/
                if (request("unidade") === "0") {
                    $data->turmas = Turma::where('StatusTurma', 'Ativa')
                        ->whereRaw(DB::raw("DATE(DataInicio) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get()->count();
                    /*pega a última semana*/
                    $totUltSem = Turma::where('StatusTurma', 'Ativa')->whereRaw(DB::raw("DATE(DataInicio) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                    if ($data->turmas > 0) {
                        $data->turmasUltSem = ($totUltSem * 100) / $data->turmas;
                    } else {
                        $data->turmasUltSem = 0;
                    }
                } else {
                    $data->turmas = Turma::where('StatusTurma', 'Ativa')->where("IdUnidade", request("unidade"))
                        ->whereRaw(DB::raw("DATE(DataInicio) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get()->count();
                    /*pega a última semana*/
                    $totUltSem = Turma::where('StatusTurma', 'Ativa')->where("IdUnidade", request("unidade"))->whereRaw(DB::raw("DATE(DataInicio) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                    if ($data->turmas > 0) {
                        $data->turmasUltSem = ($totUltSem * 100) / $data->turmas;
                    } else {
                        $data->turmasUltSem = 0;
                    }
                }

                /*inadinplentes*/
                if (request("unidade") === "0") {
                    $data->alunosInadimplentes = SituacaoFinanceira::where('Status', 'Inadimplente')
                        ->whereRaw(DB::raw("DATE(Vencimento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get()->count();
                    /*pega a última semana*/
                    $totUltSem = SituacaoFinanceira::where('Status', 'Inadimplente')->whereRaw(DB::raw("DATE(Vencimento) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                    if ($data->alunosInadimplentes > 0) {
                        $data->inadinpletesUltSem = ($totUltSem * 100) / $data->alunosInadimplentes;
                    } else {
                        $data->inadinpletesUltSem = 0;
                    }
                } else {
                    $data->alunosInadimplentes = SituacaoFinanceira::where('Status', 'Inadimplente')
                        ->where("IdUnidade", request("unidade"))
                        ->whereRaw(DB::raw("DATE(Vencimento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->get()->count();
                    /*pega a última semana*/
                    $totUltSem = SituacaoFinanceira::where('Status', 'Inadimplente')->where("IdUnidade", request("unidade"))->whereRaw(DB::raw("DATE(Vencimento) between '" . Carbon::now()->startOfWeek() . "' and '" . Carbon::now()->endOfWeek() . "'"))->get()->count();
                    if ($data->alunosInadimplentes > 0) {
                        $data->inadinpletesUltSem = ($totUltSem * 100) / $data->alunosInadimplentes;
                    } else {
                        $data->inadinpletesUltSem = 0;
                    }
                }

                /*top 5 analitico*/
                if (request("unidade") === "0") {
                    $data->faturamentoAnalitico = Faturamento::where('Tipo', 'RECEITA')
                        ->where('CentroCusto', 'Matrícula FP')
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->limit(5)
                        ->orderby(DB::raw('count(*)'), 'DESC')
                        ->get();

                    $data->faturamentoAnaliticoMenos = Faturamento::where('Tipo', 'RECEITA')
                        ->where('CentroCusto', 'Matrícula FP')
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->limit(5)
                        ->orderby(DB::raw('count(*)'), 'ASC')
                        ->get();
                } else {
                    $data->faturamentoAnalitico = Faturamento::where('Tipo', 'RECEITA')
                        ->where('CentroCusto', 'Matrícula FP')
                        ->where('IdUnidade', request("unidade"))
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->limit(5)
                        ->orderby(DB::raw('count(*)'), 'DESC')
                        ->get();

                    $data->faturamentoAnaliticoMenos = Faturamento::where('Tipo', 'RECEITA')
                        ->where('CentroCusto', 'Matrícula FP')
                        ->where('IdUnidade', request("unidade"))
                        ->whereNotNull('matricula')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->with('unidade')
                        ->groupby('IdUnidade')
                        ->select('IdUnidade', DB::raw("count(*) as matriculas , sum(Valor) as total,round((sum(Valor)/count(*)),2) as valorMatricula,(select count(*) from visita where idUnidade = faturamento.IdUnidade and data between '" . $datas[0] . "' and '" . $datas[1] . "') as visitas"))
                        ->limit(5)
                        ->orderby(DB::raw('count(*)'), 'ASC')
                        ->get();
                }

                /*saldo*/
                if (request("unidade") === "0") {
                    $receita = Faturamento::where('tipo', 'RECEITA')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->sum('valor');
                    $despesa = Faturamento::where('tipo', 'DESPESA')
                        ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                        ->sum('valor');
                    $data->saldo = number_format(($receita + $despesa), 2, ',', '.');
                } else {
                    $data->saldo = (
                        (Faturamento::where('tipo', 'RECEITA')
                                ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                                ->where("IdUnidade", request("unidade"))
                                ->sum('valor'))
                         +
                        (Faturamento::where('tipo', 'DESPESA')
                                ->whereRaw(DB::raw("DATE(DataFaturamento) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                                ->where("IdUnidade", request("unidade"))
                                ->sum('valor'))
                    );
                    $data->saldo = number_format(($data->saldo), 2, ',', '.');
                }
            }

            return view(
                'home',
                [
                    'data' => $data,
                    'unidades' => Unidade::All(),
                    // 'unidades' => $unidade,
                    'unidade' => Unidade::where('IdUnidade', request("unidade"))->first(),
                    'periodo' => $periodo,
                    'dataIni' => $this->dataIni,
                    'dataFim' => $this->dataFim,
                    'periodoFiltro' => $periodoFiltro,
                ]
            );
        } else if ($tipo_unidade == 2) {
            return redirect('report-vendas');
        } else {
            return redirect('report-assinaturas');
        }
    }

    public function GetMatriculasMes()
    {
        $datas = explode("-", request('periodo'));
        $datas[0] = explode("/", trim($datas[0], " "));
        $datas[0] = $datas[0][2] . '-' . $datas[0][1] . '-' . $datas[0][0];
        $datas[1] = explode("/", trim($datas[1], " "));
        $datas[1] = $datas[1][2] . '-' . $datas[1][1] . '-' . $datas[1][0];
        if (request("unidade") === "0") {
            $data = Aluno::whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->groupBy(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'))
                ->select(DB::raw('count(*) as total'), DB::raw('concat(month(DataMatricula),"/",year(DataMatricula)) as mes'))
                ->orderby(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'DESC')
                ->get();
            return response()->json($data);
        } else {
            $data = Aluno::whereRaw(DB::raw("DATE(DataMatricula) between '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->where("IdUnidade", request("unidade"))
                ->groupBy(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'))
                ->select(DB::raw('count(*) as total'), DB::raw('concat(month(DataMatricula),"/",year(DataMatricula)) as mes'))
                ->orderby(DB::raw('concat(month(DataMatricula),"/",year(DataMatricula))'), 'DESC')
                ->get();
            return response()->json($data);
        }

    }
    public function GetMatriculasDia()
    {

        if (request("unidade") === "0") {
            //$data = Aluno::whereRaw(DB::raw("month(DataMatricula) = " .$mesAtual. " and year(DataMatricula) = " . $anoAtual ))
            $data = Aluno::whereRaw(DB::raw("DataMatricula between '" . $this->dataFim . "' and '" . $this->dataIni . "'"))
                ->groupBy(DB::raw('DataMatricula'))
                ->select(DB::raw('count(*) as total'), DB::raw('DATE_FORMAT(DataMatricula, "%d/%m/%Y") as dia'))
                ->orderby(DB::raw('DataMatricula'))
                ->get();
            return response()->json($data);
        } else {
            $data = Aluno::whereRaw(DB::raw("DataMatricula between '" . $this->dataFim . "' and '" . $this->dataIni . "'"))
                ->where("IdUnidade", request("unidade"))
                ->groupBy(DB::raw('DataMatricula'))
                ->select(DB::raw('count(*) as total'), DB::raw('DATE_FORMAT(DataMatricula, "%d/%m/%Y") as dia'))
                ->orderby(DB::raw('DataMatricula'))
                ->get();
        }
        return response()->json($data);
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
            $data = Faturamento::whereRaw(DB::raw("DATE(DataFaturamento) between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->groupBy(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->select(DB::raw('replace(FORMAT(sum(valor),2),",","") as total'), DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento)) as mes'), 'Tipo')
                ->orderby(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'DESC')
                ->orderby('Tipo')
                ->get();
        } else {
            $data = Faturamento::whereRaw(DB::raw("DATE(DataFaturamento) between  '" . $datas[0] . "' and '" . $datas[1] . "'"))
                ->where("IdUnidade", request("unidade"))
                ->groupBy(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'Tipo')
                ->select(DB::raw('FORMAT(replace(sum(valor),",",""),2) as total'), DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento)) as mes'), 'Tipo')
                ->orderby(DB::raw('concat(month(DataFaturamento),"/",year(DataFaturamento))'), 'DESC')
                ->orderby('Tipo')
                ->get();

        }

        return response()->json($data);
    }
}
