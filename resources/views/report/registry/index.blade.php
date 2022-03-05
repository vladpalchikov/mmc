@extends('layouts.master')

@section('title', 'Реестр учета приема заявлений')

@section('menu')
    @include('partials.operator-menu', ['active' => 'registryreport'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title main-title-single">
              Реестр учета приема заявлений
              @if (count($daterange) > 1)
                  @if ($daterange[0] != $daterange[1])
                      {{ Helper::dateFromString($daterange[0]) }} - {{ Helper::dateFromString($daterange[1]) }}
                  @else
                      {{ Helper::dateFromString($daterange[0]) }}
                  @endif
              @else
                  {{ Helper::dateFromString($daterange[0]) }}
              @endif
              (документов: {{ $registry->count() }})
            </h3>
            <a href="/operator/report/registry/print?daterange={{ Request::get('daterange') }}&user={{ Request::get('user') }}&skip={{ Request::get('skip') }}&limit={{ Request::get('limit') }}&mmc={{ Request::get('mmc') }}" class="btn btn-default pull-right">Распечатать</a>
        </div>
    </div>

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

                    @if (Auth::user()->hasRole('administrator|chief.accountant'))
                        <div class="form-group">
                            <div class="input-group">
                                <select name="mmc" class="form-control pull-right" style="min-width:150px">
                                    <option value="">Все ММЦ</option>
                                    @foreach($mmc as $item)
                                        <option @if(Request::get('mmc') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <select name="user" class="form-control" style="min-width:250px">
                            <option value="">Все менеджеры</option>
                            @foreach($users as $user)
                                <option @if(Request::get('user') == $user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Показать с</div>
                            <input type="number" name="skip" class="form-control input-group-addon" value={{ $skip }} style="max-width:70px" min="1">
                            <div class="input-group-addon" style="border-left: 0px;">по</div>
                            <input type="number" name="limit" class="form-control input-group-addon" value={{ $limit == 0 ? 'Все' : $limit }} style="max-width:70px; border-left: 0px;" max="{{$registry->count()}}">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary btn-report" value="Построить отчет">
                </div>
            </div>
        </form>
    </div>

    @if ($registry->count() > 0)
        {{--
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th style="width:100%">Подготовил</th>
                        <th class="tw100">Период&nbsp;отчета</th>
                        <th class="tw100">Количество&nbsp;документов</th>
                    </tr>
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
						<td class="nbr">
                            @if (count($daterange) > 1)
                                @if ($daterange[0] != $daterange[1])
                                    {{ $daterange[0] }} - {{ $daterange[1] }}
                                @else
                                    {{ $daterange[0] }}
                                @endif
                            @else
                                {{ $daterange[0] }}
                            @endif
						</td>
						<td>{{ $registry->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
        --}}
        <?php $count = $skip; ?>
        <div class="row well mt20">
            <div class="col-md-4">
                <input type="text" name="search" id="customSearch" autocomplete="off" autofocus placeholder="Найти в реестре" class="form-control" value="{{ Request::get('search') }}" style="width:100%;">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered data-table m-align table-hover" id="customSearchTable">
                    <thead>
                        <tr>
                            <th class="tc35 nbr" style="border-left: 3px solid #ebebeb">П/н</th>
                            <th>ФИО&nbsp;Заявителя</th>
                            <th>Дата&nbsp;рождения</th>
                            <th>Серия</th>
                            <th>Номер</th>
                            <th>Дата&nbsp;выдачи</th>
                            <th>Гражданство</th>
                            <th>ФИО ответственного сотрудника</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>

                    <tbody class="documents-rows">
                        @foreach ($registry as $document)
                            @if ($document->foreigner)
                                <tr>
                                    <td
                                        class="
                                            @if ($document->doc_status == 0)
                                                registry-status-0
                                            @elseif ($document->doc_status == 1)
                                                registry-status-1
                                            @else
                                                registry-status-2
                                            @endif
                                        "
                                    >{{ $count++ }}</td>
                                    <td><a href="/operator/foreigners/{{ $document->foreigner_id }}" class="capitalize">{{ $document->foreigner->surname }} {{ $document->foreigner->name }} {{ $document->foreigner->middle_name }}</a></td>
                                    <td>{{ date('d.m.Y', strtotime($document->foreigner->birthday)) }}</td>
                                    <td class="uppercase">
                                      @if ($document->foreigner->document_series)
                                          {{ $document->foreigner->document_series }}
                                      @else
                                          &mdash;
                                      @endif
                                    </td>
                                    <td>{{ $document->foreigner->document_number }}</td>
                                    <td>{{ date('d.m.Y', strtotime($document->foreigner->document_date)) }}</td>
                                    <td>{{ $document->foreigner->nationality }}</td>
                                    <td>{{ $document->docuser->name }}</td>
                                    <td>
                                          @if ($document->doc_status == 1)
                                            <a class="btn btn-success btn-sm btn-block registry-approve-change" href="/operator/report/registry/approve/{{ ($document instanceof \MMC\Models\ForeignerPatent) ? 'patent' : 'patentrecertifying' }}/{{ $document->id }}">Подтвердить</a>
                                          @else
                                            <a class="btn btn-default btn-sm btn-block registry-approve-change" href="/operator/report/registry/approve/{{ ($document instanceof \MMC\Models\ForeignerPatent) ? 'patent' : 'patentrecertifying' }}/{{ $document->id }}">Отменить подтверждение</a>
                                          @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-default btn-sm btn-danger btn-block registry-remove" href="/operator/report/registry/remove/{{ ($document instanceof \MMC\Models\ForeignerPatent) ? 'patent' : 'patentrecertifying' }}/{{ $document->id }}">Удалить из реестра</a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center" role="alert">Нет документов</div>
    @endif
@endsection
