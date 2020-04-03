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
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                <table id="datatable-responsive" class="table table-striped jambo_table bulk_action">
                        <thead>
                            <tr>
                                <th>Nome Aluno</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Curso de Interesse</th>
                                <th>Tentativa Pagamento</th>
                                <th>Data Tentativa</th>
                                <th>Unidade</th>
                                <!-- <th>
                                 Ação
                                </th> -->
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($alunos as $aluno)
                                <tr>
                                <td> {{$aluno->nome}}</td>
                                <td> {{$aluno->email}}</td>
                                <td>
                                @php 
                                $acentos = array(' ','-');
                                $telefone = str_replace($acentos, '', $aluno->pag_telefone); 
                                 @endphp
                                 <a href="https://api.whatsapp.com/send?1=pt_BR&phone=55{{$telefone}}" target="_blank" class="btn btn-success"> <i class="fa fa-phone"></i> Clique Aqui Whatshaap </a>
                                </td>
                                <td> {{$aluno->pag_produto}}</td>
                                <td> 
                                @if($aluno->pag_tipo == 'cartao')
                                   <button class="btn btn-success"> Cartão</button>
                                @elseif($aluno->pag_tipo == 'boleto')
                                    <button class="btn btn-primary"> Boleto</button>
                                @else
                                    <button class="btn btn-danger"> Indefinido</button>
                                @endif
                               </td>
                               <td> {{ date('d/m/Y',  strtotime($aluno->pag_data))}}</td>
                                <td>@foreach ($unidades as $uni)
                                        @if($aluno->unidade_id == $uni->sophia_id)
                                        {{$uni->Nome}}
                                        @endif
                                @endforeach
                            </td>
                            <!-- <td>
                            <form action="{{route('remover')}}" method="POST">
                                   @csrf
                                   <input name="cliente_id" value="{{$aluno->cliente_id}}"  type="hidden">
                                   <button type='submit' class="btn btn-danger"> <i class="fa fa-trash"></i> Remover </button>
                                 </form>
                            </td> -->
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
    <script src="{{URL::asset('assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{URL::asset('assets/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{URL::asset('assets/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/pdfmake/build/vfs_fonts.js')}}"></script>

@endsection
