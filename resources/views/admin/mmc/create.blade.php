@extends('layouts.master')

@section('title', 'Новый юнит')

@section('menu')
    @include('partials.admin-menu', ['active' => 'mmc'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
