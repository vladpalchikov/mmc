@extends('layouts.print_mu')

@section('content')
<style>
    .document-preview {
        font: 12pt/18pt "Times New Roman";
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 60px;
        position: relative;
        margin-top: 100px;
        background-color: #fff;
        border: 0px;
    }
    .qr-box {
        position: absolute;
        bottom: 0px;
        padding: 0px 35px 0px 0px;
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
        font: 10pt/14pt "Times New Roman";
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

    table, td {
        font: 12pt/18pt "Times New Roman";
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
	        position: relative;
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
                @if ($group->payment_status == 1)
                    <div class="pay-label">Оплачено</div>
                @endif
            </div>
            <div class="col-md-6" style="text-align:right; line-height: 14pt; font-size: 90%; margin-bottom: 15px">
                Приложение 1<br>к Договору - Оферте возмездного<br>оказания консультационных услуг
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <h3>Заявка №{{ $group->id }}</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="col-xs-1 pl0">Заказчик</div>
                <div class="col-xs-11">
                    <div class="und">{{ $group->client->name }}</div>
                </div>
                <div class="col-xs-1 pl0">
                    &nbsp;
                </div>
                <div class="col-xs-11 field-tip">
                    (наименование организации или индивидуального предпринимателя)
                </div>
                <br>
                просит оказать следующую консультационную услугу/следующие консультационные услуги:
                <br>
                <table class="table table-bordered" style="margin-top:10px">
                    <tbody>
                        <tr>
                            <th>Наименование&nbsp;услуги</th>
                            <th>Способ&nbsp;оплаты</th>
                            <th>Количество</th>
                            <th><nobr>Цена,&nbsp;руб.</nobr></th>
                        </tr>
                        <tr>
                            <td>{{ $group->service_description }}</td>
                            <td>{{ $group->payment_method == 0 ? 'Наличными в кассу' : 'Безналичная оплата' }}</td>
                            <td>{{ $group->service_count }}</td>
                            <td class="text-right">{{ number_format($group->service_price, 0, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>Итого:</td>
                            <td></td>
                            <td>{{ $group->service_count }}</td>
                            <td class="text-right">{{ number_format($group->service_count * $group->service_price, 0, ',', ' ') }}</td>
                        </tr>
                    </tbody>
                </table>

                @if ($group->services()->where('foreigner_id', '<>', 0)->count() > 0)
                    <table class="table table-bordered" style="margin-top:10px">
                        <tbody>
                            <tr>
                                <th>ФИО Гражданина</th>
                                <th>Документ</th>
                                <th>Серия/номер</th>
                                <th>Дата рождения</th>
                                <th>Национальность</th>
                                <th>Тип обращения</th>
                            </tr>
                            @foreach ($group->services as $service)
                                @if ($service->foreigner)
                                    <tr>
                                        <td>
                                            <span class="capitalize">{{ $service->foreigner->surname }} {{ $service->foreigner->name }} {{ $service->foreigner->middle_name }}</span>
                                        </td>
                                        <td class="uppercase">
                                            {{ $service->foreigner->document_name }}
                                        </td>
                                        <td class="nbr">
                                            <span class="uppercase">{{ $service->foreigner->document_series }}</span>{{ $service->foreigner->document_number }}
                                        </td>
                                        <td>
                                            {{ date('d.m.Y', strtotime($service->foreigner->birthday)) }}
                                        </td>
                                        <td class="capitalize">{{ $service->foreigner->nationality }}</td>
                                        <td>{{ $service->getTypeAppeal() }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 small-text">
                <p>С условиями публичного договора­ оферты возмездного оказания услуги, размещенного в здании
                Многофункционального миграционного центра и опубликованного в сети Интернет по адресу http://mmc63.ru ознакомлен(­а) и согласен(­на).</p>

                <p>Заказчик согласен(­а) с условиями п. 2.4., ­2.7., 3.6. Договора оферты, а именно, что: в случае отказа
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
                Исполнителем в рамках данного Договора, считается оказанными надлежащим образом.</p>
                <p>
                В случае невозможности исполнения обязательств по оказанию Услуги, возникшей по вине Заказчика, а так же в случаях предусмотренных п. 2.4.­, 2.7. Договора оферты, денежные средства, перечисленные Заказчиком за предоставление Услуги, не возвращаются.
                </p>
                <p>Необходимый пакет документов для оказания предусмотренной настоящей Заявкой услуги Заказчиком прилагается.</p>
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid">
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6 text-center">
                <div class="und capitalize">
                    @if ($group->client->contact_person == "")
                        {{ $group->client->name }}
                    @else
                        {{ $group->client->contact_person }}
                    @endif
                </div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2 text-center">
                <div class="und">{{ date('d.m.Y', strtotime($group->created_at)) }}</div>
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
                <div class="und capitalize">{{ $group->operator->name }}</div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2 text-center">
                <div class="und">{{ date('d.m.Y', strtotime($group->created_at)) }}</div>
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
            <div class="col-md-12">
                <p>Услуга оказана в полном объеме. Претензий к оказанию Услуги не имею.</p>
            </div>
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6">
                <div class="und text-center capitalize">
                    @if ($group->client->contact_person == "")
                        {{ $group->client->name }}
                    @else
                        {{ $group->client->contact_person }}
                    @endif
                </div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2">
                <div class="und text-center">{{ date('d.m.Y', strtotime($group->created_at)) }}</div>
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
            <div class="col-md-12 small-text">
                <p>Настоящим подтверждаю своё согласие на обработку персональных данных (сбор, систематизацию,
                накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе
                передачу), обезличивание, блокирование, уничтожение персональных данных, а так же иных действий,
                необходимых для обработки персональных данных, указанных в настоящем Заявлении, в том числе в
                автоматизированном режиме, в рамках предоставления консультационных услуг в области миграции.</p>
            </div>
        </div>

        <div class="row" style="page-break-inside: avoid">
            <div class="col-xs-2">
                Заказчик:
            </div>
            <div class="col-xs-6 text-center">
                <div class="und capitalize">
                    @if ($group->client->contact_person == "")
                        {{ $group->client->name }}
                    @else
                        {{ $group->client->contact_person }}
                    @endif
                </div>
            </div>
            <div class="col-xs-2">
                <div class="und">&nbsp;</div>
            </div>
            <div class="col-xs-2">
                <div class="und text-center">{{ date('d.m.Y', strtotime($group->created_at)) }}</div>
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
    </div>
</div>
{{--
<div class="document-action container">
    <div class="row">
        <div class="col-md-6">
            <a href="/operator/clients/{{ $group->client->id }}/edit" class="btn btn-default btn-block">Исправить</a>
        </div>

        <div class="col-md-6">
            <a href="" class="btn btn-primary btn-block btn-print">Распечатать</a>
        </div>
    </div>
</div>
--}}
@endsection
