@extends('layouts.master')

@section('title', 'Пользователи')

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'users'])
    @else
        @include('partials.operator-menu', ['active' => ''])
    @endif
@endsection

@section('content')

    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Пользователи</h3>
            <a href="/users/create" class="btn btn-success pull-right">Создать пользователя</a>
      </div>
    </div>


    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти пользователя" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:35%">
            @if ($mmc->count() > 1)
                <form class="form-inline">
                    <div class="form-group ml10 pull-left">
                        <div class="input-group">
                            <select name="mmc" class="form-control pull-right" style="min-width:150px">
                                <option value="">Все&nbsp;юниты</option>
                                @foreach($mmc as $item)
                                    <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <input type="submit" class="btn btn-primary pull-left ml10" value="Найти">
                        </div>
                    </div>
                </form>
            @endif
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a href="/users" class="btn btn-default @if (!Request::has('banned')) btn-primary @endif">Активные</a>
                <a href="/users?banned=true" class="btn btn-default @if (Request::has('banned')) btn-primary @endif">Заблокированные</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover data-table data-table-users" id="customSearchTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>ФИО</th>
                            <th>Роль</th>
                            <th>Строгая&nbsp;отчетность</th>
                            <th>Реестр</th>
                            <th>ММЦ</th>
                            <th>Телефон</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users->get() as $user)
                            @if (Auth::user()->hasRole('business.manager'))
                                @if (!$user->hasRole('admin|administrator'))
                                    @include('admin.user.row', ['user' => $user])
                                @endif
                            @else
                                @if (!$user->hasRole('admin'))
                                    @include('admin.user.row', ['user' => $user])
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
