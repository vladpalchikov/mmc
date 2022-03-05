@extends('layouts.print')

@section('content')

<style>
	.document-preview {
        font: 10pt/12pt "Times New Roman";
    }

	.check-section-row {
		display: inline-block;
		width: 16px;
		height: 18px;
		text-align: center;
		border: 1px solid black;
	}

	.underline-block {
	  display: inline-block;
	  position: relative;
	  text-align: center;
	  width: 200px;
	  margin-top: 10px;
	  border-bottom: 1px solid black;
	}

	.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
			border: 1px solid #000 !important;
	}

	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
		line-height: 1;
		padding: 2px;
	}

	@media print {
        .document-preview {
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
						position: relative;
        }

        .document-action {
            display: none;
        }

        .print-size {
        	margin-left: -10px !important;
        }

        .page {
        	border: none;
        	padding: 0 !important;
        }
    }
</style>
<div class="document-action container without-padding">
	<!-- <a href="/operator/foreigners/{{ $dms->application_id }}/" class="btn btn-danger btn-block pull-right">Закрыть</a>-->
</div>

<div class="document-preview">
    <div class="container-fluid">
	<p>
		<strong>1.СТРАХОВЩИК: Открытое акционерное общество «Национальная страховая компания ТАТАРСТАН» (сокращенное наименование ОАО «НАСКО»)</strong>
		Лицензия СЛ № 3116 выданная 19.08.2015г. Центральным Банком Российской Федерации.
	</p>

	<table class="table table-bordered mb5">
		<tr>
			<td>Адрес: 443099 г.Самара ул. Фрунзе, д.70</td>
			<td>Тел. (846) 373-86-25</td>
			<td>Сайт: www.nasko.ru</td>
		</tr>
		<tr>
			<td colspan="2">р\с 40701810879020000002 Самарский филиал ПАО «АК БАРС» Банк г.Самара</td>
			<td>к\с 30101810400000000850</td>
		</tr>
		<tr>
			<td>БИК 043601850</td>
			<td>КПП 631743001</td>
			<td>ИНН 1657023630</td>
		</tr>
	</table>

	<p>Все графы заполняются на основании сведений: для граждан РФ - паспорта гражданина РФ, для иностранных граждан и лиц без гражданства на основании данных документа, предусмотренного федеральным законом или признаваемого в соответствии с международным договором РФ в качестве документа, удостоверяющего личность иностранного гражданина или лица без гражданства.</p>

	<strong>2. СТРАХОВАТЕЛЬ</strong>

	<table class="table table-bordered">
		<tr>
			<td width="60%" colspan="2">
				ФИО - для физических лиц, индивидуальных предпринимателей, в т.ч. на латинице для
				иностранных граждан и лиц без гражданства. Для юридических лиц – организационно-
				правовая форма, наименование (полное и сокращенное), в т.ч. фирменное, при наличии с
				указанием на языке народов РФ или иностранном языке.
			</td>

			<td width="40%" class="td-centered" colspan="3">
				<span class="capitalize">{{ $dms->dms_surname }} {{ $dms->dms_name }} {{ $dms->dms_middle_name }}</span>
			</td>
		</tr>

		<tr>
			<td class="td-centered">
				Пол (м/ж) &nbsp;&nbsp;&nbsp; {{ $dms->dms_gender ? 'Ж' : 'М' }}
			</td>

			<td class="td-centered">
				Дата рождения &nbsp;&nbsp;&nbsp; {{ date('d.m.Y', strtotime($dms->dms_birthday)) }}
			</td>

			<td class="td-centered" colspan="3">
				Гражданство (при наличии) &nbsp;&nbsp;&nbsp; {{ $dms->dms_nationality }}
			</td>
		</tr>

		<tr>
			<td width="60%" colspan="2" rowspan="2">
				Адрес: для физических лиц - место жительства (регистрации) или место пребывания на
				территории РФ. Для юридических лиц – место нахождения исполнительного органа, для ИП-
				адрес регистрации.
			</td>

			<td colspan="3">
				{{ $dms->dms_address }} {{ $dms->dms_address_line2 }} {{ $dms->dms_address_line3 }}
			</td>
		</tr>

		<tr>
			<td colspan="3">
				Дата регистрации &nbsp;&nbsp;&nbsp; {{ date('d.m.Y', strtotime($dms->dms_registration_date)) }}
			</td>
		</tr>
		<tr>
			<td>Основной документ, удостоверяющий личность</td>
			<td>
				Наименование <br>
				{{ $dms->dms_document }}
			</td>
			<td>
				Серия, номер <br>
				{{ $dms->dms_document_series }} {{ $dms->dms_document_number }}
			</td>
			<td>
				Дата выдачи <br>
				{{ date('d.m.Y', strtotime($dms->dms_document_date)) }}
			</td>
			<td>
				Кем выдан <br>
				{{ $dms->dms_document_issuedby }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				Контактный телефон (факс), e-mail, сайта (при наличии)
			</td>
			<td colspan="2"></td>
			<td></td>
		</tr>
		<tr>
			<td style="width: 20%">Для ИП</td>
			<td>
				Дата государственной регистрации ИП <br>
				{{ date('d.m.Y', strtotime($dms->dms_registration_ip_date)) }}
			</td>
			<td colspan="3" class="td-centered">
				Документ, подтверждающий факт внесения в ЕГРИП записи о государственной регистрации ИП <br>
				{{ $dms->dms_registration_document }}
			</td>
		</tr>
	</table>

	<strong>3. ЗАСТРАХОВАННОЕ ЛИЦО</strong>
	<table class="table table-bordered mb5">
		<tr>
			<td width="60%" colspan="2" class="td-centered">
				ФИО. Для иностранных граждан и лиц без гражданства дополнительно на латинице.
			</td>

			<td width="40%" class="td-centered" colspan="3">
				<span class="capitalize">{{ $foreigner->surname }} {{ $foreigner->name }} {{ $foreigner->middle_name }}</span> <br>
				<span class="capitalize">{{ Helper::translit($foreigner->surname) }} {{ Helper::translit($foreigner->name) }}</span>
			</td>
		</tr>
		<tr>
			<td class="td-centered">
				Пол (м/ж) &nbsp;&nbsp;&nbsp; {{ $foreigner->gender ? 'Ж' : 'М' }}
			</td>

			<td class="td-centered">
				Дата рождения &nbsp;&nbsp;&nbsp; {{ date('d.m.Y', strtotime($foreigner->birthday)) }}
			</td>

			<td class="td-centered" colspan="3">
				Гражданство (при наличии) &nbsp;&nbsp;&nbsp; {{ $foreigner->nationality }}
			</td>
		</tr>
		<tr>
			<td>Основной документ, удостоверяющий личность</td>
			<td>
				Наименование <br>
				{{ $foreigner->document }}
			</td>
			<td>
				Серия, номер <br>
				{{ $foreigner->document_series }} {{ $foreigner->document_number }}
			</td>
			<td>
				Дата выдачи <br>
				{{ date('d.m.Y', strtotime($foreigner->document_date)) }}
			</td>
			<td>
				Кем выдан <br>
				{{ $foreigner->document_issuedby }}
			</td>
		</tr>
		<tr>
			<td class="td-centered">
				Адрес места жительства (регистрации) или адрес места пребывания на территории РФ
			</td>
			<td colspan="4">
				{{ $foreigner->address }} {{ $foreigner->address_line2 }} {{ $foreigner->address_line3 }}
			</td>
		</tr>
		<tr>
			<td class="td-centered">
				Контактный телефон, e-mail (при наличии)
			</td>
			<td colspan="4">
				{{ $foreigner->phone }}
			</td>
		</tr>
	</table>
	<p>
		По настоящему Договору (полису) страховым случаем признается документально подтвержденное обращение Застрахованного, в соответствии с условиями Договора (полиса) страхования в период его действия, в медицинские и иные организации, оказывающие неотложную медицинскую и иную помощь, в результате события, предусмотренного Программой добровольного медицинского страхования трудовых мигрантов (Приложение №1 к полису)., которая является неотъемлемой частью Договора (полиса) страхования. Договор (полис) страхования без Программы добровольного медицинского страхования трудовых мигрантов не действителен.
	</p>

	<table class="table table-bordered mb5">
		<tr>
			<td class="td-centered">
				<strong>Программа добровольного медицинского страхования «СИГ – Трудовой мигрант», вариант 1</strong> <br>
				Страховая сумма (агрегатная) на одного Застрахованного: <strong>100 000 (Сто тысяч) рублей 00 копеек.</strong> <div class="check-section-row">X</div> <br>
				Страховая сумма (агрегатная) на одного Застрахованного: &nbsp;&nbsp;&nbsp; (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) руб. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; копеек Вариант &nbsp;&nbsp;&nbsp; <div class="check-section-row"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="width: 520px">
					Страховая премия: 3 600, 00 (Три тысячи шестьсот) рублей 00 копеек. <br>
					<div class="text-center"><span style="font-size: 10px">Сумма прописью</span></div>
					<div class="check-section-row">@if (!$dms->dms_payment) X @endif</div> наличными деньгами
					&nbsp;&nbsp;&nbsp;
					<div class="check-section-row">@if ($dms->dms_payment) X @endif</div> безналичным путем
					&nbsp;&nbsp;&nbsp;
					№ П/П или № квитанции <strong>{{ $dms->dms_receipt }}</strong> <br>
					<strong>Дата заключения Договора (полиса): {{ date('d.m.Y', strtotime($dms->dms_contract_date)) }}</strong>
				</div>
			</td>
		</tr>
	</table>

	<p>
		Договор вступает в силу с 00 часов 00 минут дня, следующего за днем уплаты страховой премии Страховщику в полном объеме и действует <br>
		<strong>с {{ date('«d» F «Y»г.', strtotime($dms->dms_policy_date_from)) }} по {{ date('«d» F «Y»г.', strtotime($dms->dms_policy_date_to)) }}</strong>
	</p>

	<p>
		<strong>Территория Страхования: субъект РФ, на территории которого Застрахованному лицу разрешена трудовая деятельность.</strong> <br>
		<strong>Для получения медицинской помощи Застрахованному или его представителю необходимо:</strong> связаться с представителем Страховщика по телефону: <strong>8 927 710 13 14</strong> и сообщить: фамилию, имя, отчество Застрахованного, номер полиса, срок действия полиса, причину обращения за помощью, свое местонахождение, телефон, по которому с Застрахованным можно связаться.
	</p>

	<p>
		<strong>Страхователь подтверждает свое согласие на обработку, использование, передачу Страховщику, персональных данных, перечисленных в настоящем Договоре, в соответствии с Федеральным Законом от 27.07.2006г. №152-ФЗ «О персональных данных». в объеме, необходимом для заключения и исполнения настоящего Договора (полиса) страхования, включая сбор, систематизацию, накопление, хранение, уточнение, использование, распространение (в т.ч. передачу третьим лицам), блокирование, обезличивание, уничтожение персональных данных, а также сведений о состоянии здоровья, оказанных медицинских услугах, об обращениях в медицинские учреждения. Срок согласия устанавливается в соответствии с законодательством РФ.</strong>
		<strong>Страхователь:</strong> Правила ДМС трудовых мигрантов в редакции от 17. 05. 2016 г., Программу ДМС трудовых мигрантов получил, ознакомлен, согласен. Обязуюсь ознакомить Застрахованного с условиями Договора страхования и Программой ДМС трудовых мигрантов
	</p>

	<div class="pull-right">
		<div>
            <div class="underline-block"></div>
        </div>
        <div class="text-center" style="width: 200px">
        	<span style="font-size: 10px">(подпись)</span>
        </div>
	</div>

	<table>
		<tr>
			<td>
				<div style="width: 400px">
					<strong>Страховщик, в лице:</strong> <br><br>
					<div>
			            <div class="underline-block"></div> <strong>ИП Нечаева М.В</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; М.П.
			        </div>
			        <div class="text-center" style="width: 200px">
			        	<span style="font-size: 10px">(подпись)</span>
			        </div>
				</div>
			</td>

			<td>
				<div style="width: 400px">
					<strong>На основании (указать документ):</strong> <br><br>
			        <div class="text-center">
			        	Аген. дог. № 111/2016/АД
			        </div>
				</div>
			</td>
		</tr>
	</table>
</div>
</div>

<div class="document-action container">
    <div class="row">
        <div class="col-md-2 col-md-offset-4">
            <a href="/operator/foreigners/{{ $dms->foreigner_id }}/dms/{{ $dms->id }}/edit" class="btn btn-default btn-block">Изменить</a>
        </div>

        <div class="col-md-3">
            <a href="" class="btn btn-primary btn-block btn-print">Распечатать</a>
        </div>
    </div>
</div>
@endsection
