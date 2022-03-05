<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <tr>
                <th style="width: 80%">Менеджер</th>
                <th class="tw100">Роль</th>
                <th class="tw100">Граждан</th>
                <th class="tw100">Заявок</th>
                <th class="tw100">Возвратов</th>
                <th class="tw100">Оплачено</th>
                <th class="tw100">Оплачено&nbsp;(комп.)</th>
                <th class="tw100">Документов</th>
                <th class="tw100">Перерасчет</th>
                <th class="nbr tw100"><div><span>Итого,&nbsp;руб.</span></div></th>
                <th class="nbr tw100"><div><span>Итого,&nbsp;услуг</span></div></th>
                <th class="tw100">План</th>
                <th class="nbr tw100">Стоимость, руб.</th>
                <th class="nbr tw100">Премия, руб.</th>
            </tr>
            @foreach ($servicesByOperator as $row)
                <tr>
                    <td style="overflow-y: hidden;" class="nbr">
                        @if ($row['operator']->role == 'Ст. Менеджер ТМ' || $row['operator']->role == 'Менеджер ТМ')
                            <a href="/operator/report/tm?daterange={{ Request::get('daterange') }}&manager={{ $row['operator']->id }}">{{ $row['operator']->name }}</a>
                        @elseif ($row['operator']->role == 'Ст. Менеджер МУ' || $row['operator']->role == 'Менеджер МУ')
                            <a href="/operator/report/mu?daterange={{ Request::get('daterange') }}&manager={{ $row['operator']->id }}">{{ $row['operator']->name }}</a>
                        @endif
                    </td>
                    <td style="overflow-y: hidden;" class="nbr">
                        {{ $row['operator']->role }}
                    </td>
                    <td class="text-right">
                        @if ($row['totalClients'] == 0)
                            0
                        @else
                            {{ $row['totalClients'] }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalServices'] == 0)
                            0
                        @else
                            {{ $row['totalServices'] }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalRepayment'] == 0)
                            0
                        @else
                            {{ $row['totalRepayment'] }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalPayment'] == 0)
                            0
                        @else
                            @if ($row['operator']->role == 'Ст. Менеджер ТМ' || $row['operator']->role == 'Менеджер ТМ')
                                {{ number_format($row['totalPayment'] / 3, 1, ',', ' ') }}
                            @else
                                {{ $row['totalPayment'] }}
                            @endif
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalPaymentComplex'] == 0)
                            0
                        @else
                            {{ $row['totalPaymentComplex'] }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalDocuments'] == 0)
                            0
                        @else
                            {{ $row['totalDocuments'] }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($row['totalDocuments'] == 0)
                            0
                        @else
                            {{ number_format($row['totalDocuments'] * 3.2, 1, ',', ' ') }}
                        @endif
                    </td>

                    <td class="text-right nbr">
                        @if ($row['totalPrice'] == 0)
                            0
                        @else
                            {{ number_format($row['totalPrice'], 0, ',', '') }}
                        @endif
                    </td>
                    <td class="text-right nbr" style="position: relative">
                        @if ($row['operator']->role == 'Ст. Менеджер ТМ' || $row['operator']->role == 'Менеджер ТМ')
                            {{ number_format(($row['totalPayment'] / 3) + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2), 1, ',', '') }}
                            <div style="position: absolute; bottom: -1px; left:0; background-color: #1abc9c; width: {{ number_format((($row['totalPayment'] / 3) + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2))/1680*100) }}%; height: 3px"></div>
                        @else
                            {{ $row['totalPayment'] }}
                            <div style="position: absolute; bottom: -1px; left:0; background-color: #1abc9c; width: {{ number_format($row['totalPayment']/1680*100) }}%; height: 3px"></div>
                        @endif
                    </td>
                    <td class="text-right">1680</td>
                    <td class="text-right nbr">
                        @if ($row['operator']->role == 'Ст. Менеджер ТМ' || $row['operator']->role == 'Менеджер ТМ')
                            30
                        @elseif ($row['operator']->role == 'Ст. Менеджер МУ' || $row['operator']->role == 'Менеджер МУ')
                            14
                        @endif
                    </td>
                    <td class="text-right nbr">
                        @if ($row['operator']->role == 'Ст. Менеджер ТМ' || $row['operator']->role == 'Менеджер ТМ')
                            @if (((($row['totalPayment'] / 3) + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2)) - 1680) * 30 < 0)
                                0
                            @else
                                {{ number_format(((($row['totalPayment'] / 3) + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2)) - 1680) * 30, 2, ',', '') }}
                            @endif
                        @elseif ($row['operator']->role == 'Ст. Менеджер МУ' || $row['operator']->role == 'Менеджер МУ')
                            @if ((($row['totalPayment'] + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2)) - 1680) * 14 < 0)
                                0
                            @else
                                {{ number_format((($row['totalPayment'] + $row['totalPaymentComplex'] + ($row['totalDocuments'] * 3.2)) - 1680) * 14, 2, ',', '') }}
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
