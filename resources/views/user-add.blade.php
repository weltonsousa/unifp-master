@extends('layouts.app')
@section('styles')
@endsection
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Novo Usuário</h3>
            </div>
            <div class="title_right">
                <div class="col-md-2 col-sm-2 col-xs-12 pull-right ">
                    <a href="{{ route('users')}}" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <form class="form-horizontal form-label-left" novalidate action="{{ route('user-post') }}" method="post">
                        @csrf
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            {{ __('Name') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="name" type="text"
                                    class="form-control  col-md-7 col-xs-12{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name"
                                    value="{{ old('name') }}"
                                    autofocus
                                    data-validate-length-range="6"
                                    data-validate-words="2"
                                    placeholder="exemplo: Antonio Rodrigues" required="required" />
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">
                            {{ __('E-mail') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="email" type="email"
                                    class="form-control  col-md-7 col-xs-12{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autofocus
                                    placeholder="antonio.rodrigues@gracomonline.com.br" required="required" />
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                            {{ __('Senha') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="password" type="password"
                                    class="form-control  col-md-7 col-xs-12{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password"
                                    value="{{ old('password') }}"
                                    autofocus
                                    placeholder="digite uma senha" required="required" />
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation">
                            {{ __('Confirmação de Senha') }} <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="password_confirmation" type="password"
                                    class="form-control  col-md-7 col-xs-12"
                                    name="password_confirmation"
                                    value=""
                                    autofocus
                                    placeholder="confirme sua senha" required="required" />
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="unidade">
                            Selecione Unidade <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="unidade" class="form-control  col-md-7 col-xs-12"
                                    name="unidade">
                                    @foreach($unidades as $unidade)
                                    <option value="{{$unidade->IdUnidade}}">{{$unidade->Nome}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tipo-unidade">
                                 Tipo Unidade <span class="required">*</span>
                               </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="unidade" class="form-control  col-md-7 col-xs-12"
                                    name="tipo-unidade">
                                    <option value="1">Própria</option>
                                    <option value="2">Franquia</option>
                                </select>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <button id="send" type="submit" class="btn btn-success">Salvar</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- FastClick -->
    <script src="{{URL::asset('assets/fastclick/lib/fastclick.js')}}"></script>
    <!-- validator -->
    <script src="{{URL::asset('assets/validator/validator.js')}}"></script>
@endsection
