@extends('layouts.app')
@section('styles')
    <!-- bootstrap-daterangepicker -->
    <link href="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="{{URL::asset('assets/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{URL::asset('assets/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/css/animate.css')}}" rel="stylesheet">
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
                <h3>Leads Externos</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 hidden-panel animated"  id="lead-panel" style="position:fixed; top:0; right:0; z-index:100; float:right;">
    <div class="x_panel">
                <div class="x_title">
                    <h2> <i class="fa fa-user"></i> Adicionar Lead Externo</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content" style=" height:100vh;">
                    <form class="form-horizontal form-label-left input_mask" action="" method="post">
                        <input type="hidden" name="_token" value="RQlU607kd6JGbbnhI2yyLqW3WcFaIenv4AuYFmrN">                        
                        <div class="form-group">
                            <div>
                             <label for=""> Nome Aluno * </label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Email * </label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Telefone * </label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Curso * </label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Unidade * </label>
                                <select class="form-control" >
                                 <option value="">Minha Unidade</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for="">Status Lead * </label>
                                <select class="form-control" >
                                 <option value="">aqui</option>
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div>
                                <button type="button" id="lead" class="btn btn-success btn-lg"> <i class="fa fa-check"></i> Adicionar</button>
                                <button type="button" class="btn btn-danger btn-lg" id="fechar"> <i class="fa fa-close"></i> Cancelar</button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-12">
                        <div class="panel panel-success sucesso">
                            <div class="panel-heading"> Sucesso </div>
                            <div class="panel-body">Cadastro inserido com sucesso :)</div>
                        </div>
                        <div class="panel panel-danger falha">
                            <div class="panel-heading"> Opss ocorreu um erro ;/ </div>
                            <div class="panel-body">Ocorreu um erro no cadastro</div>
                        </div>
                        <div class="panel panel-warning alerta">
                            <div class="panel-heading"> Campos Obrigatorios ;/ </div>
                            <div class="panel-body">Cadastro Inserido Comsucesso</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12">
           <button type="button" id="add-lead" class="btn btn-primary btn-lg mt-2"> <i class="fa fa-plus"></i> Adicionar Lead Externo</button>
            
            <div class="x_panel">
            <br>
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
                    <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Curso</th>
                                <th>Situação Leads</th>
                                <th>Unidade</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
    <script src="{{URL::asset('assets/js/leads.js')}}"></script>
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
