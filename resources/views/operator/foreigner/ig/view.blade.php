@extends('layouts.print')

@section('content')
<style>
    .document-preview {
        font: 12pt/18pt "Times New Roman";

    }

    .document-action {
    	width: 850px;
    }

	.page {
		width: 800px;
		border: 1px solid black;
		padding-top: 80px;
		padding-left: 70px;
		padding-right: 40px;
		padding-bottom: 40px;
        background-color: #fff;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 100px;
	}
	.photo-block {
		position: absolute;
		top: 20px;
		width: 100px;
		height: 120px;
		padding-top: 20px;
		border: 1px solid black;
	}
	.photo-block .photo-title {
		font: 16px 'Times New Roman';
		color: black;
		line-height: 1.1;
		text-transform: uppercase;
	}
	.photo-block .photo-size {
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
		margin-top: 30px;
	}
	.document-head {
		position: relative;
		padding-top: 90px;
		border-bottom: 1px solid black;
	}
	.document-head .authority-description {
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.document-head .form-header {
		font: 16px 'Times New Roman';
		color: black;
		line-height: 1.1;
		font-weight: 600;
		text-transform: uppercase;
		line-height: 0.625pt;
	}
	.foreigner-container {
		position: absolute;
		top: 0;
		right: 0;
	}
	.foreigner-container span {
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.foreigner-container .underline:last-child {
		width: 50px;
	}
	.underline {
		display: inline-block;
		width: 100px;
		border-bottom: 1px solid black;
	}
	.form-content .description-wrapper span {
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content div span {
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content div ul {
		position: absolute;
		right: 0;
		top: 0;
		padding-left: 0;
		margin-bottom: 0;
	}
	.form-content .short-form-section {
		height: 30px;
	}
	.form-content .short-form-section .row-title {
		float: left;
		margin-right: 15px;
		margin-top: 5px;
	}
	.form-content .short-form-section ul {
		position: static;
		float: left;
	}
	.form-content .short-form-section .underline {
		width: 200px;
		padding-top: 20px;
	}
	.form-content div ul li {
		display: inline-block;
		width: 16px;
		height: 26px;
		padding-top: 2px;
		text-align: center;
		text-transform: uppercase;
		vertical-align: top;
		border-right: 1px solid black;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
	}
	.form-content div ul li:first-child {
		border-left: 1px solid black;
	}
	.form-content .first-line {
		margin-bottom: 15px;
	}
	.form-content .form-section {
		position: relative;
		margin-bottom: 5px;
		height: 30px;
	}
	.form-content .section-offset {
		margin-bottom: 13px;
	}
	.form-content .section-top-offset {
		margin-top: 30px;
	}
	.form-content .textfield {
		position: absolute;
		right: 0;
		top: 0;
		padding-top: 3px;
		padding-left: 3px;
		width: 592px;
		height: 28px;
		letter-spacing: 6.3px;
	}
	.form-content .row-title {
		vertical-align: top;
		line-height: 1.1;
	}
	.form-content .row-title-qualification {
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
		position: absolute;
		top: 15px;
	}
	.form-content .view {
		left: 100px;
	}
	.form-content .table-qualification {
		padding-top: 4px;
		clear: both;
	}
	.form-content .table-qualification span {
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content .short-table-qualification {
		position: relative !important;
		margin-right: 25px;
		height: 30px;
	}
	.form-content .short-table-qualification:before {
		position: absolute;
		bottom: -7px;
		width: 10px;
		height: 10px;
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content .day:before {
		content: "(число)";
		left: -3px;
	}
	.form-content .month:before {
		content: "(месяц)";
		left: -3px;
	}
	.form-content .year:before {
		content: "(год)";
		left: 19px;
	}
	.form-content .last-item {
		margin-right: 0;
	}
	.form-content .example-block {
		display: inline-block;
	}
	.form-content .example-block div {
		text-align: center;
		width: 16px;
		height: 26px;
		display: inline-block;
		border: 1px solid black;
	}
	.form-content .check-section-row {
		margin-bottom: 5px;
	}
	.form-content .check-section-row div {
		float: left;
		width: 16px;
		height: 26px;
		margin-right: 20px;
		margin-top: 8px;
		text-align: center;
		border: 1px solid black;
	}
	.form-content .check-section-row span {
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content .check-section-label {
		margin-right: 55px;
	}
	.form-content .check-section-label:nth-child(2) {
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.form-content .confirmation-block {
		padding-bottom: 20px;
	}
	.form-content .confirmation-block p {
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
		text-indent: 40px;
	}
	.form-content .underline-block {
		display: inline-block;
		position: relative;
		text-align: center;
		width: 200px;
		margin-top: 10px;
		border-bottom: 1px solid black;
	}
	.form-content .underline-block:before {
		position: absolute;
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
		top: 4px;
	}
	.form-content .signature:before {
		content: "(подпись заявителя)";
		left: 50px;
	}
	.form-content .signature {
		float: left;
	}
	.form-content .document-date,
	.form-content .document-signature {
		float: right;
	}
	.form-content .document-date:before {
		content: "(дата)";
		left: 90px;
	}
	.form-content .clearfix {
		clear: both;
	}
	.form-content .signature-field {
		height: 35px;
	}
	.form-content .signature-field .document-signature:before {
		content: "(подпись)";
		left: 80px;
	}
	.form-content .signature-field .user-data {
		width: 430px;
	}
	.form-content .signature-field .user-data:before {
		content: "(должность, фамилия, имя, отчество должностного лица, принявшего документы)";
		left: 0;
	}
	.form-content .accept .underline-block {
		width: 225px;
	}
	.form-content .accept .employee {
		width: 300px;
	}
	.form-content .accept .employee:before {
		content: "(должность, фамилия, имя, отчество должностного лица, принявшего решение об оформлении патента)";
		left: 0;
	}
	.form-content .accept .employee-signature {
		width: 150px;
	}
	.form-content .accept .employee-signature:before {
		content: "(подпись)";
		left: 50px;
	}
	.form-content .accept .date:before {
		content: "(дата)";
	}
	.print-fix {
		padding-top: 10px;
		padding-bottom: 5px;
		box-sizing: content-box;
	}
	.notification-page {
		width: 830px;
		border: 0px solid black;
		padding-top: 50px;
		padding-left: 40px;
		padding-right: 40px;
		padding-bottom: 40px;
		font: 12px 'Times New Roman';
		color: black;
		line-height: 1.1;
        background-color: #fff;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        margin-top: 100px;
	}
	.notification-page .content-wrapper {
		padding-left: 10px;
		padding-right: 10px;
	}
	.notification-page .title-block p {
		font-size: 16px;
		font-weight: 600;
		text-transform: uppercase;
	}
	.notification-page .title-block p span {
		text-decoration: underline;
	}
	.notification-page .text-row {
		display: inline-block;
		margin-bottom: 0;
		padding-left: 0;
		vertical-align: top;
	}
	.notification-page .last-row {
		padding-left: 25px;
	}
	.notification-page .text-row li {
		display: inline-block;
		width: 18px;
		height: 22px;
		padding-top: 3px;
		font: 14px 'Times New Roman';
		color: black;
		line-height: 1.1;
		text-align: center;
		text-transform: uppercase;
		vertical-align: top;
		border-right: 1px solid black;
		border-top: 1px solid black;
		border-bottom: 1px solid black;
	}
	.notification-page .text-row li:first-child {
		border-left: 1px solid black;
	}
	.notification-page .top-offset {
		margin-top: 5px;
	}
	.notification-page .form-row {
		height: 22px;
		vertical-align: top;
	}
	.notification-page .form-row span {
		float: left;
	}
	.notification-page .form-row .label-center {
		line-height: 22px;
	}
	.notification-page .main-title {
		padding-right: 5px;
	}
	.notification-page .short-form-row {
		display: inline-block;
	}
	.notification-page .short-form-row .text-row {
		margin-right: 5px;
		margin-left: 5px;
	}
	.notification-page .mark-field {
		position: relative;
		height: 145px;
		margin-bottom: 55px;
		margin-top: 30px;
		border: 1px solid black;

	}
	.notification-page .mark-field.bottom {
		height: 155px;
		margin-top: 0px;
	}
	.notification-page .mark-field-description {
		position: absolute;
		bottom: -50px;
		font: 10px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.notification-page .mark-field-description.bottom {
		bottom: -35px;
	}
	.notification-page .line {
		display: inline-block;
		width: 43%;
		border-bottom: 2px solid black;
	}
	.notification-page .cut-line-text {
		display: inline-block;
		width: 14%;
		height: 16px;
		top: 4px;
		text-align: center;
		background: white;
		position: relative;
		float: left;
	}
	.notification-page .dashed {
		border-bottom: 2px dashed black;
	}
	.notification-page .signature-field {
		height: 60px;
		margin-top: -20px;
		border: 1px solid black;
	}
	.notification-page .stamp-field {
		height: 155px;
		padding-top: 140px;
		border: 1px solid black;
	}
	.notification-page .host-signature {
		margin-top: 95px;
	}
	.notification-page .mark {
		float: left;
		width: 20px;
		height: 20px;
	}
	.notification-page .middle-mark {
		margin-right: 100px;
	}
	.notification-page .align-right {
		float: right;
	}
	.notification-page .back-side {
		height: 30px;
	}
	.notification-page .back-side div {
		position: relative;
		height: 13px;
	}
	.notification-page .back-side span {
		position: absolute;
		top: 0;
		right: 40px;
		font: 10px 'Times New Roman';
		color: black;
		line-height: 1.1;
	}
	.top-right-title {
	    margin-top: -40px;
	    margin-right: -40px;
	    margin-bottom: 10px;
	}
	.personal-document-title {
		margin-top: 5px;
	}
	.residence-type {
		margin-right: 30px;
	}
  .line {
    width: 100%;
    float: left;
    border-bottom: 1px solid #999;
    margin-top: 10px;
    margin-bottom: 10px;
  }
	@media print {
		.page {
			width: 690px;
			border: none;
			padding: 0;
			margin: 0;
		}
		.notification-page {
			padding-top: 0 !important;
			padding-bottom: 0;
			padding-left: 15px;
			padding-right: 15px;
			width: 780px;
			border: none;
      margin-top: 20px !important;;
		}
		.wrapper {
			height: 15px;
		}

.document-preview {
		width: 100%;
		margin: 0;
		padding: 0;
		border: none;
    }

    .document-action {
		display: none;
    }

    .right-print-offset {
    	font-size: 11px !important;
    }
}

</style>

<div class="notification-page center-block">
    <div class="content-wrapper">
    	<div class="title-block text-right top-right-title">
    		Приложение № 3 к Приказу МВД России от 23.11.2017 № 881
    	</div>
        <div class="title-block text-center">
            <svg class="mark">
                <rect x="0" y="0" width="15" height="15" fill="black"/>
            </svg>
            <svg class="mark align-right">
                <rect x="0" y="0" width="15" height="15" fill="black"/>
            </svg>

            <p>уведомление о прибытии иностранного гражданина <br> или лица без гражданства в место пребывания <span>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</span></p>
        </div>
        <div class="rules-block">
            <span>Пожалуйста, заполняйте форму на русском языке, ручкой с <b>черными</b> или <b>темно-синими</b> чернилами, разборчиво,<br> <b>заглавными печатными буквами</b> и цифрами по следующим образцам:</span>
            <div>
                <ul class="text-row">
                    <li>а</li><li>б</li><li>в</li><li>г</li><li>д</li><li>е</li><li>х</li><li>з</li><li>и</li><li>й</li><li>к</li><li>л</li><li>м</li><li>н</li><li>о</li><li>п</li><li>р</li><li>с</li><li>т</li><li>у</li><li>ф</li><li>х</li><li>ц</li><li>ч</li><li>ш</li><li>щ</li><li>ъ</li><li>ь</li><li>э</li><li>ю</li><li>я</li>
                </ul>
                <ul class="text-row last-row">
                    <li>i</li><li>x</li><li>v</li>
                </ul>
            </div>
            <div class="top-offset">
                <ul class="text-row">
                    <li>a</li><li>b</li><li>c</li><li>d</li><li>e</li><li>f</li><li>g</li><li>h</li><li>i</li><li>j</li><li>k</li><li>l</li><li>m</li><li>n</li><li>o</li><li>p</li><li>q</li><li>r</li><li>s</li><li>t</li><li>u</li><li>v</li><li>w</li><li>x</li><li>y</li><li>z</li>
                </ul>
                <ul class="text-row last-row">
                    <li>0</li><li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li><li>8</li><li>9</li>
                </ul>
            </div>
        </div>
        <div class="person-block top-offset">
            <div class="text-center">
                <span class="text-uppercase"><b>1. сведения о лице, подлежащем постановке на учет в место пребывания</b></span>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Фамилия</span>
                <div class="text-row align-right">
					{!! FormOutput::string($foreigner->surname, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Имя,<br> Отчество</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($foreigner->name.' '.$foreigner->middle_name, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="right-print-offset">Гражданство,<br> подданство</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($foreigner->nationality, 36) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center">Дата рождения:</span>
                <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center main-title">Пол:</span>
                    <span class="label-center">Мужской</span>
                    <div class="text-row">
                        <li>@if (!$foreigner->gender) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Женский</span>
                    <div class="text-row">
                        <li>@if ($foreigner->gender) X @endif</li>
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Место рождения:<br> государство</span>
                <div class="text-row align-right">
					{!! FormOutput::string($ig->place_birthday_country, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Город или другой<br> населенный пункт</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->place_birthday_city, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title personal-document-title">Документ, удостоверяющий личность:</span>
                <div class="short-form-row">
                    <span class="label-center">Вид</span>
                    <div class="text-row">
                    	{!! FormOutput::string($foreigner->document_name, 11) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Серия</span>
                    <div class="text-row">
                    	{!! FormOutput::string($foreigner->document_series, 4) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">№</span>
                    <div class="text-row align-right mr0">
                    	{!! FormOutput::string($foreigner->document_number, 9) !!}
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title">Дата<br> выдачи:</span>
	            <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($foreigner->document_date) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($foreigner->document_date) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($foreigner->document_date) !!}
                    </div>
                </div>

                <div class="short-form-row">
                	<span class="label-center main-title">Срок действия:</span>
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($foreigner->document_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($foreigner->document_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($foreigner->document_date_to) !!}
                    </div>
                </div>
            </div>
            <div class="top-offset">
            <span>Вид и реквизиты документа, подтверждающего право на пребывание (проживание) в Российской Федерации</span></div>
            <div class="form-row top-offset">
                <div class="short-form-row residence-type">
                    <span class="label-center">Виза</span>
                    <div class="text-row">
                        <li>@if ($ig->residence_type == 1) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row residence-type">
                    <span class="label-center">Вид на жительство</span>
                    <div class="text-row">
                        <li>@if ($ig->residence_type == 2) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row residence-type">
                    <span>Разрешение на<br> временное проживание</span>
                    <div class="text-row">
                        <li>@if ($ig->residence_type == 3) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Серия</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->residence_series, 4) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">№</span>
                    <div class="text-row mr0">
                    	{!! FormOutput::string($ig->residence_number, 9) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-row top-offset">
			            <div class="short-form-row">
		                    <span class="label-center">число</span>
		                    <div class="text-row">
		                        {!! FormOutput::day($ig->residence_date_from) !!}
		                    </div>
		                </div>
		                <div class="short-form-row">
		                    <span class="label-center">месяц</span>
		                    <div class="text-row">
		                        {!! FormOutput::month($ig->residence_date_from) !!}
		                    </div>
		                </div>
		                <div class="short-form-row">
		                    <span class="label-center">год</span>
		                    <div class="text-row">
		                    	{!! FormOutput::year($ig->residence_date_from) !!}
		                    </div>
		                </div>

                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-row top-offset">
			            <div class="short-form-row">
		                    <span class="label-center">число</span>
		                    <div class="text-row">
		                        {!! FormOutput::day($ig->residence_date_to) !!}
		                    </div>
		                </div>
		                <div class="short-form-row">
		                    <span class="label-center">месяц</span>
		                    <div class="text-row">
		                        {!! FormOutput::month($ig->residence_date_to) !!}
		                    </div>
		                </div>
		                <div class="short-form-row">
		                    <span class="label-center">год</span>
		                    <div class="text-row">
		                    	{!! FormOutput::year($ig->residence_date_to) !!}
		                    </div>
		                </div>
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title">Цель<br> въезда:</span>
                <div class="short-form-row">
                    <span class="label-center">Служебная</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 0) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Туризм</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 1) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Деловая</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 2) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Учеба</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 3) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Работа</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 4) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Частная</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 5) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Транзит</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 6) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Гуманитарная</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 7) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Другая</span>
                    <div class="text-row">
                        <li>@if ($ig->entry_purpose == 8) X @endif</li>
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <div class="short-form-row">
                    <span class="label-center">Профессия</span>
                    <div class="text-row">
						{!! FormOutput::string($ig->profession, 25) !!}
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center mr25">Дата въезда:</span>
	            <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->enter_date_from) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->enter_date_from) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($ig->enter_date_from) !!}
                    </div>
                </div>
	            <div class="short-form-row">
	            	<span class="label-center main-title ml10">Срок пребывания до:</span>
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->enter_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->enter_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">год</span>
                    <div class="text-row mr0">
                    	{!! FormOutput::year($ig->enter_date_to) !!}
                    </div>
                </div>
            </div>
            <div class="row top-offset">
                <div class="col-xs-8">
                    <div class="form-row top-offset">
                        <span class="main-title label-center">Миграционная карта:</span>
                        <div class="short-form-row">
                            <span class="label-center">Серия</span>
                            <div class="text-row">
                            	{!! FormOutput::string($ig->migration_card_series, 4) !!}
                            </div>
                        </div>
                        <div class="short-form-row">
                            <span class="label-center">№</span>
                            <div class="text-row">
                            	{!! FormOutput::string($ig->migration_card_number, 7) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <span>Сведения<br> о законных<br> представителях</span>
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->representatives, 21) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->representatives_line2, 21) !!}
                        </div>
                    </div>
                    <br>
                    <div class="form-row top-offset">
                    	<span>Адрес прежнего <br> места <br> пребывания в <br> Российской <br> Федерации</span>
                        <div class="text-row align-right">
							{!! FormOutput::string($ig->prev_address_line1, 21) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <div class="text-row align-right">
							{!! FormOutput::string($ig->prev_address_line2, 21) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <div class="text-row align-right">
							{!! FormOutput::string($ig->prev_address_line3, 21) !!}
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="mark-field top-offset">
                        <div class="mark-field-description text-center"><span>Отметка о подтверждении выполнения принимающей стороной и иностранным гражданином действий, необходимых для его постановки на учет по месту пребывания</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cut-line"><div class="line"></div><div class="cut-line-text">Линия отрыва</div><div class="line"></div></div>
    <br>
    <br>
    <div class="content-wrapper">
        <div class="person-block top-offset">
            <div><span>Настоящим подтверждается, что</span></div>
            <div class="form-row top-offset">
                <span class="label-center">Фамилия</span>
                <div class="text-row align-right">
					{!! FormOutput::string($foreigner->surname, 36) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Имя,<br> Отчество</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($foreigner->name.' '.$foreigner->middle_name, 36) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Гражданство,<br> подданство</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($foreigner->nationality, 36) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center ьд">Дата рождения:</span>
                <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($foreigner->birthday) !!}
                    </div>
                </div>
                <div class="short-form-row ml45">
                    <span class="label-center main-title">Пол:</span>
                    <span class="label-center">Мужской</span>
                    <div class="text-row">
                        <li>@if (!$foreigner->gender) X @endif</li>
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Женский</span>
                    <div class="text-row">
                        <li>@if ($foreigner->gender) X @endif</li>
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center">Документ, удостоверяющий личность:</span>
                <div class="short-form-row">
                    <span class="label-center">Вид</span>
                    <div class="text-row">
                    	{!! FormOutput::string($foreigner->document_name, 11) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Серия</span>
                    <div class="text-row">
                    	{!! FormOutput::string($foreigner->document_series, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">№</span>
                    <div class="text-row mr0">
                    	{!! FormOutput::string($foreigner->document_number, 9) !!}
                    </div>
                </div>
            </div>
            <div class="top-offset"><span>в установленом порядке уведомил о прибытии в место пребывания по адресу:</span></div>
            <div class="form-row top-offset">
                <span>Область, край<br> республика, АО</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->area, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Район</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->region, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Город или другой<br> населенный пункт</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->city, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Улица</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->street, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <div class="short-form-row">
                    <div class="text-row ml0"><li style="width: 118px;">&nbsp;</li></div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Дом</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->house, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Корпус</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->housing, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Строение</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->building, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <div class="text-row ml0"><li style="width: 143px;">&nbsp;</li></div>
                </div>
                <div class="short-form-row">
                    <!-- <span class="label-center">Квартира</span> -->
                    <div class="text-row mr0 ml0">
                    	{!! FormOutput::string($ig->flat, 5) !!}
                    </div>
                </div>
            </div>
            <div class="top-offset" style="white-space: nowrap">
              <span style="display: inline-block; font-size: 11px;">Дом, участок, владение и т.д.<br>(заполнить согласно документу, подтверждающему право владения)</span>
              <span style="display: inline-block; text-align: center; font-size: 11px; margin-left: 62px;">Квартира, комната, офис и т.д.<br>(заполнить согласно документу, подтверждающему право собственности)</span>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center">Срок пребывания до:</span>
                <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->enter_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->enter_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($ig->enter_date_to) !!}
                    </div>
                </div>
            </div>
            <div class="text-uppercase top-offset">
                <svg class="mark">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <svg class="mark align-right">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <svg class="mark align-right middle-mark">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <span class="last-row"><b>отрывная часть бланка уведомления</span> <br> <span class="last-row">о прибытии иностранного гражданина в место пребывания</b></span>
            </div>
        </div>
        <div class="back-side top-offset">
            <div>
                <span>Оборотная сторона</span>
            </div>
            <svg class="mark">
                <rect x="0" y="0" width="20" height="20" fill="black"/>
            </svg>
            <svg class="mark align-right">
                <rect x="0" y="0" width="20" height="20" fill="black"/>
            </svg>
        </div>
        <div class="rules-block top-offset">
            <span>Пожалуйста, заполняйте форму на русском языке, ручкой с <b>черными</b> или <b>темно-синими</b> чернилами, разборчиво,<br> <b>заглавными печатными буквами</b> и цифрами по следующим образцам:</span>
            <div>
                <ul class="text-row">
                    <li>а</li><li>б</li><li>в</li><li>г</li><li>д</li><li>е</li><li>х</li><li>з</li><li>и</li><li>й</li><li>к</li><li>л</li><li>м</li><li>н</li><li>о</li><li>п</li><li>р</li><li>с</li><li>т</li><li>у</li><li>ф</li><li>х</li><li>ц</li><li>ч</li><li>ш</li><li>щ</li><li>ъ</li><li>ь</li><li>э</li><li>ю</li><li>я</li>
                </ul>
                <ul class="text-row last-row">
                    <li>i</li><li>x</li><li>v</li>
                </ul>
            </div>
            <div class="top-offset">
                <ul class="text-row">
                    <li>a</li><li>b</li><li>c</li><li>d</li><li>e</li><li>f</li><li>g</li><li>h</li><li>i</li><li>j</li><li>k</li><li>l</li><li>m</li><li>n</li><li>o</li><li>p</li><li>q</li><li>r</li><li>s</li><li>t</li><li>u</li><li>v</li><li>w</li><li>x</li><li>y</li><li>z</li>
                </ul>
                <ul class="text-row last-row">
                    <li>0</li><li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li><li>8</li><li>9</li>
                </ul>
            </div>
        </div>
        <div class="person-block top-offset">
            <div class="text-center">
                <span class="text-uppercase"><b>2. сведения о месте пребывания</b></span>
            </div>
            <div class="form-row top-offset">
                <span>Область, край<br> республика, АО</span>
                <div class="text-row align-right">
					{!! FormOutput::string($ig->place_area, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Район</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->place_region, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Город или другой<br> населенный пункт</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->place_city, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Улица</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->place_street, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <div class="short-form-row">
                    <div class="text-row ml0"><li style="width: 118px;">&nbsp;</li></div>
                </div>
                <div class="short-form-row">
                    <span class="label-center mr10">Дом</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->place_house, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Корпус</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->place_housing, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Строение</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->place_building, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <div class="text-row ml0"><li style="width: 133px;">&nbsp;</li></div>
                </div>
                <div class="short-form-row">
                    <div class="text-row mr0 ml0">
                    	{!! FormOutput::string($ig->place_flat, 5) !!}
                    </div>
                </div>
            </div>
            <div class="top-offset" style="white-space: nowrap">
              <span style="display: inline-block; font-size: 11px;">Дом, участок, владение и т.д.<br>(заполнить согласно документу, подтверждающему право владения)</span>
              <span style="display: inline-block; text-align: center; font-size: 11px; margin-left: 62px;">Квартира, комната, офис и т.д.<br>(заполнить согласно документу, подтверждающему право собственности)</span>
            </div>
            <div class="form-row top-offset">
              <div class="short-form-row">
                  <span class="label-center">Тел.</span>
                  <div class="text-row mr0">
                  	{!! FormOutput::string($ig->place_phone, 10) !!}
                  </div>
              </div>
            </div>
            <div class="text-center top-offset">
                <span class="text-uppercase"><b>3. сведения о принимающей стороне</b></span>
            </div>
            <div class="form-row top-offset">
                <span class="label-center last-row">Для принимающей стороны - организации заполняется ответственным лицом</span>
                <div class="short-form-row align-right">
                    <span class="label-center">Физ. лицо</span>
                    <div class="text-row">
                        <li></li>
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">Организация</span>
                    <div class="text-row">
                        <li>X</li>
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center main-title mr10">Фамилия</span>
                <div class="text-row">
                	{!! FormOutput::string($ig->receiving_surname, 16) !!}
                </div>
                <div class="short-form-row last-row align-right">
                	<div class="short-form-row align-right">
	                    <span class="label-center">год</span>
	                    <div class="text-row" style="margin-right: 0px;">
	                    	{!! FormOutput::year($ig->receiving_birthday) !!}
	                    </div>
	                </div>
	                <div class="short-form-row align-right">
	                    <span class="label-center">месяц</span>
	                    <div class="text-row">
	                        {!! FormOutput::month($ig->receiving_birthday) !!}
	                    </div>
	                </div>
	                <div class="short-form-row align-right">
	                    <span class="label-center">число</span>
	                    <div class="text-row">
	                        {!! FormOutput::day($ig->receiving_birthday) !!}
	                    </div>
	                </div>
	                <span class="main-title align-right">Дата<br> рождения:</span>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="right-print-offset">Имя,<br> Отчество</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_name.' '.$ig->receiving_middle_name, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center">Документ, удостоверяющий личность:</span>
                <div class="short-form-row">
                    <span class="label-center">Вид</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_document_name, 11) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Серия</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_document_series, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">№</span>
                    <div class="text-row mr0">
                    	{!! FormOutput::string($ig->receiving_document_number, 9) !!}
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="main-title label-center">Дата выдачи:</span>
                <div class="short-form-row">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->receiving_document_date_from) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->receiving_document_date_from) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($ig->receiving_document_date_from) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center main-title">Срок действия:</span>
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->receiving_document_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->receiving_document_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">год</span>
                    <div class="text-row">
                    	{!! FormOutput::year($ig->receiving_document_date_to) !!}
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Область, край<br> республика, АО</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_area, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Район</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_region, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Город или другой<br> населенный пункт</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_city, 35) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Улица</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_street, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <div class="short-form-row">
                    <span class="label-center mr10">Дом</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_house, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center mr10">Корпус</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_housing, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Строение</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_building, 4) !!}
                    </div>
                </div>
                <div class="short-form-row">
                    <span class="label-center">Квартира</span>
                    <div class="text-row">
                    	{!! FormOutput::string($ig->receiving_flat, 4) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">Тел.</span>
                    <div class="text-row mr0">
                    	{!! FormOutput::string($ig->receiving_phone, 10) !!}
                    </div>
                </div>
            </div>
            <div class="row top-offset">
                <div class="col-xs-9">
                    <div class="form-row top-offset">
                        <span class="label-center">Наименование</span>
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->receiving_org_name, 24) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->receiving_org_name_line2, 28) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <span class="label-center">Факт. адрес</span>
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->receiving_org_address, 24) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <div class="text-row align-right">
                        	{!! FormOutput::string($ig->receiving_org_address_line2, 28) !!}
                        </div>
                    </div>
                    <div class="form-row top-offset">
                        <span class="label-center main-title mr10">ИНН</span>
                        <div class="text-row">
                        	{!! FormOutput::string($ig->receiving_org_inn, 12) !!}
                        </div>
                    </div>
                    <div class="row top-offset">
                        <div class="col-xs-9">
                            <span class="text-uppercase"><b>достоверность представленных сведений,<br> а также согласие на временное нахождение<br> у меня подтверждаю (подпись):</b></span>
                        </div>
                        <div class="col-xs-3">
                            <div class="signature-field"></div>
                            <div class="text-center"><span>Подпись</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 align-right">
                    <div class="stamp-field "></div>
                    <div class="text-center">
                    	<span>Печать организации</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cut-line top-offset"><div class="dashed line"></div><div class="cut-line-text">Линия отрыва</div><div class="dashed line"></div></div>

    <div class="content-wrapper">
        <div class="person-block">
            <div class="top-offset"><span>Для принимающей стороны</span><span class="align-right">Дата убытия иностранного гражданина</span></div>
            <div class="form-row top-offset">
            	<div class="short-form-row align-right">
                    <span class="label-center">год</span>
                    <div class="text-row" style="margin-right: 0px;">
                    	{!! FormOutput::year($ig->residence_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">месяц</span>
                    <div class="text-row">
                        {!! FormOutput::month($ig->residence_date_to) !!}
                    </div>
                </div>
                <div class="short-form-row align-right">
                    <span class="label-center">число</span>
                    <div class="text-row">
                        {!! FormOutput::day($ig->residence_date_to) !!}
                    </div>
                </div>
            </div>
            <div class="form-row top-offset">
                <span class="label-center">Фамилия</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_surname, 37) !!}
                </div>
            </div>
            <div class="form-row top-offset">
                <span>Имя,<br> Отчество</span>
                <div class="text-row align-right">
                	{!! FormOutput::string($ig->receiving_name.' '.$ig->receiving_middle_name, 37) !!}
                </div>
            </div>
            <div class="row top-offset">
                <div class="col-xs-3 text-center">
                    <div class="signature-field host-signature"></div>
                    <span style="font-size: 11px;">Подпись принимающей стороны</span>
                </div>
                <div class="col-xs-3">
                    <div class="stamp-field text-center"></div>
                    <div class="text-center">
                    	<span>Печать организации</span>
                    </div>
                </div>
                <div class="col-xs-5 align-right">
                    <div class="mark-field bottom">
                        <div class="mark-field-description bottom text-center"><span>Отметка о подтверждении выполнения принимающей стороной и иностранным гражданином действий, необходимых для его постановки на учет по месту пребывания</span></div>
                    </div>
                </div>
            </div>
            <div class="text-uppercase top-offset">
                <svg class="mark">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <svg class="mark align-right">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <svg class="mark align-right middle-mark">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                </svg>
                <span class="last-row"><b>отрывная часть бланка уведомления</span> <br> <span class="last-row">о прибытии иностранного гражданина в место пребывания</b></span>
            </div>
        </div>
    </div>
</div>

<div class="document-action container">
    <div class="row">
        <div class="col-md-2 col-md-offset-4">
            <a href="/operator/foreigners/{{ $ig->foreigner_id }}/ig/{{ $ig->id }}/edit" class="btn btn-default btn-block">Изменить</a>
        </div>

        <div class="col-md-3">
            <a href="" class="btn btn-primary btn-block btn-print">Распечатать</a>
        </div>
    </div>
</div>
@endsection
