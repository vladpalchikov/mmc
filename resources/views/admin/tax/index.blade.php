@extends('layouts.master')

@section('title', 'Налоги и пошлины')

@section('menu')
    @include('partials.admin-menu', ['active' => 'taxes'])
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Налоги</h3>
        <a href="/admin/taxes/create" class="btn btn-success pull-right">Добавить налог</a>
      </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти юнит" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:100%">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover data-table" id="customSearchTable">
                    <thead>
                        <tr>
                            <th>Код</th>
                            <th>Название</th>
                            <th>Комментарий</th>
                            <th>Сумма</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->code }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->comment }}</td>
                                <td>{{ $tax->price }}</td>
                                <td class="nbr">
                                    <a href="/admin/taxes/{{ $tax->id }}" class="btn btn-danger btn-sm ajax-delete pull-right">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                    <a href="/admin/taxes/{{ $tax->id }}/edit" class="btn btn-primary btn-sm pull-right mr10">
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
