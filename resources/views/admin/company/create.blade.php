@extends('layouts.master')

@section('title', 'Новый оператор')

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'companies'])
    @else
        @include('partials.operator-menu', ['active' => 'companies'])
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
