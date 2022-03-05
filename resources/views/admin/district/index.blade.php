@extends('layouts.master')

@section('title', 'ОКТМО')

@section('menu')
    @include('partials.admin-menu', ['active' => 'district'])
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Районы</h3>
        <a href="/admin/districts/create" class="btn btn-success pull-right">Добавить район</a>
      </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти район" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:100%">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover data-table" id="customSearchTable">
                    <thead>
                        <tr>
                            <th>ОКТМО</th>
                            <th>Район</th>
                            <th>ИФНС</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($districts as $district)
                            <tr>
                                <td>{{ $district->oktmo }}</td>
                                <td>{{ $district->district }}</td>
                                <td>{{ $district->ifns }}</td>
                                <td>
                                    <a href="/admin/districts/{{ $district->id }}" class="btn btn-danger btn-sm ajax-delete pull-right">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                    <a href="/admin/districts/{{ $district->id }}/edit" class="btn btn-primary btn-sm pull-right mr10">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
