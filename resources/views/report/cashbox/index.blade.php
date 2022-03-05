@extends('layouts.master')

@section('title', 'Услуги')

@section('menu')
    @include('partials.operator-menu', ['active' => 'cashboxreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет об оказанных услугах</h3>
            <a href="/operator/report/cashbox/print?daterange={{ Request::get('daterange') }}&manager={{ Request::get('manager') }}&mmc={{ Request::get('mmc') }}&cashier={{ Request::get('cashier') }}&client={{ Request::get('client') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|accountant|chief.accountant|business.manager|business.managerbg'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                            </div>
                        </div>
                        @if (Auth::user()->hasRole('administrator|chief.accountant'))
                            <div class="form-group">
                                <div class="input-group">
                                    <select name="mmc" class="form-control pull-right" style="min-width:150px">
                                        <option value="">Все&nbsp;юниты</option>
                                        @foreach($mmc as $item)
                                            <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="input-group">
                                <select name="cashier" id="cashier" class="form-control" style="min-width:150px">
                                    <option value="">Вся касса</option>
                                    @foreach($cashiers as $cashier)
                                        <option @if(Request::get('cashier') == $cashier->id) selected @endif @if(!Request::has('manager') && $cashier->id == Auth::user()->id) selected @endif value="{{ $cashier->id }}">{{ $cashier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    @if (Auth::user()->hasRole('cashier') && !Auth::user()->hasRole('administrator'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group">
                            <select name="company" id="company" class="form-control">
                                <option value="">Все операторы</option>
                                @foreach($companies as $company)
                                    <option @if(Request::get('company') == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('accountant|chief.accountant'))
                    <div class="form-group">
                        <div class="input-group">
                            <select name="client" class="form-control pull-right">
                                <option value="">Все плательщики</option>
                                @foreach($clients as $client)
                                    <option @if(Request::get('client') == $client->id) selected @endif value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @if ($totalServices > 0)
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 100%">Область&nbsp;отчета</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Кассир</th>
                        <th class="tw100">Оператор</th>
                        <th class="tw100">Плательщик</th>
                        <th class="tw100">Услуг&nbsp;оплачено</th>
                        <th class="tw100">Итого,&nbsp;руб.</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('mmc'))
                                {{ \MMC\Models\MMC::find(Request::get('mmc'))->name }}
                            @else
                                @if (Auth::user()->hasRole('business.manager|accountant|cashier'))
                                    {{ Auth::user()->mmc->name }}
                                @else
                                   Все&nbsp;юниты
                                @endif
                            @endif
                        </td>
                        <td class="nbr">
                          @if (count($daterange) > 1)
                              @if ($daterange[0] != $daterange[1])
                                  {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
                              @else
                                  {{ Helper::dateFromString($daterange[0]) }}
                              @endif
                          @else
                              {{ Helper::dateFromString($daterange[0]) }}
                          @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('cashier'))
                                {{ \MMC\Models\User::find(Request::get('cashier'))->name }}
                            @else
                                @if (Auth::user()->hasRole('cashier'))
                                    {{ Auth::user()->name }}
                                @else
                                    Все кассиры
                                @endif
                            @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('company'))
                                {{ \MMC\Models\Company::find(Request::get('company'))->name }}
                            @else
                                Все операторы
                            @endif
                        </td>
                        <td class="nbr">
                            @if (Request::has('client'))
                                {{ \MMC\Models\Client::find(Request::get('client'))->name }}
                            @else
                                Все плательщики&nbsp;({{ \MMC\Models\Client::count() }})
                            @endif
                        </td>
                        <td class="nbr">{{ $totalServices }}</td>
                        <td class="nbr">{{ number_format($totalPrice, 0, ',', ' ') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    @if ($totalServices > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Услуга</th>
                        <th>Оператор</th>
                        @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                            <th>Агентское вознаграждение</th>
                            <th>Сумма принципалу</th>
                        @endif
                        <th class="tw80">Цена</th>
                        <th class="tw80">Количество</th>
                        @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                            <th class="tw80">Итого&nbsp;агент</th>
                            <th class="tw80">Итого&nbsp;принципал</th>
                        @endif
                        <th class="tw80 nbr">Итого, руб.</th>
                    </tr>

                    @foreach ($reportServices as $name => $service)
                        @if ($service['count'] > 0)
                            <tr>
                                <td class="nbr">{{ $name }}</td>
                                <td class="nbr">{{ $service['company'] }}</td>
                                @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                    <td class="text-right nbr">{{ number_format($service['agent_compensation'], 0, ',', ' ') }}</td>
                                    <td class="text-right nbr">{{ number_format($service['principal_sum'], 0, ',', ' ') }}</td>
                                @endif
                                <td class="text-right nbr">{{ number_format($service['price'], 0, ',', ' ') }}</td>
                                <td class="text-right nbr">{{ $service['count'] }}</td>
                                @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                    <td class="text-right nbr"><nobr>{{ number_format($service['total_agent_compensation'], 0, ',', ' ') }}</nobr></td>
                                    <td class="text-right nbr"><nobr>{{ number_format($service['total_principal_sum'], 0, ',', ' ') }}</nobr></td>
                                @endif
                                <td class="text-right nbr"><nobr>{{ number_format($service['total_price'], 0, ',', ' ') }}</nobr></td>
                            </tr>
                        @endif
                    @endforeach
                        <tr class="active">
                            <td><strong>Всего:</strong></td>
                            @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                <td></td>
                                <td></td>
                            @endif
                            <td></td>
                            <td></td>
                            <td class="text-right nbr"><strong>{{ $totalServices }}</strong></td>
                            @if(Auth()->user()->hasRole('administrator|business.manager|accountant|chief.accountant'))
                                <td class="text-right nbr"><strong>{{ number_format($totalPriceAgent, 0, ',', ' ') }}</strong></td>
                                <td class="text-right nbr"><strong>{{ number_format($totalPricePrincipal, 0, ',', ' ') }}</strong></td>
                            @endif
                            <td class="text-right nbr"><strong>{{ number_format($totalPrice, 0, ',', ' ') }}</strong></td>
                        </tr>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет данных</div>
    @endif
@endsection
