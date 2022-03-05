<div class="table-responsive">
<table class="table table-bordered table-hover">
    <tr>
        <th>Дата&nbsp;оформления</th>
        <th>На&nbsp;бирже&nbsp;с</th>
        <th>ФИО</th>
        <th>Пол</th>
        <th>Возраст</th>
        <th>Гражданство</th>
        <th>Профессия</th>
        <th>Телефон</th>
        <th>Регистрация&nbsp;до</th>
        <th>Документ</th>
        <th>Серия&nbsp;Номер</th>
        <th>Дата&nbsp;выдачи</th>
        <th>ИНН</th>
        <th>Дата&nbsp;получения&nbsp;ИНН</th>
        <th>Адрес&nbsp;регистрации</th>
	</tr>

	@foreach ($patents as $patent)
		@if ($patent->foreigner)
			<tr>
				<td class="nbr">{{ date('d.m.Y', strtotime($patent->created_at)) }}</td>
				<td class="nbr">
					@if ($patent->serviceLabor())
						{{ date('d.m.Y', strtotime($patent->serviceLabor()->created_at)) }}
					@else
						&mdash;
					@endif
				</td>
				<td class="nbr">
					@if (!$export)
						<a href="/operator/foreigners/{{ $patent->foreigner->id }}">{{ Helper::capitalize($patent->foreigner->surname) }} {{ Helper::capitalize($patent->foreigner->name) }} {{ Helper::capitalize($patent->foreigner->middle_name) }}</a>
					@else
						{{ Helper::capitalize($patent->foreigner->surname) }} {{ Helper::capitalize($patent->foreigner->name) }} {{ Helper::capitalize($patent->foreigner->middle_name) }}
					@endif
				</td>
				<td class="nbr">{{ $patent->foreigner->gender ? 'Женский' : 'Мужской' }}</td>
				<td class="nbr">{{ Helper::age($patent->foreigner->birthday) }}</td>
				<td class="nbr">{{ Helper::capitalize($patent->foreigner->nationality) }} {{ Helper::capitalize($patent->foreigner->nationality_line2) }}</td>
				<td class="nbr">{{ Helper::capitalize($patent->profession) }}</td>
				<td class="nbr">{{ $patent->foreigner->phone }}</td>
				<td class="nbr">
					@if ($patent->foreigner->registration_date)
						{{ date('d.m.Y', strtotime($patent->foreigner->registration_date)) }}
					@else
						&mdash;
					@endif
				</td>
				<td class="nbr">{{ Helper::capitalize($patent->foreigner->document_name) }}</td>
				<td class="nbr">{{ mb_strtoupper($patent->foreigner->document_series) }}{{ $patent->foreigner->document_number }}</td>
				<td class="nbr">
					@if ($patent->foreigner->document_date)
						{{ date('d.m.Y', strtotime($patent->foreigner->document_date)) }}
					@else
						&mdash;
					@endif
				</td>
				<td class="nbr">{{ $patent->foreigner->inn }}</td>
				<td class="nbr">
					@if ($patent->foreigner->inn_date)
						{{ date('d.m.Y', strtotime($patent->foreigner->inn_date)) }}
					@else
						&mdash;
					@endif
				</td>
                <td class="nbr">{{ $patent->foreigner->address }} {{ $patent->foreigner->address_line2 }} {{ $patent->foreigner->address_line3 }}</td>
			</tr>
		@endif
	@endforeach
</table>
</div>
