@extends('layouts.master')

@section('title', 'Заявка №'.$group->id.' - '.$group->client->name)

@section('menu')
    @include('partials.operator-menu', ['active' => 'muservices'])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/operator/clients/{{ $group->client->id }}">{{ $group->client->name }}</a></li>
                <li class="active">Заявка №{{ $group->id }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3 class="uppercase">Заявка №{{ $group->id }}</h3>
        </div>
    </div>

    <div class="row mt10">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>№</th>
                    <th>ФИО</th>
                    <th>Дата&nbsp;рождения</th>
                    <th>Документ</th>
                    <th>Серия / Номер</th>
                    <th>Национальность</th>
                    <th>Тип обращения</th>
                </tr>
                @foreach ($group->services as $service)
                    @if ($service->foreigner)
                        <tr>
                            <td class="nbr">{{ $service->id }}</td>
                            <td>
                                <a class="capitalize" href="/operator/foreigners/{{ $service->foreigner->id }}">{{ $service->foreigner->surname }} {{ $service->foreigner->name }} {{ $service->foreigner->middle_name }}</a>
                            </td>
                            <td><nobr>{{ date('d.m.Y', strtotime($service->foreigner->birthday)) }}</nobr></td>
                            <td class="capitalize">{{ $service->foreigner->document_name }}</td>
                            <td class="uppercase">{{ $service->foreigner->document_series }} {{ $service->foreigner->document_number }}</td>
                            <td class="capitalize">{{ $service->foreigner->nationality }}</td>
                            <td>{{ $service->getTypeAppeal() }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
@endsection
