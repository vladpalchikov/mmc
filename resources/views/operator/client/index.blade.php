@extends('layouts.master')

@section('title', 'Представители')

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3 class="pull-left main-title">Представители<br>
                @if(Auth()->user()->hasRole('administrator|admin'))
                    <span class="text-muted"><strong>{{ number_format($countClients, 0, ',', ' ') }}</strong> {{ Helper::plural($countClients, 'представитель', 'представителя', 'представителей') }}&nbsp;и</span>
                    <span class="text-muted"><strong>{{ number_format($countClientsHosts, 0, ',', ' ') }}</strong> {{ Helper::plural($countClientsHosts, 'принимающая сторона', 'принимащие стороны', 'принимающих сторон') }} в базе</span>
                @endif
            </h3>
            @if(Auth()->user()->hasRole('managerbg|administrator|managermu|managermusn|managertm|managertmsn|accountant|chief.accountant'))
                <a href="/operator/clients/create" class="btn btn-success pull-right">Создать представителя</a>
            @endif
        </div>
    </div>

    <div class="row well mt20">
        <div class="col-md-9">
            <form action="/operator/clients">
                @if (Request::has('host'))
                    <input type="hidden" name="host" value="true">
                @endif

                @if (Request::has('clients'))
                    <input type="hidden" name="clients" value="true">
                @endif
                <input type="text" name="search" autocomplete="off" autofocus placeholder="Найти представителя" class="form-control" value="{{ Request::get('search') }}" style="width: 100%;">
                <input type="submit" class="btn btn-default float-search-button" value="Найти">
            </form>
        </div>
        <div class="col-md-3">
            <div class="btn-group pull-right" role="group" aria-label="...">
                <a href="/operator/clients" class="btn btn-default @if (!Request::has('host') && !Request::has('clients')) btn-primary @endif">Все</a>
                <a href="/operator/clients?clients=true" class="btn btn-default @if (Request::has('clients')) btn-primary @endif">Представители</a>
                <a href="/operator/clients?host=true" class="btn btn-default @if (Request::has('host')) btn-primary @endif">Принимающие стороны</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if ($clients->count() > 0)
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Физическое / юридическое лицо</th>
                            <th>Статус</th>
                            <th>Тип&nbsp;лица</th>
                            <th>ИНН</th>
                            <th>Контактное&nbsp;лицо</th>
                            <th>Телефон</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr class="tr-link" data-link="/operator/clients/{{ $client->id }}">
                                <td class="nrb">
                                    <a href="/operator/clients/{{ $client->id }}">{{ $client->name }}</a>
                                </td>
                                <td>{{ $client->is_host_only == 0 ? "Представитель" : "Принимающая сторона" }}</td>
                                <td class="nrb">{{ $client->type == 0 ? "Юридическое лицо" : "Физическое лицо" }}</td>
                                <td>{{ $client->inn }}</td>
                                <td class="nrb">{{ title_case($client->contact_person) }}</td>
                                <td>{{ empty($client->person_document_phone) ? $client->organization_contact_phone : $client->person_document_phone }}</td>
                                <td><a href="mailto:{{ $client->email }}" target="_blank">{{ $client->email }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="alert text-center mt20" role="alert">
                        <h4>Нет данных</h4>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="pull-right">
        {{ $clients->appends(Request::all())->links() }}
    </div>
@endsection
