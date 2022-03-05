<div class="row">
    <div class="col-lg-12">
        <div class="print">
            <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            <h3>Отчет по возвращенным услугам</h3>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="width: 100%">Область&nbsp;отчета</th>
                <th class="tw100">Период&nbsp;отчета</th>
                <th class="tw100">Возвратов</th>
                <th class="tw100">На&nbsp;сумму</th>
            </tr>
            <tr>
                <td>
                    @if ($currentMMC)
                        {{ \MMC\Models\MMC::find($currentMMC)->name }}
                    @else
                         @if (Auth::user()->hasRole('business.manager|business.managerbg|accountant|cashier'))
                            {{ Auth::user()->mmc->name }}
                        @else
                            Все&nbsp;ММЦ
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
                    {{ $services->count() }}
                </td>
                <td class="nbr">
                    {{ number_format($services->sum('service_price'), 0, ',', ' ') }} руб.
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            @if ($services->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Услуга</th>
                            <th>Иностранный&nbsp;гражданин</th>
                            <th>Номер&nbsp;документа</th>
                            <th>ИНН</th>
                            <th>Способ&nbsp;оплаты</th>
                            <th>Создана</th>
                            <th>Возврат</th>
                            <th>Менеджер</th>
                            <th>Кассир&nbsp;/&nbsp;Бухгалтер</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($services as $service)
                                <tr class="tr-link">
                                    <td>{{ $service->id }}</td>
                                    <td>{{ $service->service_name }}</td>
                                    <td><a href="/operator/foreigners/{{ $service->foreigner->id }}"><span class="capitalize">{{ $service->foreigner->surname }} {{ $service->foreigner->name }} {{ $service->foreigner->middle_name }}</span></a></td>
                                    <td><span class="uppercase">{{ $service->foreigner->document_series }}</span>{{ $service->foreigner->document_number }}</td>
                                    <td>
                                        @if ($service->foreigner->inn == 0 && !$service->foreigner->inn_check)
                                            <span class="text-danger">{{ $service->foreigner->inn }}</span>
                                        @else
                                            {{ $service->foreigner->inn }}
                                        @endif
                                    </td>
                                    <td>{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($service->created_at)) }}</td>
                                    <td class="nbr">{{ date('d.m.Y H:i', strtotime($service->updated_at)) }}</td>
                                    <td>{{ $service->operator->name }}</td>
                                    <td>
                                        @if (isset($service->cashier_id))
                                            {{ $service->cashier->name }}
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
