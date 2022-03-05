@extends('layouts.master')

@section('title', 'Новый ОКТМО')

@section('menu')
    @include('partials.admin-menu', ['active' => 'district'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
