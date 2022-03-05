@extends('layouts.master')

@section('title', 'Новая версия')

@section('menu')
    @include('partials.admin-menu', ['active' => 'updates'])
@endsection

@section('content')
    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Создать запись</h3>
      </div>
    </div>
    <div class="row">
        <div class="col-md-6 form">
            {!! form($form) !!}
        </div>
    </div>
@endsection
