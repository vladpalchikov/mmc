<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Плательщик</th>
                <th>Первичная&nbsp;регистрация</th>
                <th>Продление</th>
                <th>Трудовой&nbsp;договор</th>
                <th>Итого</th>
            </tr>
            @foreach ($clientsData as $id => $client)
                <tr>
                    <td><a href="/operator/clients/{{ $id }}">{{ $client['name'] }}</a></td>
                    <td class="text-right">{{ $client['count_type_appeal_0'] }}</td>
                    <td class="text-right">{{ $client['count_type_appeal_1'] }}</td>
                    <td class="text-right">{{ $client['count_type_appeal_2'] }}</td>
                    <td class="text-right">{{ $client['count'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
