@extends('layouts.master')

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'foreigners'])
    @else
        @include('partials.operator-menu', ['active' => 'foreigners'])
    @endif
@endsection

@section('content')
    <div class="alert text-center mt20" role="alert">
        <h4>Возможно данный гражданин был удален или перемещен. Воспользуйтесь поиском.</h4>
        <div class="row well mt20">
            <div class="col-md-6 col-md-offset-3">
                <form action="/operator/foreigners" method="GET" class="form-inline" width="100%">
                    <input type="text" name="search" autofocus placeholder="Укажите номер документа или ФИО гражданина, нажмите Enter" autofocus class="form-control" value="{{ Request::get('search') }}" style="width:100%;">
                    <input type="submit" class="btn btn-default float-search-button" value="Найти">
                </form>
            </div>
        </div>
    </div>
@endsection
