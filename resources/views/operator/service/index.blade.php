@extends('layouts.master')

@section('title', 'Справочник услуг')

@section('menu')
    @include('partials.operator-menu', ['active' => 'services'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title">Справочник услуг</br>
                <span class="text-muted">обновлено {{ date('d.m.Y', strtotime(\MMC\Models\Service::orderBy('updated_at', 'desc')->first()->updated_at)) }}</span>
            </h3>
        </div>
    </div>
    <div class="row well mt20">
        <div class="col-md-12 nbr">
            @if(Auth::user()->hasRole('accountant|chief.accountant|business.manager|business.managerbg|administrator'))
                <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти услугу" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:50%">
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <a href="/operator/service" class="btn btn-default @if (!Request::has('active')) btn-primary @endif">Активные</a>
                    <a href="/operator/service?active=false" class="btn btn-default @if (Request::has('active')) btn-primary @endif">Заблокированные</a>
                </div>
            @else
                <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти услугу" class="form-control pull-left" value="{{ Request::get('search') }}" style="width:100%">
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover data-table data-table-services" id="customSearchTable">
                    <thead>
                        <tr>
                            <th style="width:80%">Услуга</th>
                            <th class="nbr" style="width:10%">Цена,&nbsp;руб.</th>
                            @if(Auth::user()->hasRole('accountant|chief.accountant|business.manager|business.managerbg|administrator'))
                                <th>Агентское&nbsp;вознаграждение</th>
                                <th>Сумма&nbsp;принципалу</th>
                                <th>Оператор</th>
                            @endif
                            <th style="width:10%">Обновлена</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr>
                                <td>
                                  <abbr title="{{ $service->description }}" onclick="$('.service-description').toggle()">{{ $service->name }}</abbr>
                                  <div class="service-description mt5" style="display: none;">{{ $service->description }}</div>
                                </td>
                                <td class="nbr text-right">{{ number_format($service->price, 0, ',', ' ') }}</td>
                                @if(Auth::user()->hasRole('accountant|chief.accountant|business.manager|business.managerbg|administrator'))
                                    <td class="text-right">{{ number_format($service->agent_compensation, 0, ',', ' ') }}</td>
                                    <td class="text-right">{{ number_format($service->principal_sum, 0, ',', ' ') }}</td>
                                    <td class="nbr">
                                        @if ($service->company_id)
                                            {{ \MMC\Models\Company::find($service->company_id)->name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                @endif
                                <td class="nbr">
                                    {{ date('d.m.Y', strtotime($service->updated_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
