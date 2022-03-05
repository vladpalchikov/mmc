@extends('layouts.print')

@section('content')
<style>
    .document-preview {
        font: 12pt/18pt "Times New Roman";
        background-color: #fff;
        height: 1000px;
        border: 0px;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 100px;
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

    .check {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 1px solid;
      margin-right: 10px;
      position: relative;
      top: 3px;
    }

    @media print {
        .document-preview {
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
	        position: relative;
            margin-top: 0px;
        }

        .document-action {
            display: none;
        }
    }
</style>

<div class="document-preview">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">&nbsp;</div>
            <div class="col-md-5" style="text-align:right; line-height: 14pt; font-size: 90%; margin-bottom: 15px">
                {{-- Директору {{ \MMC\Models\MMC::find(Auth::user()->mmc_id)->title }} --}}
                {{-- Руководителю {{ $service->service->company->name }} --}}
                <br>
                <br>
                от <span class="field capitalize">{{ $foreigner->surname }} {{ $foreigner->name }} {{ $foreigner->middle_name }}</span><br>
                паспортные данные <span class="field uppercase">{{ $foreigner->document_series }}{{ $foreigner->document_number }}</span><br>
                проживающего по адресу<br><span class="field capitalize">{{ trim($foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3) }}</span><br>
                @if (!empty($foreigner->phone))
                Тел. <span class="field capitalize"><nobr>{{ $foreigner->phone }}</nobr></span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align:center">
                <h3>Заявление</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                Прошу Вас вернуть денежные средства в размере <span class="field capitalize">{{ number_format((float) $qr->sum_from, 2, ',', ' ') }}</span> рублей {{--(<span class="field">{{ trim(Helper::num2str((float) $qr->sum_from)) }}</span>) --}} за платеж проведенный через терминал от <span class="field nbr">{{ Helper::ru_date('%e %b %Y', strtotime($qr->created_at)) }} г.</span><br><br>
                <div class="und">&nbsp;</div>
                <div style="text-align:center">(причина возврата)</div>
                <br>
                <p>Приложение:</p>
                <span class="check">&nbsp;</span>Кассовый чек @if ($qr->receipt_id) №{{ $qr->receipt_id }}@endif<br>
                <span class="check">&nbsp;</span>Заявка (оригинал) <br>
                <span class="check">&nbsp;</span>Копия паспорта <br>
                <span class="check">&nbsp;</span>Регистрация <br>
                <span class="check">&nbsp;</span>Копия перевода паспорта  <br>
                <br>
                <div class="col-xs-3 pl0">
                    <div class="und">&nbsp;</div>
                </div>
                подпись
                <br>
                <br>
                {{ Helper::ru_date('%e %b %Y') }} г. <br>

                <div class="pull-left mt5">Виза руководителя ком. отдела</div>
                <div class="col-xs-8 pl0">
                    <div class="und">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
