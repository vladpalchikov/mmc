@extends('layouts.master')

@section('title', 'Строгая отчетность')

@section('menu')
    @include('partials.operator-menu', ['active' => 'blankreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">Строгая отчетность</h3>
            <a href="/operator/report/blank/print?daterange={{ Request::get('daterange') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

    <div class="well mt20">
        <form class="form-inline">
            <div class="row">
                <div class="col-md-12">
                    @if (Auth::user()->hasRole('administrator|managertmsn|accountant|chief.accountant|business.manager'))
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="false" class="form-control" value={{ $daterange[0] }}-{{ $daterange[1] }}>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Дата</div>
                                <input type="text" name="daterange" data-single="true" class="form-control" value={{ Request::get('daterange') }}>
                            </div>
                        </div>
                    @endif
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @if ($blanks->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Подготовил</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Количество&nbsp;бланков</th>
                    </tr>
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
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
                        <td>{{ $blanks->count() }}</td>
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
                        <th>Дата&nbsp;оформления</th>
                        <th>ФИО&nbsp;ИГ</th>
                        <th>Паспорт&nbsp;ИГ</th>
                        <th>Номер&nbsp;документа</th>
                        <th>Менеджер</th>
                    </tr>

                    @foreach ($blanks as $blank)
                        <tr>
                            <td class="text-center">{{ $count++ }}</td>
                            <td>{{ date('d.m.Y H:i', strtotime($blank->created_at)) }}</td>
                            <td><a href="/operator/foreigners/{{ $blank->foreigner_id }}" class="capitalize">{{ $blank->foreigner->surname }} {{ $blank->foreigner->name }} {{ $blank->foreigner->middle_name }}</a></td>
                            <td>{{ $blank->foreigner->document_series }}{{ $blank->foreigner->document_number }}</td>
                            <td>{{ $blank->full_number }}</td>
                            <td>{{ $blank->operator->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет бланков</div>
    @endif
@endsection
