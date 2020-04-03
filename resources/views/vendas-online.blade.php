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
                <h3>Matriculas</h3>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
       <div class="col-md-6 col-sm-10 col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Filtros<small>faça uma busca para encontrar resultados.</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" action="" method="post">
                        @csrf                        
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Unidade:</label>
                            <div>
                                <select class="form-control" name="unidade" style="width: 300px;">
                                    <option value="0">Todas</option>                                                                       <option value="118">Imugi Arapiraca</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Período de matricula:</label>
                            <div class="controls">
                                <div class="input-prepend input-group">
                                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                    <input type="text" style="width: 260px" name="periodo" id="reservation" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-offset-2">
                                <button id="send" type="submit" class="btn btn-success"> <i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-10 col-xs-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Resultados</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
                    <thead>
                            <tr>
                                <th>Nome Aluno</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Forma de Pagamento</th>
                                <th>Situação do Pagamento</th>
                                <th>Data do Pagamento</th>
                                <th>Curso</th>
                                <th>Unidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alunos as $aluno)
                                <tr>
                                <td> {{$aluno->nome}}</td>
                                <td> {{$aluno->email}}</td>
                                <td> {{$aluno->pag_telefone}}</td>
                                <td> 
                                @if($aluno->pag_tipo == 'cartao')
                                   <button class="btn btn-success"> Cartão</button>
                                @elseif($aluno->pag_tipo == 'boleto')
                                   <button class="btn btn-primary"> Boleto</button>
                                @else
                                    <button class="btn btn-danger"> Indefinido</button>
                                @endif
                               </td>
                                <td>@if($aluno->pag_status == 2)
                                    Pago
                                    @else
                                    Aberto
                                    @endif
                                </td>
                                <td> {{ date('d/m/Y',  strtotime($aluno->pag_data))}}</td>
                                <td> {{$aluno->pag_produto}}</td>
                                <td>@foreach ($unidades as $uni)
                                        @if($aluno->unidade_id == $uni->sophia_id)
                                        {{$uni->Nome}}
                                        @endif
                                @endforeach
                                </td>
                                </tr>
                            @endforeach
                        </tbody>

                     </table>
                </div>
            </div>
        </div>
    </div>

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
    <script src="{{URL::asset('assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{URL::asset('assets/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/vfs_fonts.js')}}"></script>

@endsection