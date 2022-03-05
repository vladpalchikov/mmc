@extends('layouts.master')

@section('title', 'Операторы')

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'companies'])
    @else
        @include('partials.operator-menu', ['active' => 'companies'])
    @endif
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Операторы</h3>
            <a href="/admin/companies/create" class="btn btn-success pull-right">Создать оператора</a>
      </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти оператора" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:100%">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover data-table" id="customSearchTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>
                                    <a href="/admin/companies/{{ $company->id }}" class="btn btn-danger btn-sm ajax-delete pull-right">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                    <a href="/admin/companies/{{ $company->id }}/edit" class="btn btn-primary btn-sm pull-right mr10">
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
