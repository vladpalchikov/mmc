@extends('layouts.print')

@section('content')
<style>
    .document-preview {
        font: 12pt/16pt "Times New Roman";
        background-color: #fff;
        height: 2090px;
        border: 0px;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 50px;
        position: relative;
        margin-top: 100px;
    }
    .qr-box {
        position: absolute;
        bottom: 0px;
        padding: 0px 35px 0px 0px;
        width: auto;
    }

    .box-div {
        border-top: 1px dashed;
        margin: 0px 0px 5px 0px;
        overflow: hidden;
        height: 5px;
    }

    .field {
        border-bottom: 1px solid;
        font-weight: bold;
    }
    .small-text p {
        font: 8pt/11pt "Times New Roman";
    }
    .und {
        border-bottom: 1px solid;
    }

    .field-tip {
        text-align: center;
        font: 10pt/14pt "Times New Roman";
        padding-top: 5px;
		margin-bottom: 10px;
    }

    .table>tbody>tr>td {
        font: 11pt/14pt "Times New Roman";
        padding: 6px 8px;
    }

    .table>tbody>tr>th {
        font: 11pt/14pt "Times New Roman";
        padding: 6px 8px;
        font-weight: bold;
    }

    .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
    }

    .pay-label {
        border: 2px solid;
        padding: 13px 20px;
        font-family: "Times New Roman";
        display: inline-block;
        margin-top: 5px;
        text-transform: uppercase;
        line-height: 1;
    }

    @media print {
        .document-preview {
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
            height: 2090px;
	        position: relative;
            margin-top: 0px;
        }

        .table>tbody>tr>td {
            font: 11pt/14pt "Times New Roman";
            padding: 6px 8px;
        }

        .table>tbody>tr>th {
            font: 11pt/14pt "Times New Roman";
            padding: 6px 8px;
            font-weight: bold;
        }

        .qr-box {
            position: absolute;
            bottom: 0px;
            padding: 0px 10px 0px 0px;
        }

        .document-action {
            display: none;
        }
    }
</style>

<div class="document-preview">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                @if ($service->payment_status == 1)
                    <div class="pay-label">Оплачено</div>
                @endif
            </div>
            <div class="col-md-6 small-text" style="text-align:right; margin-bottom: 15px">
                <p>Приложение 1<br>к Договору - Оферте возмездного<br>оказания консультационных услуг</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <h4>Заявка № {{ $service->id }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                Заказчик <span class="field capitalize">{{ $service->foreigner_surname }} {{ $service->foreigner_name }} {{ $service->foreigner_middle_name }}</span> <span class="field capitalize">{{ date('d.m.Y', strtotime($service->foreigner_birthday)) }}</span> года рождения, пол <span class="field lowercase">{{ $service->foreigner_gender ? 'Женский' : 'Мужской' }}</span>, гражданство <span class="field capitalize">{{ $service->foreigner_nationality }}@if(!empty($service->foreigner_nationality_line2)) {{ $service->foreigner_nationality_line2 }}@endif</span>, документ удостоверяющий личность <span class="field lowercase">{{ $service->foreigner_document_name }}</span> <span class="field uppercase">{{ $service->foreigner_document_series }}{{ $service->foreigner_document_number }}</span>, дата выдачи <span class="field capitalize">{{ date('d.m.Y', strtotime($service->foreigner_document_date)) }}</span>, выдан <span class="field capitaliz">{{ $service->foreigner_document_issuedby }}</span>, адрес регистрации по
                месту пребывания <span class="field capitalize">{{ trim($service->foreigner_address.' '.$service->foreigner_address_line2.' '.$service->foreigner_address_line3) }}</span>, срок
                регистрации <span class="field capitalize">{{ date('d.m.Y', strtotime($service->foreigner_registration_date)) }}</span>, контактный телефон&nbsp;<span class="field capitalize">@if (!empty($service->foreigner_phone))<nobr>{{ $service->foreigner_phone }}</nobr>@else &nbsp;&mdash;&nbsp; @endif</span>, просит оказать следующую консультационную услугу:
                <table class="table table-bordered" style="margin-top:8px;margin-bottom:8px">
                    <tbody>
                        <tr>
                            <th style="width: 80%">Наименование услуги</th>
                            <th>Способ&nbsp;оплаты</th>
                            <th>Цена,&nbsp;руб.</th>
                        </tr>
                        <tr>
                            <td>{{ $service->service_description }}</td>
                            <td>
                                <p class="nbr">{{ $service->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}<p>
                                @if (isset($service->client_id) && $service->client_id != 0)
                                    {{ $service->client->name }} ({{ $service->client->inn }})
                                @endif
                            </td>
                            <td>{{ number_format($service->service_price, 0, ',', ' ') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 small-text" style="page-break-after: avoid;">
                <p>С условиями публичного договора оферты возмездного оказания услуги, размещенного в здании Многофункционального миграционного центра и опубликованного на информационных стойках ознакомлен(а) и согласен(а).
                Заказчик согласен(­а) с условиями п. 2.4., ­2.7., 3.6. Договора оферты, а именно, что: в случае отказа
                образовательной организации, включенной в перечень утвержденный Министерством образования и науки
                РФ, в выдачи Сертификата о владении русским языком, знании истории России и основ законодательства
                Российской Федерации по причинам, установленным действующим законодательством; в случае отказа
                медицинскими организациями в выдачи медицинских свидетельств и заключений, выдаваемых по
                результатам медицинских осмотров и анализов, а так же Сертификата об отсутствии у данного
                иностранного гражданина заболевания, вызываемого вирусом иммунодефицита человека (ВИЧ­инфекции),
                по причинам, установленным действующим законодательством РФ; в случае отказа нотариуса, страховой
                организации, миграционной службы, в предоставлении услуг перечисленных в п. 1.1. настоящего договора,
                по причинам, установленным действующим законодательством; в случае отказа органом исполнительной
                власти в сфере миграции в приме заявления на выдачу патента, а равно, как и отказа в
                оформлении/переоформлении патента, по причинам, установленным Федеральным законом от 25.07.2002 N
                115­ФЗ "О правовом положении иностранных граждан в Российской Федерации", услуги, предоставленные
                Исполнителем в рамках данного Договора, считается оказанными надлежащим образом.
                В случае невозможности исполнения обязательств по оказанию Услуги, возникшей по вине Заказчика, а так же в случаях предусмотренных п. 2.4.­, 2.7. Договора оферты, денежные средства, перечисленные Заказчиком за предоставление Услуги, не возвращаются.
                </p>
            </div>
        </div>

        <div class="row" style="margin-bottom: 2px;">
            <div class="col-md-12">
                <p>Необходимый пакет документов для оказания предусмотренной настоящей Заявкой услуги Заказчиком прилагается.</p>
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid">
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6 text-center">
                <div class="und capitalize">{{ $service->foreigner_surname }} {{ $service->foreigner_name }} {{ $service->foreigner_middle_name }}</div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2 text-center">
                <div class="und">{{ date('d.m.Y', strtotime($service->updated_at)) }}</div>
            </div>
            <div class="col-xs-2">
                &nbsp;
            </div>
            <div class="col-xs-6 field-tip">
                (ФИО полностью)
            </div>
            <div class="col-xs-2 field-tip">
                (подпись)
            </div>
            <div class="col-xs-2 field-tip">
                (дата)
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid">
            <div class="col-xs-2">
                Принял:
            </div>
            <div class="col-xs-6 text-center">
                <div class="und capitalize">{{ $service->operator->name }}</div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2 text-center">
                <div class="und">{{ date('d.m.Y', strtotime($service->updated_at)) }}</div>
            </div>

            <div class="col-xs-2">
                &nbsp;
            </div>
            <div class="col-xs-6 field-tip">
                (ФИО полностью)
            </div>
            <div class="col-xs-2 field-tip">
                (подпись)
            </div>
            <div class="col-xs-2 field-tip">
                (дата)
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid; margin-top: 2px;">
			<div class="col-md-12" style="margin-bottom: 8px;">
				<p>Услуга оказана в полном объеме. Претензий к оказанию Услуги не имею.</p>
			</div>
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6">
                <div class="und text-center capitalize">{{ $service->foreigner_surname }} {{ $service->foreigner_name }} {{ $service->foreigner_middle_name }}</div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2">
                <div class="und text-center">{{ date('d.m.Y', strtotime($service->updated_at)) }}</div>
            </div>
            <div class="col-xs-2">
                &nbsp;
            </div>
            <div class="col-xs-6 field-tip">
                (ФИО полностью)
            </div>
            <div class="col-xs-2 field-tip">
                (подпись)
            </div>
            <div class="col-xs-2 field-tip">
                (дата)
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 small-text" style="margin-top: 2px;">
                @if ($service->service->company_id == 6)
                    <p>Настоящим подтверждаю ООО «СТК» (Самарская обл., Самара г., ул. Черногорская 2) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
                @if ($service->service->company_id == 5)
                    <p>Настоящим подтверждаю ИП Капишева Е.Э. (443022, Самарская обл., Самара г., Кабельная ул., дом № 13А) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
                @if ($service->service->company_id == 4)
                    <p>Настоящим подтверждаю ООО «ЦПМ» (443022, Самарская обл., Самара г., Кабельная ул., дом № 13А) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
                @if ($service->service->company_id == 3)
                    <p>Настоящим подтверждаю ИП Рыжова И.А. (443022, Самарская обл., Самара г., Кабельная ул., дом № 13А) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
                @if ($service->service->company_id == 2)
                    <p>Настоящим подтверждаю ООО «ЦПМ» (443022, Самарская обл., Самара г., Кабельная ул., дом № 13А) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
                @if ($service->service->company_id == 1)
                    <p>Настоящим подтверждаю ИП Капишева Е.Э. (443022, Самарская обл., Самара г., Кабельная ул., дом № 13А) своё согласие на обработку персональных данных (сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий, необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции. Настоящее  согласие  вступает  в силу со дня его подписания и действует до дня отзыва в письменной форме. Согласие может быть отозвано мною в любое время на основании моего письменного заявления.
                    </p>
                @endif
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid; margin-top: 2px;">
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6 text-center">
                <div class="und capitalize">{{ $service->foreigner_surname }} {{ $service->foreigner_name }} {{ $service->foreigner_middle_name }}</div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2">
                <div class="und text-center">{{ date('d.m.Y', strtotime($service->updated_at)) }}</div>
            </div>
            <div class="col-xs-2">
                &nbsp;
            </div>
            <div class="col-xs-6 field-tip">
                (ФИО полностью)
            </div>
            <div class="col-xs-2 field-tip">
                (подпись)
            </div>
            <div class="col-xs-2 field-tip">
                (дата)
            </div>
        </div>

        @if ($service->service->labor_exchange)
        <div class="row">
        <div class="col-md-12" style="page-break-before: always;">
        <h4><b>Биржа труда</b></h4>
        <p>
            Многофункциональный миграционный центр (ММЦ) оказывает услуги по содействию в поиске работы иностранным гражданам, и работодателям – в поиске сотрудников.Управление по вопросам миграции ГУ МВД России по Самарской области, расположенное на базе ММЦ — официальный орган, уполномоченный выдавать иностранным гражданам патенты.
        </p>
        <p>
            <b>Остерегайтесь мошенников, которые предлагают Вам получить патент и другие документы за пределами ММЦ.</b>
        </p>
        <p>
            Иностранный гражданин, нуждающийся в работе, может получить услугу по содействию в поиске работы в любое время в период действия патента.
            Резюме иностранного гражданина будет помещено в Базу данных ММЦ и направлено на рассмотрение работодателям г.о. Самара и Самарской области.
        </p>
        <p>
            Иностранный гражданин получит возможность ознакомиться с актуальными вакансиями работодателей г.о Самара, Самарской области и выбрать ту, которая соответствует его квалификации и опыту. А также получит юридическую помощь и консультацию.
        </p>
        <p>
            Стоимость – 2000 рублей за оказание услуги «Биржа труда» для соискателя в Базе данных ММЦ.
        </p>
        <p>
            <b>Что делать после получения патента</b>
        </p>
        <p>
            В течение 11 месяцев необходимо оплачивать налог за патент, чтобы он был действителен. Общий срок действия патента – не более 12 месяцев.
            Для продления действия патента нужно совершить авансовый платёж по налогу в размере {{ $tax->price }} рублей за каждый месяц продления. Оплатить налог на патент можно в платежных терминалах ММЦ – необходимо обратиться к любому сотруднику центра. Для оплаты налога на патент иностранный гражданин должен иметь ИНН.
        <p>
            <b>Не доверяйте оплату налога на патент третьим лицам. После уплаты налоговых платежей за патент сохраняйте все квитанции и храните их в течение года.</b>
        </p>
        </div>
        </div>
        @endif

        @if (isset($service->foreigner_oktmo) && !empty($service->foreigner_oktmo) && $service->service_id != 4 && trim(mb_strtolower($service->foreigner_document_name)) == 'паспорт' && isset($tax))
            <div class="row">
                <div class="col-md-12 qr-box">

                <table class="table table-bordered" style="width: 100%; border:3px solid;">
                    <tr>
                        <td style="padding: 5px 5px 5px 5px; min-width: 240px; border-right:3px solid !important;">
                            <img src="/operator/foreigners/{{ $service->foreigner_id }}/qrcode/{{ $tax->id }}" alt="" style="min-width: 200px">
                        </td>
                        <td style="font: 10pt/12pt Arial; padding: 13px 20px">
                            <p><strong>{{ $tax->name }}</strong></p>
                            <p class="capitalize">
                              {{ $service->foreigner_surname }} {{ $service->foreigner_name }} {{ $service->foreigner_middle_name }}
                            </p>
                            <p>
                              <span class="capitalize">{{ $service->foreigner_document_name }}</span>
                              <span class="uppercase"><strong>{{ $service->foreigner_document_series }}</span>{{ $service->foreigner_document_number }}</strong><br>
                              Регистрация до <strong>{{ date('d.m.Y', strtotime($service->foreigner_registration_date)) }}</strong>
                            </p>
                            @if ($service->foreigner_ifns_name)
                                <p>
                                    <span class="capitalize">{{ trim($service->foreigner_address.' '.$service->foreigner_address_line2.' '.$service->foreigner_address_line3) }}</span>
                                    ({{ \MMC\Models\Ifns::find($service->foreigner_ifns_name)->name }})
                                </p>
                            @endif
                            <span class="small">Сумма налога {{ $tax->price }} руб. {{ $tax->comment }}</span>
                        </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="font: 10pt/12pt Arial; padding: 13px 20px; border-top:3px solid !important; text-align: center">
                        <p><span style="font-weight: bold; text-transform: uppercase">Внимание! Перед оплатой убедитесь что указанные данные верны.</span><br>Для обновления данных обратитесь в Многофункциональный Миграционный Центр по адресам:</strong></p>
                        Самара, ул. Кабельная 13А / Самара, ул. Черногорская 2 / Тольятти, Тупиковый проезд 4, стр. 2
                        {{--
                        <u>{{ $mmc->address }}</u>
                        @foreach (\MMC\Models\MMC::all() as $item)
                            @if ($item->id != $mmc->id)
                                / {{ $item->address }}
                            @endif
                        @endforeach
                        --}}
                      </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
