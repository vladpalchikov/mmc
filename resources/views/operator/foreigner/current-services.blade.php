@extends('layouts.master')

@section('title', 'Заявки')

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'current'])
    @else
        @include('partials.operator-menu', ['active' => 'current'])
    @endif
@endsection

@section('content')
    <div class="row">
      <div class="col-md-12">
        <h3 class="pull-left main-title main-title-single">Заявки</h3>
        @if(Auth()->user()->hasRole('administrator|managertm|managerbg|managertmsn|managermu|managermusn'))
          <a class="btn btn-success pull-right new-foreigner" data-toggle="modal" data-target="#newApplication">Новый гражданин</a>
        @endif
      </div>
    </div>

    <div class="row well mt20">

        @if (Auth::user()->hasRole('managertm|managerbg|cashier'))
        <div class="col-md-12">
            <form action="/operator/current" method="GET" class="form-inline pull-left" style="width:100%">
                <input type="text" name="search" autofocus placeholder="Укажите № заявки, номер документа или фамилию гражданина, нажмите Enter" class="form-control" value="{{ Request::get('search') }}" style="width: 100%">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>

        @else

        <div class="col-md-6">
            <form action="/operator/current" method="GET" class="form-inline pull-left" style="width:100%">
                <input type="text" name="search" autofocus placeholder="Укажите № заявки, номер документа или фамилию гражданина, нажмите Enter" class="form-control" value="{{ Request::get('search') }}" style="width: 100%">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>

        <div class="col-md-6">
            <form class="form-inline pull-right">
                @if (Auth::user()->hasRole('administrator|chief.accountant|business.manager|business.managerbg') && $mmcList->count() > 1)
                    <select name="mmc" class="form-control pull-left" style="min-width:150px">
                        <option value="">Все ММЦ</option>
                        @foreach($mmcList as $mmc)
                            <option @if(Request::get('mmc') == $mmc->id) selected @endif value="{{ $mmc->id }}">{{ $mmc->name }}</option>
                        @endforeach
                    </select>
                @endif

                @if (Auth::user()->hasRole('administrator|managertmsn|business.manager|business.managerbg|accountant|chief.accountant'))
                    <select name="manager" class="form-control pull-left ml15" style="min-width:150px">
                        <option value="">Все менеджеры</option>
                        @foreach($operators as $operator)
                        <option @if(Request::get('manager') == $operator->id) selected @endif value="{{ $operator->id }}">{{ $operator->name }}</option>
                        @endforeach
                    </select>
                    <input type="submit" class="btn btn-primary" value="Выбрать">
                @endif

            </form>
        </div>

        @endif

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if ($services->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Услуга</th>
                            <th>Иностранный&nbsp;гражданин</th>
                            <th>Номер&nbsp;документа</th>
                            <th>ИНН</th>
                            <th>Дата создания</th>
                            <th>Создал</th>
                            <th>Способ&nbsp;оплаты</th>
                            <th>Статус&nbsp;оплаты</th>
                            <th><abbr title="Пользователь подтвердивший оплату">Подтвердил</abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            @if ($service->foreigner)
                                <tr class="tr-link @if ($service->repayment_status == 3) bg-danger @endif @if (!$service->payment_status) bg-warning @endif" data-link="/operator/foreigners/{{ $service->foreigner->id }}">
                                    <td>{{ $service->id }}</td>
                                    <td>
                                        <span class="nbr tx-owr">{{ $service->service_name }}</span>
                                    </td>
                                    <td><a href="/operator/foreigners/{{ $service->foreigner->id }}">{{ title_case($service->foreigner->surname.' '.$service->foreigner->name.' '.$service->foreigner->middle_name) }}</a></td>
                                    <td><span class="uppercase">{{ $service->foreigner->document_series }}</span>{{ $service->foreigner->document_number }}</td>
                                    <td>
                                        @if ($service->foreigner->inn == 0 && !$service->foreigner->inn_check)
                                            <span class="text-danger">{{ $service->foreigner->inn }}</span>
                                        @else
                                            {{ $service->foreigner->inn }}
                                        @endif
                                    </td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                                    <td>{{ $service->operator->name }}</td>
                                    <td>{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                                    <td>
                                        @if ($service->repayment_status == 3)
                                            <span class="text-danger">Возврат</span>
                                        @else
                                            @if ($service->payment_status == 0)
                                                &mdash;
                                            @elseif ($service->payment_status == 2)
                                                <span class="text-muted">Архивирована</span>
                                            @else
                                                <span class="text-success">{{ date('d.m.Y H:i', strtotime($service->payment_at)) }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($service->cashier_id))
                                            {{ $service->cashier->name }}
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @else
                    @if (app('request')->input('search'))
                        <div style="padding-top: 5%; padding-bottom: 5%;">
                            <div class="text-center">
                                <h3>Такого гражданина нет</h3>
                                <p>Попробуйте поискать по другим параметрам</p>
                                <a href="/operator/current" class="btn btn-default mt20">Вернуться</a>
                            </div>
                        </div>
                    @else
                        <div style="padding-top: 5%; padding-bottom: 5%;">
                            <div class="text-center">
                                <h3 style="font-size:40px;">Доброе утро</h3>
                                <p>Текущих заявок нет</p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <div class="pull-right">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection
