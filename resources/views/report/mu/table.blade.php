<?php $count = 1 ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th class="tc35 text-center">№</th>
                <th>Плательщик</th>
                <th>Услуга</th>
                <th>Количество</th>
                <th>Сумма,&nbsp;руб.</th>
                <th>Способ&nbsp;оплаты</th>
                <th>Статус&nbsp;оплаты</th>
                <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
            </tr>

            @foreach ($reportServices as $service)
                <tr @if ($service->repayment_status == 3) class="bg-danger" @endif @if (!$service->payment_status) class="bg-warning" @endif>
                    <td class="text-center">{{ $count++ }}</td>
                    <td>
                        <a href="/operator/clients/{{ $service->client->id }}">{{ $service->client->name }}</a>
                    </td>
                    <td>{{ $service->service->name }}</td>
                    <td class="text-right nbr">{{ $service->service_count }}</td>
                    @if ($export)
                        <td class="text-right nbr">{{ $service->service_price*$service->service_count }}</td>
                    @else
                        <td class="text-right nbr">{{ number_format($service->service_price*$service->service_count, 0, ',', ' ') }}</td>
                    @endif
                    <td>
                        @if ($service->payment_method)
                            Безналичная оплата
                        @else
                            Наличными в кассу
                        @endif
                    </td>
                    <td>
                        {{-- \MMC\Models\ForeignerService::$statuses[$service->payment_status] --}}
                        @if ($service->payment_status)
                            <span class="text-success">{{ date('d.m.Y H:i', strtotime($service->payment_at)) }}</span>
                        @else
                            &mdash;
                        @endif
                    </td>
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