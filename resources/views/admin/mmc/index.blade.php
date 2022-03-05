@extends('layouts.master')

@section('title', 'Юниты')

@section('menu')
    @include('partials.admin-menu', ['active' => 'mmc'])
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Юниты</h3>
        <a href="/admin/mmc/create" class="btn btn-success pull-right">Создать юнит</a>
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
                            <th>ID</th>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th>Заголовок для документов</th>
                            <th>Код города</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mmc as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->city_code }}</td>
                                <td>
                                    <a href="/admin/mmc/{{ $item->id }}" class="btn btn-danger btn-sm ajax-delete pull-right">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                    <a href="/admin/mmc/{{ $item->id }}/edit" class="btn btn-primary btn-sm pull-right mr10">
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
