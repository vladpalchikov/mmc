<?php $count = 1 ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th class="tc35 text-center">№</th>
                <th>Иностранный&nbsp;гражданин</th>
                <th>Дата&nbsp;создания</th>
                <th>Тип&nbsp;принимающей&nbsp;стороны</th>
                <th>Название&nbsp;организации&nbsp;/&nbsp;ФИО</th>
                <th style="width: 200px;">Адрес&nbsp;постановки&nbsp;на&nbsp;учет</th>
                <th>Менеджер</th>
            </tr>

            @foreach ($hosts as $reg)
                <tr>
                    <td class="text-center">{{ $count++ }}</td>
                    <td><a href="/operator/foreigners/{{ $reg->foreigner_id }}" class="capitalize">{{ $reg->foreigner->surname }} {{ $reg->foreigner->name }} {{ $reg->foreigner->middle_name }}</a></td>
                    <td>{{ $reg->created_at->format('d.m.Y') }}</td>
                    <td>
                        @if ($reg->host)
                            @if ($reg->host->type)
                                Физическое&nbsp;лицо
                            @else
                                Юридическое&nbsp;лицо
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($reg->host)
                            {{ $reg->host->name }}
                        @endif
                    </td>
                    <td class="capitalize nbr" style="width: 200px;">
                        {{ $reg->foreigner_address }}
                    </td>
                    <td>
                        @if ($reg->operator)
                            {{ $reg->operator->name }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>