@extends('layouts.master')

@section('title', 'Отчет ТМ')

@section('menu')
    @include('partials.operator-menu', ['active' => 'report'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Отчет о заявках</h3>
            <a href="/operator/report/tm/print?daterange={{ Request::get('daterange') }}&@if (Request::has('manager'))&manager={{ Request::get('manager') }}@endif" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    @if (Auth::user()->hasRole('administrator|accountant|chief.accountant|business.manager|business.managerbg'))
    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Дата</div>
                            <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <select name="manager" class="form-control pull-right">
                                @foreach($operators as $operator)
                                    <option @if(Request::get('manager') == $operator->id) selected @endif @if(!Request::has('manager') && $operator->id == Auth::user()->id) selected @endif value="{{ $operator->id }}">{{ $operator->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>
    @endif

    @if (count($reportServices) > 0)
        <div class="row mt20">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Менеджер</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Граждан</th>
                        <th class="tw100">Услуг&nbsp;оплачено</th>
                        <th class="tw100">Документов</th>
                        <th class="tw100">Итого,&nbsp;руб.</th>
                    </tr>
                    <tr>
                        <td>
                            @if (Request::has('manager'))
                                {{ \MMC\Models\User::find(Request::get('manager'))->name }}
                            @else
                                {{ Auth::user()->name }}
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
                        <td>{{ $reportServices->unique('foreigner_id')->count() }}</td>
                        <td>{{ $totalPayment }}</td>
                        <td>{{ $totalDocuments }}</td>
                        <td><nobr>{{ number_format($totalPrice, 0, ',', ' ') }}</nobr></td>
                    </tr>
                </table>
            </div>
        </div>

        <?php $count = 1 ?>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="tc35 text-center">№</th>
                        <th>Иностранный&nbsp;гражданин</th>
                        <th>Принимающая&nbsp;сторона</th>
                        <th>Услуга</th>
                        <th>Сумма,&nbsp;руб.</th>
                        <th>Статус&nbsp;оплаты</th>
                        <th>Способ&nbsp;оплаты</th>
                        <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
                    </tr>

                    @foreach ($reportServices as $service)
                        <tr @if ($service->repayment_status == 3) class="bg-danger" @endif @if (!$service->payment_status) class="bg-warning" @endif>
                            <td class="text-center">{{ $count++ }}</td>
                            <td><a href="/operator/foreigners/{{ $service->foreigner_id }}" class="capitalize">{{ $service->foreigner->surname }} {{ $service->foreigner->name }} {{ $service->foreigner->middle_name }}</a></td>
                            <td>
                            @if ($service->foreigner->host)
                              {{ $service->foreigner->host->name }}
                            @else
                              &mdash;
                            @endif
                            </td>
                            <td>{{ $service->service->name }}</td>
                            <td class="text-right nbr">{{ number_format($service->service_price, 0, ',', ' ') }}</td>
                            <td>
                                @if ($service->repayment_status == 3)
                                    Возврат
                                @else
                                    {{-- \MMC\Models\ForeignerService::$statuses[$service->payment_status] --}}
                                    @if ($service->payment_status)
                                        <span class="text-success">{{ date('d.m.Y H:i', strtotime($service->payment_at)) }}</span>
                                    @else
                                        &mdash;
                                    @endif
                                @endif
                            </td>
                            <td>{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                            <td>
                                @if (isset($service->cashier_id))
                                    {{ \MMC\Models\User::find($service->cashier_id)->name }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center mt20" role="alert">Нет заявок</div>
    @endif
@endsection
