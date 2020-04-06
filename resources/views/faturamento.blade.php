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
                    <form class="form-horizontal form-label-left input_mask" action="{{ route('report-faturamentos') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade:</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <select class="form-control" name="unidade">
                                    <option value="0">Todas</option>
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
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo Faturamento:</label>
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <select class="form-control" name="tipo">
                                    @if($tipo == 'RECEITA,DESPESA')
                                        <option value="RECEITA,DESPESA" selected>Todos</option>
                                    @else
                                        <option value="RECEITA,DESPESA">Todos</option>
                                    @endif
                                    @if($tipo == 'RECEITA,')
                                        <option value="RECEITA," selected>RECEITA</option>
                                    @else
                                        <option value="RECEITA,">RECEITA</option>
                                    @endif
                                    @if($tipo == 'DESPESA,')
                                        <option value="DESPESA," selected>DESPESA</option>
                                    @else
                                        <option value="DESPESA,">DESPESA</option>
                                    @endif
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
                            <div class="col-md-1 col-md-offset-11">
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
                                <th>Data Faturamento</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Matricula</th>
                                <th>Unidade</th>
                                <th>Forma Pagamento</th>
                                <th>Centro de custo</th>
                                <th>Data Vencimento</th>
                                <th>Tipo</th>
                                <th>Valor R$</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($faturamentos as $fat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($fat->DataFaturamento)->format('d/m/Y')}}</td>
                                    @if($fat->Matricula != null)
                                        <td>{{$fat->Nome}}</td>
                                        <td>{{$fat->Email}}</td>
                                        <td>{{$fat->Matricula}}</td>
                                    @elseif($fat->Matricula > 0)
                                        <td>Aluno código {{$fat->Matricula}} não encontrado.</td>
                                        <td>Aluno código {{$fat->Matricula}} não encontrado.</td>
                                        <td>Aluno código {{$fat->Matricula}} não encontrado.</td>
                                    @else
                                        <td>Não possui aluno.</td>
                                        <td>Não possui aluno.</td>
                                        <td>Não possui aluno.</td>
                                    @endif
                                    <td>{{$fat->unidade->Nome}}</td>
                                    <td>{{$fat->FormaPgto}}</td>
                                    <td>{{$fat->CentroCusto}}</td>
                                    <td>{{ \Carbon\Carbon::parse($fat->DataVencimento)->format('d/m/Y')}}</td>
                                    <td>{{$fat->Tipo}}</td>
                                    <td>{{$fat->Valor}}</td>
                                </tr>
                            @endforeach
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
