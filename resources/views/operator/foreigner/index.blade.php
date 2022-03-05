@extends('layouts.master')

@section('title', 'Граждане')

@section('menu')
    @if(Auth()->user()->hasRole('admin'))
        @include('partials.admin-menu', ['active' => 'foreigners'])
    @else
        @include('partials.operator-menu', ['active' => 'foreigners'])
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title">Иностранные граждане<br>
                <span class="text-muted"><strong>{{ number_format(\MMC\Models\Foreigner::count(), 0, ',', ' ') }}</strong> граждан в базе</span>
            </h3>
            @if(Auth()->user()->hasRole('administrator|managertm|managertmsn|managermu|managermusn|managerbg'))
                <a class="btn btn-success pull-right new-foreigner" data-toggle="modal" data-target="#newApplication">Новый гражданин</a>
            @endif
        </div>
    </div>
    <div class="row well mt20">
        <div class="col-md-12">
            <form action="/operator/foreigners" method="GET" class="form-inline" width="100%">
                <input type="text" name="search" autofocus placeholder="Укажите номер документа или ФИО гражданина, нажмите Enter" autofocus class="form-control" value="{{ Request::get('search') }}" style="width:100%;">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>
    </div>
    @if(Auth()->user()->hasRole('managertm|managertmsn|managermu|managermusn|managerbg') && !Request::get('search'))
      <div class="alert text-center mt20" style="margin-top: 150px" role="alert">
          <h4>Поиск по базе граждан возможен по номеру документа или ФИО</h4>
          @if(Auth()->user()->hasRole('administrator|managertm|managertmsn|managermu|managermusn|managerbg'))
              <a class="btn btn-success new-foreigner mt20" data-toggle="modal" data-target="#newApplication">Новый гражданин</a>
          @endif
      </div>
    @else
    <div class="row">
        <div class="col-md-12">
            @if ($foreigners->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="nbr">ФИО</th>
                                <th>Номер документа</th>
                                <th>ИНН</th>
                                <th>ОКТМО</th>
                                <th>Регистрация до</th>
                                <th>Создан</th>
                                <th>Изменен</th>
                                <th>Изменил</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($foreigners as $foreigner)
                                <tr class="tr-link" data-link="/operator/foreigners/{{ $foreigner->id }}">
                                    <td>
                                        <a href="/operator/foreigners/{{ $foreigner->id }}">
                                            {{ title_case($foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name) }}
                                        </a>
                                    </td>
                                    <td>{{ mb_strtoupper($foreigner->document_series) }}{{ $foreigner->document_number }}</td>
                                    <td>
                                        @if ($foreigner->inn == 0 && !$foreigner->inn_check)
                                            <span class="text-danger">{{ isset($foreigner->inn) ? $foreigner->inn : '0' }}</span>
                                        @else
                                            {{ isset($foreigner->inn) ? $foreigner->inn : '0' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($foreigner->oktmo_fail)
                                            <span class="text-danger" title="ОКТМО введен вручную">{{ isset($foreigner->oktmo) ? $foreigner->oktmo : '0' }}</span>
                                        @else
                                            {{ isset($foreigner->oktmo) ? $foreigner->oktmo : '0' }}
                                        @endif
                                    </td>
                                    <td>{{ isset($foreigner->registration_date) ? date('d.m.Y', strtotime($foreigner->registration_date)) : '&mdash;' }}</td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($foreigner->created_at)) }}</td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($foreigner->updated_at)) }}</td>
                                    <td>
                                        @if ($foreigner->updatedByUser)
                                            {{ $foreigner->updatedByUser->name }}
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
                    {{ $foreigners->links() }}
                </div>
            @else
                <div class="alert text-center mt20" role="alert">
                    <h4>Граждан с такими данными нет</h4>
                    <a href="/operator/foreigners" class="btn btn-default mt20">Назад</a>
                </div>
            @endif
        </div>
    </div>
  @endif
@endsection
