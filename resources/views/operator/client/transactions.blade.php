@extends('layouts.master')

@section('title', $client->name)

@section('menu')
    @include('partials.operator-menu', ['active' => 'clients'])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/operator/clients/{{ $client->id }}">{{ $client->name }}</a></li>
                <li class="active">Платежи</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                        <select name="company" class="form-control pull-right" style="min-width:250px">
                            <option value="">Все операторы</option>
                            @foreach(\MMC\Models\Company::all() as $company)
                                <option @if(Request::get('company') == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary mr10" value="Найти">
            </form>
        </div>
        <div class="col-md-6">
            @if(Auth()->user()->hasRole('admin|administrator|accountant|chief.accountant'))
                <a role="button" id="add-balance" tabindex="0" data-trigger="focus" data-container="body" data-toggle="popover-balance" data-placement="bottom" class="btn btn-default pull-right text-info mr10">
                    Баланс: <strong>{{ number_format($client->getBalance(), 2, ',', ' ') }}</strong> руб.
                </a>
                <div id="popover-balance-content" class="hide">
                    <table class="c-table">
                        @foreach(\MMC\Models\Company::all() as $company)
                            <tr>
                                <td class="nbr">{{ $company->name }}</td>
                                <td class="c-sum nbr">
                                    <strong @if($company->getBalance($client) < 0) class="text-danger" @endif>
                                        {{ number_format($company->getBalance($client), 2, ',', ' ') }}
                                    </strong> руб.
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <a data-toggle="modal" data-target="#addBalance" class="btn btn-success btn-block mt10">Пополнить</a>
                </div>
                <button class="btn btn-link pull-right" disabled>Долг: <strong>{{ number_format($client->getDebts(), 2, ',', ' ') }}</strong> руб.</button>
            @endif
        </div>
    </div>

    @if ($transactions->count() > 0)
        <div class="row mt20">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Сумма, руб.</th>
                            <th>Оператор</th>
                            <th>Номер платежного поручения</th>
                            <th>Комментарий</th>
                            <th>Бухгалтер</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ date('d.m.Y H:i', strtotime($transaction->created_at)) }}</td>
                                <td>{{ number_format($transaction->sum, 2, ',', ' ') }}</td>
                                <td>
                                    @if ($transaction->company)
                                        {{ $transaction->company->name }}
                                    @endif
                                </td>
                                <td>{{ $transaction->number }}</td>
                                <td>{{ $transaction->comment }}</td>
                                <td>{{ $transaction->operator->name }}</td>
                                <td>
                                    @if(Auth()->user()->hasRole('admin|administrator|accountant|chief.accountant'))
                                        <a href="/operator/clients/{{ $client->id }}/transactions/delete?id={{ $transaction->id }}" class="btn btn-danger btn-sm">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="alert alert-info text-center mt20">Транзакций нет</div>
    @endif
    @include('modals.add-balance-client-modal')
@endsection
