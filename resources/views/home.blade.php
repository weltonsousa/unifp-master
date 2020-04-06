@extends('layouts.app')

        @section('styles')
        <!-- iCheck -->
        <link href="{{URL::asset('assets/iCheck/skins/flat/green.css')}}" rel="stylesheet">
        <!-- bootstrap-progressbar -->
        <link href="{{URL::asset('assets/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
        <!-- JQVMap -->
        <link href="{{URL::asset('assets/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
        <!-- bootstrap-daterangepicker -->
        <link href="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
        <style>
            .btnPesquisar{
                position: fixed;
                float: bottom;
                bottom: 15px;
                right: 15px;
                z-index: 100;
            }
            .btnCircular{
                border-radius: 50%;
            }
            .btnPrincipal{
                font-size: 20px;
                padding: 15px;
            }
            .filtros-utlizados{
                color:#000;
                font-weight:bold;
                font-size:12px;
            }
        </style>
        @endsection

        @section('content')
        <div class="btnPesquisar">
            <div class="col-3 btnPesquisarBtn">
                <button id="ocultar-filtros" class="btn btn-success btnCircular btnPrincipal" name="1"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <!-- page content -->
            <div class="row" id="filtros" style="display:none;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="col-md-6">
                                <h2>Filtros<small>faça uma busca para encontrar resultados.</small></h2>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <form class="form-horizontal form-label-left input_mask" action="{{ route('home') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade:</label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <select class="form-control" name="unidade" id="unidade">
                                            @if($unidade == null)
                                                <option value="0" selected>Todas</option>
                                            @else
                                                <option value="0">Todas</option>
                                            @endif
                                            @foreach ($unidades as $un)
                                                {{-- @if(count($unidade) > 0) --}}
                                                @if( is_array($unidade) ? count($unidade[$unidade->IdUnidade]) : 0 )
                                                    @if($un->IdUnidade == $unidade->IdUnidade)
                                                        <option value="{{$un->IdUnidade}}" selected>{{$un->Nome}}</option>
                                                    @else
                                                        <option value="{{$un->IdUnidade}}">{{$un->Nome}}</option>
                                                    @endif
                                                @else
                                                    <option value="{{$un->IdUnidade}}">{{$un->Nome}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Período:</label>
                                    <div class="controls">
                                        <div class="input-prepend input-group">
                                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                            <input type="text" style="width: 200px" name="periodo" id="reservation" class="form-control" value="{{$periodo}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-1 col-md-offset-11">
                                        <button type="submit" id="buscar-grafico-aluno-status"class="btn btn-success">Buscar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row tile_count filtros-utlizados">
                <div class="col-md-12 col-sm-4 col-xs-12 tile_stats_count">
                    filtros <b style='color:red;'>ATUAIS</b> utilizados:
                    <span class="count_top"><i class="fa fa-calendar"></i> Período: {{$periodoFiltro}}</span>
                    {{-- @if(count($unidade) > 0) --}}
                    @if( is_array($unidade) ? count($unidade[$unidade->IdUnidade]) : 0 )
                        <span class="count_top"><i class="fa fa-building-o"></i> Unidade: {{$unidade->Nome}}</span>
                    @else
                        <span class="count_top"><i class="fa fa-building-o"></i> Unidade: Todos</span>
                    @endif
                </div>
            </div>
            <!-- top tiles -->
            <div class="row tile_count">
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Total de Alunos ativos</span>
                    <div class="count">{{$data->alunos}}</div>
                    <span class="count_bottom"><i class="green">{{number_format($data->alunosUltSem,2)}}% </i> na última semana</span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-clock-o"></i> Total turmas</span>
                    <div class="count">{{$data->turmas}}</div>
                    <span class="count_bottom">
                        <i class="green">
                            @if($data->alunosDesitentesUltSem > 0)
                                <i class="fa fa-sort-asc"></i>
                            @endif
                            {{number_format($data->turmasUltSem,2)}}%
                        </i> na última semana
                    </span>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top red"><i class="fa fa-user"></i> Total Evasão</span>
                    <div class="count red">{{$data->alunosDesistentes}}</div>

                    <span class="count_bottom">
                        <i class="red">
                            @if($data->alunosDesitentesUltSem > 0)
                                <i class="fa fa-sort-asc"></i>
                            @endif
                            {{number_format($data->alunosDesitentesUltSem,2)}}%
                        </i> na última semana
                    </span>
                </div>
                <!--<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Total Cursos</span>
                    <div class="count">0</div>
                    <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>0% </i> na última semana</span>
                </div>-->
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Atrasados</span>
                    <div class="count red">{{$data->alunosInadimplentes}}</div>
                    <span class="count_bottom">
                        <i class="red">
                            @if($data->inadinpletesUltSem > 0)
                                <i class="fa fa-sort-asc"></i>
                            @endif
                            {{number_format($data->inadinpletesUltSem,2)}}%
                        </i> na última semana
                    </span>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i> Saldo Geral</span>
                    @if($data->saldo > 0)
                    <div class="count green"><span class="count_bottom">R$</span> {{$data->saldo}}</div>
                    @else
                    <div class="count red"><span class="count_bottom">R$</span> {{$data->saldo}}</div>
                    @endif

                </div>
            </div>
            <!-- /top tiles -->

            @if($data->faturamentoAnalitico != null)
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="x_panel">
                            <div class="x_title">
                                Top 5 Unidades com <b>MAIS</b> matrículas
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Unidade</th>
                                            <th>Total Matriculas</th>
                                            <th>Valor</th>
                                            <th>Valor Total</th>
                                            <th>Visitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalGeralValorMatricula = 0;
                                            $totalGeral = 0;
                                            $totalGeralMatriculas = 0;
                                            $totalGeralVisitas = 0;
                                        @endphp
                                        @foreach ($data->faturamentoAnalitico as $item)
                                            <tr>
                                                <td>{{$item->unidade->Nome}}</td>
                                                <td>{{$item->matriculas}}</td>
                                                <td>{{number_format($item->valorMatricula, 2, ',', '.')}}</td>
                                                <td>{{number_format($item->total, 2, ',', '.')}}</td>
                                                <td>{{$item->visitas}}</td>
                                            </tr>
                                            @php
                                                $totalGeralMatriculas += $item->matriculas;
                                                $totalGeralValorMatricula += $item->valorMatricula;
                                                $totalGeral += $item->total;
                                                $totalGeralVisitas +=$item->visitas;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td><b>Totais:</b></td>
                                            <td>{{$totalGeralMatriculas}}</td>
                                            <td>{{number_format($totalGeralValorMatricula, 2, ',', '.')}}</td>
                                            <td>{{number_format($totalGeral, 2, ',', '.')}}</td>
                                            <td>{{$totalGeralVisitas}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="x_panel">
                            <div class="x_title">
                                Top 5 Unidades com <b>MENOS</b> matrículas
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Unidade</th>
                                            <th>Total Matriculas</th>
                                            <th>Valor</th>
                                            <th>Valor Total</th>
                                            <th>Visitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalGeralValorMatricula = 0;
                                            $totalGeral = 0;
                                            $totalGeralMatriculas = 0;
                                            $totalGeralVisitas = 0;
                                        @endphp
                                        @foreach ($data->faturamentoAnaliticoMenos as $item)
                                            <tr>
                                                <td>{{$item->unidade->Nome}}</td>
                                                <td>{{$item->matriculas}}</td>
                                                <td>{{number_format($item->valorMatricula, 2, ',', '.')}}</td>
                                                <td>{{number_format($item->total, 2, ',', '.')}}</td>
                                                <td>{{$item->visitas}}</td>
                                            </tr>
                                            @php
                                                $totalGeralMatriculas += $item->matriculas;
                                                $totalGeralValorMatricula += $item->valorMatricula;
                                                $totalGeral += $item->total;
                                                $totalGeralVisitas +=$item->visitas;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td><b>Totais:</b></td>
                                            <td>{{$totalGeralMatriculas}}</td>
                                            <td>{{number_format($totalGeralValorMatricula, 2, ',', '.')}}</td>
                                            <td>{{number_format($totalGeral, 2, ',', '.')}}</td>
                                            <td>{{$totalGeralVisitas}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            Matrículas por mês
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            Receitas x Despesas
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <canvas id="mybarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="dashboard_graph">
                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Nº de matrículas <small>período corrente ({{$dataFim->format('d/m/Y')}} até {{$dataIni->format('d/m/Y')}}).</small></h3>
                            </div>
                        </div>
                        <canvas id="lineChartMatriculasDia"></canvas>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br />
        <!-- /page content -->
        @endsection


    @section('scripts')

    <!-- FastClick -->
    <script src="{{URL::asset('assets/fastclick/lib/fastclick.js')}}"></script>

    <!-- Chart.js -->
    <script src="{{URL::asset('assets/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{URL::asset('assets/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{URL::asset('assets/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{URL::asset('assets/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{URL::asset('assets/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{URL::asset('assets/Flot/jquery.flot.js')}}"></script>
    <script src="{{URL::asset('assets/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{URL::asset('assets/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{URL::asset('assets/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{URL::asset('assets/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{URL::asset('assets/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{URL::asset('assets/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{URL::asset('assets/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{URL::asset('assets/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{URL::asset('assets/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{URL::asset('assets/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{URL::asset('assets/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{URL::asset('assets/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    @endsection
  </body>
</html>


