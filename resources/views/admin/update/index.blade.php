@extends('layouts.master')

@section('title', 'Обновления')

@section('menu')
    @include('partials.admin-menu', ['active' => 'updates'])
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Обновления</h3>
            <a href="/admin/updates/create" class="btn btn-success pull-right">Создать запись</a>
      </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Поиск по обновлениям" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:100%">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover data-table" id="customSearchTable">
                    <thead>
                        <tr>
                            <th>Версия</th>
                            <th>Обновления</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($updates as $update)
                            <tr>
                                <td>{{ $update->version }}</td>
                                <td>{!! $update->update !!}</td>
                                <td>
                                    <a href="/admin/updates/{{ $update->id }}" class="btn btn-danger btn-sm ajax-delete pull-right">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </a>
                                    <a href="/admin/updates/{{ $update->id }}/edit" class="btn btn-primary btn-sm pull-right mr10">
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
