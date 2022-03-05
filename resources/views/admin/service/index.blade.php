@extends('layouts.master')

@section('title', 'Услуги')

@section('menu')
    @if (Auth::user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'services'])
    @else
        @include('partials.operator-menu', ['active' => ''])
    @endif
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Услуги</h3>
            <a href="/services/create" class="btn btn-success pull-right">Создать услугу</a>
        </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-12 nbr">
            <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти услугу" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:50%">
            @if ($companies->count() > 1)
                <form class="form-inline">
                    <div class="form-group ml10 pull-left">
                        <div class="input-group">
                            <select name="company" class="form-control pull-right" style="min-width:150px">
                                <option value="">Все&nbsp;операторы</option>
                                @foreach($companies as $company)
                                    <option @if(Request::get('company') == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <select name="module" class="form-control pull-right" style="min-width:150px">
                                <option value="">Модуль</option>
                                <option value="0" @if(Request::get('module') == 0) selected @endif>Трудовая миграция</option>
                                <option value="1" @if(Request::get('module') == 1) selected @endif>Миграционный учет</option>
                                <option value="2" @if(Request::get('module') == 2) selected @endif>Блок гражданство</option>
                            </select>
                            <input type="submit" class="btn btn-primary pull-left ml10" value="Найти">
                        </div>
                    </div>
                </form>
            @endif
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a href="/services" class="btn btn-default @if (!Request::has('active')) btn-primary @endif">Активные</a>
                <a href="/services?active=false" class="btn btn-default @if (Request::has('active')) btn-primary @endif">Заблокированные</a>
            </div>
        </div>
    </div>

    @if (isset($services))
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover data-table data-table-services" id="customSearchTable">
                        <thead>
                            <tr>
                                <th>Вес</th>
                                <th>Название</th>
                                <th>Цена,&nbsp;руб</th>
                                <th>Биржа&nbsp;труда</th>
                                <th>Оператор</th>
                                <th>Налог</th>
                                <th>Модуль</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->order }}</td>
                                    <td>
                                        @if ($service->is_complex)
                                            <abbr class="text-danger nbr tx-owr" title="{{ $service->description }}" onclick="$('.service-description').toggle()">{{ $service->name }}</abbr>
                                            <div class="service-description mt5" style="display: none;">{{ $service->description }}</div>
                                        @else
                                            <abbr class="nbr tx-owr" title="{{ $service->description }}" onclick="$('.service-description').toggle()">{{ $service->name }}</abbr>
                                            <div class="service-description mt5" style="display: none;">{{ $service->description }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($service->price, 0, ',', ' ') }}</td>
                                    <td>
                                        @if ($service->labor_exchange)
                                            Да
                                        @endif
                                    </td>
                                    <td class="nbr">
                                        @if ($service->company_id)
                                            {{ \MMC\Models\Company::find($service->company_id)->name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="nbr">
                                        @if ($service->tax)
                                            {{ $service->tax->name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="nbr">{{ \MMC\Models\Service::$modules[$service->module] }}</td>
                                    <td width="100px">
                                        <a href="/services/{{ $service->id }}/edit" class="btn btn-primary btn-sm btn-block">
                                            Редактировать
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
