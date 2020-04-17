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
    <link href="{{URL::asset('assets/datatables.net-responsive-bs/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('content')
    <!--filter tables -->
          <script src="{{URL::asset('assets/filter/jquery.min.js')}}"></script>
          <script src="{{URL::asset('assets/filter/jquery.dataTables.min.js')}}" > </script>
          <script src="{{URL::asset('assets/filter/dataTables.bootstrap.min.js')}}"> </script>
          <script src="{{URL::asset('assets/filter/bootstrap-datepicker.js')}}"> </script>
          <link href="{{URL::asset('assets/filter/dataTables.bootstrap.min.css')}}" rel="stylesheet">
          <link href="{{URL::asset('assets/filter/bootstrap-datepicker.css')}}" rel="stylesheet">

    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Matriculas</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 hidden-panel animated"  id="matriculas-panel" style="position:absolute; padding:0!important; height: 100vh!important;  top:0; right:0; z-index:100;">
    <div class="x_panel">
    <button class="btn btn-default pull-right fechar"><i class="fa fa-close"></i></button>
                <div class="x_title">
                    <h2 id="titulo"> <i class="fa fa-user"></i> Adicionar Lead Externo</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content" style=" height:100vh;">
                    <form class="form-horizontal form-label-left" id="inserir-aluno" method="POST">
                    @csrf
                    <input type="hidden"  id="id" name="id">
                        <div class="form-group editar">
                            <div>
                             <label for=""> Nome Aluno * </label>
                                <input type="text" class="form-control" id="nome" name="nome">
                            </div>
                        </div>
                        <div class="form-group editar">
                            <div>
                                <label for=""> Email * </label>
                                <input type="email" id="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="form-group editar">
                            <div>
                                <label for=""> Telefone * </label>
                                <input type="text" id="telefone" class="form-control telefone" name="telefone">
                            </div>
                        </div>
                        <div class="form-group editar info">
                            <div>
                                <label for=""> Curso * </label>
                                <select class="form-control" name="curso">
                                 <option value="Esculpture Tradicional">Esculpture Tradicional</option>
                                 <option value="Animaky">Animaky</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group editar unidade">
                            <div>
                                <label for=""> Unidade * </label>
                                <select class="form-control" name="unidade">
                                 @foreach($unidades as $unidade)
                                 <option value="{{$unidade->sophia_id}}">{{$unidade->Nome}}</option>
                                 @endforeach;
                                </select>
                            </div>
                        </div>
                        <div class="form-group editar info conheceu">
                            <div>
                                <label for="">como conheceu? </label>
                                <select class="form-control" name="contato">
                                 <option value="1">Facebook</option>
                                 <option value="2">Instagram</option>
                                 <option value="3">Eventos</option>
                                 <option value="4">Outros</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group status-lead info">
                            <div>
                                <label for="">Status Lead </label>
                                <select class="form-control" name="situacao">
                                 <option value="1">Matriculado</option>
                                 <option value="2">Em Negociacao</option>
                                 <option value="3">Desistiu</option>
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div width="100%">
                                <button type="submit" class="btn btn-success btn-lg" id="action_aluno"> <i class="fa fa-check"></i> Adicionar</button>
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
       <div class="col-md-6 col-sm-10 col-xs-6">
            <div class="x_panel" style="height:265px!important;">
                <div class="x_title">
                    <h2>Filtros<small>faça uma busca para encontrar resultados.</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                <div class="row">
                <!-- <div class="col-md-4" >
          <select name = "unidade" class="form-control">
             <option>Selecione Unidade</option>
          </select>
        </div> -->
        <div class="input-daterange">
      <div class="col-md-6" >
          <input type="text" name = "from_date" id = "from_date" class="form-control" placeholder = "Data inicio" readonly/>
        </div>
      <div class="col-md-6" >
           <input type="text" name = "to_date" id = "to_date" class="form-control" placeholder = "Data fim" readonly/>
         </div>
      <div class="col-md-4">
      <br>
              <button type="button" name = "filter" id = "filter" class="btn btn-primary" > <i class="fa fa-filter"></i> Filtrar </button>
              <button type = "button" name = "refresh" id = "refresh" class="btn btn-default" > Limpar </button>
        </div>
    </div>
          </div>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="x_panel">
<div class="x_title">
    <h2>Resultado</h2>

<div class="clearfix"></div>
    </div>
        <div class="x_content">
            <table class="" style="width:100%">
                <tbody>
                    <tr>
                        <th style="width:37%;">
                            <p> Total </p>
                            </th>
                        <th>
                            <div class="col-lg-7 col-md-7 col-sm-7 ">
                                <p class="">Em  Analise</p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 ">
                                <p class="">Quantidade</p>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <canvas id="canvasDoughnut" style="140px; height: 140px;"></canvas>
                        </td>
                            <td>
                                <table class="tile_info">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p><i class="fa fa-square green"></i>Matriculado </p>
                                            </td>
                                        <td>{{ number_format($resultados[0],2)}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><i class="fa fa-square"></i>Desistiu </p>
                                            </td>
                                            <td>{{ number_format($resultados[1], 2)}} %</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><i class="fa fa-square blue"></i>Em Negociação </p>
                                            </td>
                                            <td>{{ number_format($resultados[2],2)}} %</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p><i class="fa fa-square red"></i>Outros </p>
                                            </td>
                                            <td>0.00 %</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:10px;">
            <div class="x_panel">
            <button type="button" id="add-aluno" class="btn btn-primary btn-lg mt-2"> <i class="fa fa-plus"></i> Adicionar Lead Externo</button>
            <br/>


                <div class="x_content">
                    <table id="matriculados" class="table table-striped jambo_table bulk_action" width="100%" style="zoom:0.8;">
                    <thead>
                            <tr>
                                <th>Nome Aluno</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Forma de Pagamento</th>
                                <th>Situação do Pagamento</th>
                                <th>Data do Pagamento</th>
                                <th>Curso</th>
                                <th>Situação Aluno</th>
                                <th>Como conheceu?</th>
                                <th>Unidade</th>
                                <th>Unidade Destino</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                     </table>
                </div>
            </div>
        </div>
    </div>
<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function () {
      $j('.input-daterange').datepicker({
        todayBtn: 'linked',
        format: 'yyyy-mm-dd',
        autoclose: true,
      });

 var currentdate = new Date();

   function load_data(from_date = currentdate, to_date = currentdate) {
    $('#matriculados').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '/alunos_leads_externos',
        data: { from_date: from_date, to_date: to_date }
      },
      columns: [
          { data: 'pag_nome', name: 'pag_nome' },
            { data: 'pag_email', name: 'pag_email' },
            { data: 'pag_telefone', name: 'pag_telefone' },
            { data: 'formapgto', name: 'formapgto' },
            { data: 'tipo', name: 'tipo' },
            { data: 'pag_data', name: 'pag_data' },
            { data: 'pag_produto', name: 'pag_produto' },
            { data: 'situacaoaluno', name: 'situacaoaluno' },
            { data: 'conheceu', name: 'conheceu' },
            { data: 'unidade', name: 'unidade' },
            { data: 'destino', name: 'destino' },
            { data: 'action', name: 'action' }
      ],
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },
            "select": {
                "rows": {
                    "_": "Selecionado %d linhas",
                    "0": "Nenhuma linha selecionada",
                    "1": "Selecionado 1 linha"
                }
            }
        }
    });
  }

  $('#filter').click(function () {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if (from_date != '' && to_date != '') {
      $('#matriculados').DataTable().destroy();
      load_data(from_date, to_date);
    }
    else {
      alert('Data de inicio é requerido');
    }
  });

  $('#refresh').click(function () {
    $('#from_date').val('');
    $('#to_date').val('');
    $('#matriculados').DataTable().destroy();
    load_data();
  });

});
</script>

@endsection

@section('scripts')

<script src="{{URL::asset('assets/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/funil.js')}}"></script>
    <script src="{{URL::asset('assets/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{URL::asset('assets/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
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