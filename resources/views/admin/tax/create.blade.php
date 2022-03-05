@extends('layouts.master')

@section('title', 'Новый налог')

@section('menu')
    @include('partials.admin-menu', ['active' => 'taxes'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
