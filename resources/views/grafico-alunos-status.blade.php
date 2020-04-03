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
@endsection
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Alunos Ativos x Inativos (Mensal)</h3>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Filtros<small>faça uma busca para encontrar resultados.</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" action="{{ route('report-faturamentos') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade:</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <select class="form-control" name="unidade" id="unidade">
                                    <option value="0">Todas</option>
                                    @foreach ($unidades as $unidade)
                                        <option value="{{$unidade->IdUnidade}}">{{$unidade->Nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Período de faturamento:</label>
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" style="width: 200px" name="periodo" id="reservation" class="form-control" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-1 col-md-offset-11">
                                <a href="#" id="buscar-grafico-aluno-status"class="btn btn-success">Buscar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="chart" style="display:none;">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    Alunos Status x Mês
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

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
