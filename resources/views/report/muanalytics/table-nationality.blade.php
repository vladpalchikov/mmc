<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <tr>
                <th>Национальность</th>
                <th>Первичная&nbsp;регистрация</th>
                <th>Продление</th>
                <th>Трудовой&nbsp;договор</th>
                <th>Итого</th>
            </tr>
            @foreach ($nationalityData as $nationality)
                <tr>
                    <td class="capitalize">{{ $nationality['nationality'] }}</td>
                    <td class="text-right">{{ $nationality['count_type_appeal_0'] }}</td>
                    <td class="text-right">{{ $nationality['count_type_appeal_1'] }}</td>
                    <td class="text-right">{{ $nationality['count_type_appeal_2'] }}</td>
                    <td class="text-right">{{ $nationality['count'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
