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
                <h3>Leads</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 hidden-panel animated"  id="lead-aluno-panel" style="position:fixed; top:0; right:0; z-index:100; float:right;">
    <div class="x_panel">
    <button class="btn btn-default pull-right fechar"><i class="fa fa-close"></i></button>
                <div class="x_title">
                    <h2> <i class="fa fa-home"></i> Encaminhar Lead Unidade</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content" style=" height:100vh;">
                    <form class="form-horizontal form-label-left" id="inserir-lead-aluno" method="POST">
                    @csrf
                        <div class="form-group">
                            <div>
                                <label for=""> Nome  </label>
                                <input type="text" class="form-control" readonly id="nome" name="nome">
                                <input type="hidden" class="form-control" readonly id="id" name="id">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Email  </label>
                                <input type="text" class="form-control" readonly id="email" name="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Curso  </label>
                                <input type="text" class="form-control" readonly id="curso" name="curso">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Telefone  </label>
                                <input type="text" class="form-control" readonly id="telefone" name="telefone">
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <label for=""> Unidade * </label>
                                <select class="form-control" name="unidade">
                                 @foreach($unidades as $unidade)
                                 <option value="{{$unidade->sophia_id}}">{{$unidade->Nome}}</option>
                                 @endforeach;
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div width="100%">
                                <button type="submit" class="btn btn-success btn-lg" id="action_lead_aluno"> <i class="fa fa-check"></i> Adicionar</button>
                                <button type="button" class="btn btn-danger btn-lg fechar" > <i class="fa fa-close"></i> Cancelar</button>
                            </div>
                        </div>
                    </form>
                    <span id="form_result" width="100%"></span>
                </div>
            </div>
        </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                <table id="leads" class="table table-striped jambo_table bulk_action" width="100%">
                        <thead>
                            <tr>
                                <th>Nome Aluno</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Curso de Interesse</th>
                                <th>Tentativa Pagamento</th>
                                <th>Data Tentativa</th>
                                <th>Unidade</th>
                                <th>Unidade Destino</th>
                               <th>
                                 Encaminhar
                                </th> 
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
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
