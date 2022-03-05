@extends('layouts.master')

@section('title', 'МУ')

@section('menu')
    @include('partials.operator-menu', ['active' => 'muservices'])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Миграционный учет</h3>
            @if(Auth::user()->hasRole('administrator|managermu|managermusn'))
                <a class="btn btn-success pull-right new-application" href="/operator/muservices/create">Создать заявку</a>
            @endif
        </div>
    </div>

    <div class="row well mt20">

        @if (Auth::user()->hasRole('managermu|cashier'))

        <div class="col-md-12">
            <form action="/operator/muservices" method="GET" class="form-inline pull-left" style="width:100%">
                <input type="text" name="search" autofocus placeholder="Укажите № заявки или Плательщика, нажмите Enter" class="form-control" value="{{ Request::get('search') }}" style="width:100%">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>

        @else

        <div class="col-md-6">
            <form action="/operator/muservices" method="GET" class="form-inline pull-left" style="width:100%">
                <input type="text" name="search" autofocus placeholder="Укажите № заявки или Плательщика, нажмите Enter" class="form-control" value="{{ Request::get('search') }}" style="width:100%">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>

        <div class="col-md-6">
            <form class="form-inline pull-right">

                @if (Auth::user()->hasRole('administrator|chief.accountant|business.manager') && $mmcList->count() > 1)
                    <select name="mmc" class="form-control" style="min-width:150px">
                        <option value="">Все ММЦ</option>
                        @foreach($mmcList as $mmc)
                            <option @if(Request::get('mmc') == $mmc->id) selected @endif value="{{ $mmc->id }}">{{ $mmc->name }}</option>
                        @endforeach
                    </select>
                @endif

                @if (Auth::user()->hasRole('administrator|managertmsn|business.manager|accountant|chief.accountant|cashier'))
                    <select name="manager" class="form-control ml15" style="min-width:150px">
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
            @if ($groups->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover data-table-services">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Услуга</th>
                                <th>Плательщик</th>
                                <th>Количество</th>
                                <th>Итого,&nbsp;руб.</th>
                                <th>Способ&nbsp;оплаты</th>
                                <th>Создана</th>
                                <th>Статус&nbsp;оплаты</th>
                                <th>Менеджер</th>
                                <th><abbr title="Пользователь подтвердивший оплату">Подтвердил</abbr></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr class="tr-link @if (!$group->payment_status) bg-warning @endif" data-link="/operator/group/{{ $group->id }}">
                                    <td>{{ $group->id }}</td>
                                    <td>{{ $group->service->name }}</td>
                                    <td><a href="/operator/clients/{{ $group->client->id }}">{{ $group->client->name }}</a></td>
                                    <td style="text-align: right">{{ $group->service_count }}</td>
                                    <td style="text-align: right">{{ number_format($group->service_count * $group->service_price, 0, ',', ' ') }}</td>
                                    <td>{{ $group->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($group->created_at)) }}</td>
                                    <td>
                                        @if ($group->payment_status == 0)
                                            &mdash;
                                        @else
                                            <span class="text-success">{{ date('d.m.Y H:i', strtotime($group->payment_at)) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $group->operator->name }}</td>
                                    <td>
                                        @if (isset($group->cashier_id))
                                            {{ $group->cashier->name }}
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right">
                    {{ $groups->links() }}
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">Нет заявок</div>
            @endif
        </div>
    </div>
@endsection
