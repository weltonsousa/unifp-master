<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Redirect;
use App\Turma;
use App\Message;
use App\Unidade;

class TurmaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $periodo = request("periodo");
        $unidade = request('unidade');
        if(request('unidade') !== null || request('periodo') !== null){


            if(request('unidade') > 0 && request('periodo') === null){
                $alunos = Turma::where("IdUnidade",request('unidade'))->with('unidade')->get();
            }
            else if(request('unidade') === "0" && request('periodo') !== null){
                $datas = explode("-",request('periodo'));
                $datas[0] = explode("/",trim($datas[0]," "));
                $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                $datas[1] = explode("/",trim($datas[1]," "));
                $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
                $turmas = Turma::whereRaw(DB::raw("DATE(DataInicio) between '".$datas[0]."' and '" . $datas[1] . "'"))
                            ->with('unidade')
                            ->orderby('IdTurma','ASC')
                            ->get();

            }else{
                $datas = explode("-",request('periodo'));
                $datas[0] = explode("/",trim($datas[0]," "));
                $datas[0] = $datas[0][2].'-'.$datas[0][1].'-'.$datas[0][0];
                $datas[1] = explode("/",trim($datas[1]," "));
                $datas[1] = $datas[1][2].'-'.$datas[1][1].'-'.$datas[1][0];
                $turmas = Turma::where("IdUnidade",request('unidade'))->
                            whereRaw(DB::raw("DATE(DataInicio) between '".$datas[0]."' and '" . $datas[1] . "'"))
                            ->with('unidade')
                            ->orderby('IdTurma','ASC')
                            ->get();

            }
            return view(
                'turmas',
                [
                    'turmas' => $turmas,
                    'unidades' =>Unidade::All(),
                    'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                    'periodo'=>$periodo
                ]
            );
            //return view('turmas',['turmas' => $turmas,'unidades' =>Unidade::All()]);
        }else{
            return view(
                'turmas',
                [
                    'turmas' => [],
                    'unidades' =>Unidade::All(),
                    'unidade'=>Unidade::where('IdUnidade',$unidade)->first(),
                    'periodo'=>$periodo
                ]
            );
            //return view('turmas',['turmas' => [],'unidades' =>Unidade::All()]);
        }
    }
}
