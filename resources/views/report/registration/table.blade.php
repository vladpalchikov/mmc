<?php $count = 1 ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th class="tc35 text-center">№</th>
                <th>Название&nbsp;организации&nbsp;/&nbsp;ФИО</th>
                <th>Тип&nbsp;принимающей&nbsp;стороны</th>
                <th>Граждан</th>
            </tr>

            @foreach ($clients as $client)
                <tr>
                    <td class="text-center">{{ $count++ }}</td>
                    <td><a href="/operator/report/host?client={{ $client->id }}&daterange={{ Request::get('daterange') }}">{{ $client->name }}</a></td>
                    <td>
                        @if ($client->type)
                            Физическое&nbsp;лицо
                        @else
                            Юридическое&nbsp;лицо
                        @endif
                    </td>
                    <td>{{ $client->foreigner_count }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>