@extends('layouts.app')
@section('styles')
    <!-- bootstrap-daterangepicker -->
    <link href="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="{{URL::asset('assets/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{URL::asset('assets/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{URL::asset('assets/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Faturamentos</h3>
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
                    <form class="form-horizontal form-label-left input_mask" action="{{ route('report-fatanalitico') }}" method="post">
                        <input type="hidden" name="export" id="export" value="0" />
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade:</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <select class="form-control" name="unidade"id="unidade">
                                    <option value="0">Todas</option>
                                    @if($unidade == null)
                                        <option value="0" selected>Todas</option>
                                    @else
                                        <option value="0">Todas</option>
                                    @endif
                                    @foreach ($unidades as $un)
                                        @if(count($unidade) > 0)
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
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Período de faturamento:</label>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <div class="controls">
                                    <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" style="width: 200px" name="periodo" id="reservation" class="form-control" value="{{$periodo}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br clear="all" />
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-3 col-sm-3 col-md-offset-9">
                                <button id="send-excel-fat-analitico" type="button" class="btn btn-success">Exportar Excel</button>
                                <button id="send" type="submit" class="btn btn-success">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($faturamentos != null)
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
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
                            @foreach ($faturamentos as $item)
                                <tr>
                                    <td>{{$item->unidade->Nome}}</td>
                                    <td>{{$item->matriculas}}</td>
                                    <td>{{number_format($item->valorMatricula, 2, ',', '.')}}</td>
                                    <td>{{number_format($item->total, 2, ',', '.')}}</td>
                                    <td>{{$item->visitas}}</td>
                                    @php
                                        $totalGeralMatriculas += $item->matriculas;
                                        $totalGeralValorMatricula += $item->valorMatricula;
                                        $totalGeral += $item->total;
                                        $totalGeralVisitas +=$item->visitas;
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Total Matriculas</th>
                                <th>Total Valor Matriculas</th>
                                <th>Total de Visitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$totalGeralMatriculas}}</td>
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

@endsection
@section('scripts')

    <!-- bootstrap-daterangepicker -->
    <script src="{{URL::asset('assets/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{URL::asset('assets/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{URL::asset('assets/fastclick/lib/fastclick.js')}}"></script>
    <!-- iCheck -->
    <script src="{{URL::asset('assets/iCheck/icheck.min.js')}}"></script>
    <!-- Datatables -->
    <script src="{{URL::asset('assets/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{URL::asset('assets/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/vfs_fonts.js')}}"></script>

@endsection
