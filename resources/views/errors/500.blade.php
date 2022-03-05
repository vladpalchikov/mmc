@extends('layouts.master')

@if (Auth::check())
    @if (Auth::user()->hasRole('admin'))
        @section('menu')
            @include('partials.admin-menu', ['active' => ''])
        @endsection
    @else
        @section('menu')
            @include('partials.operator-menu', ['active' => ''])
        @endsection
    @endif
@endif

@section('content')
	<div class="jumbotron ">
        <div class="row">
            <div class="col-md-12">
                <h2>Возникла непредвиденная ошибка</h2>
                <p>Обратитесь к администратору для разрешения проблемы</p>
                <p>Ошибка:</p>
                <code>{{ $data['error'] }}</code>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt20">
                <a class="btn btn-primary" href="/operator/foreigners">Вернуться на главную</a>
            </div>
        </div>
    </div>
@endsection