@extends('layouts.print')

@section('content')
<style>
    .document-preview {
        font: 12pt/18pt "Times New Roman";
        height: 1000px;
        border: 0px;
        background-color: #fff;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 80px;
    }
    .qr-box {
        padding: 0px 0px 0px 0px;
        background-color: #fff;
    }

	@media print {
        .document-preview {
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
            position: relative;
            margin-top: 0;
        }

        .qr-box {
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
            <div class="col-lg-12">
            	<p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
            </div>
        </div>
    	@if (isset($foreigner->oktmo) && !empty($foreigner->oktmo) && trim(mb_strtolower($foreigner->document_name)) == 'паспорт')
            <div class="qr-box">
                <div class="box-div">&nbsp;</div>
                <table class="table table-bordered" style="border:3px solid;">
                    <tr>
                        <td style="padding: 5px 5px 5px 5px; min-width: 240px; border-right:3px solid !important;">
                            <img src="/operator/foreigners/{{ $foreigner->id }}/qrcode/{{ $tax->id }}" alt="" style="min-width: 200px">
                        </td>
                        <td style="font: 10pt/12pt Arial; padding: 13px 20px">
                            <p><strong>{{ $tax->name }}</strong></p>
                            <p class="capitalize">
                              {{ $foreigner->surname }} {{ $foreigner->name }} {{ $foreigner->middle_name }}
                            </p>
                            <p>
                              <span class="capitalize">{{ $foreigner->document_name }}</span>
                              <span class="uppercase"><strong>{{ $foreigner->document_series }}</span>{{ $foreigner->document_number }}</strong><br>
                              Регистрация до <strong>{{ date('d.m.Y', strtotime($foreigner->registration_date)) }}</strong>
                            </p>
                            @if ($foreigner->ifns_name)
                                <p>
                                    <span class="capitalize">{{ trim($foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3) }}</span>
                                    ({{ \MMC\Models\Ifns::find($foreigner->ifns_name)->name }})
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
        @else
            <div>Для оформления QR кода необходимо указать паспорт</div>
        @endif
    </div>
</div>

@endsection
