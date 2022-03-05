@extends('layouts.print')

@section('content')
<style>
    .document-preview {
        font: 12pt/18pt "Times New Roman";
    }
    .document-action {
    	width: 810px;
    }
    .page {
	  width: 810px;
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
	.align-right {
	  float: right !important;
	}
	.document-head {
	  position: relative;
	  padding-top: 140px;
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
	.form-content .doc-type {
	  position: absolute;
	  top: 27px;
	  left: 63%;
	  font: 12px 'Times New Roman';
	  color: black;
	  line-height: 1.1;
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
	.form-content .line-height-fix {
	  line-height: 1.2;
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
	  width: 820px;
	  border: 1px solid black;
	  padding-top: 50px;
	  padding-left: 40px;
	  padding-right: 40px;
	  padding-bottom: 40px;
	  font: 12px 'Times New Roman';
	  color: black;
	  line-height: 1.1;
	}
	.notification-page .content-wrapper {
	  padding-left: 10px;
	  padding-right: 10px;
	}
	.notification-page .title-block p {
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
	  height: 180px;
	  margin-bottom: 60px;
	  border: 1px solid black;
	}
	.notification-page .mark-field-description {
	  position: absolute;
	  bottom: -50px;
	  font: 10px 'Times New Roman';
	  color: black;
	  line-height: 1.1;
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
	  left: 50%;
	  top: -5px;
	  text-align: center;
	  background: white;
	}
	.notification-page .dashed {
	  border-bottom: 2px dashed black;
	}
	.notification-page .signature-field {
	  height: 40px;
	  border: 1px solid black;
	}
	.notification-page .stamp-field {
	  height: 165px;
	  padding-top: 140px;
	  border: 1px solid black;
	}
	.notification-page .host-signature {
	  margin-top: 110px;
	}
	.notification-page .mark {
	  float: left;
	  width: 15px;
	  height: 15px;
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
	@media print {
	  .page {
	    width: 730px;
	    border: none;
	    padding: 0;
	    margin: 0;
	  }
	  .notification-page {
	    padding-top: 0;
	    padding-bottom: 0;
	    padding-left: 15px;
	    padding-right: 15px;
	    width: 780px;
	    border: none;
	  }
	  .wrapper {
	    height: 15px;
	  }
	}
	.print-size {
    	margin-left: -2px;
    }
    @media print {
        .document-preview {
            width: 100%;
            margin: 0;
            padding: 0;
            border: none;
        }

        .document-action {
            display: none;
        }

        .print-size {
        	margin-left: -10px !important;
        }
    }
</style>

<div class="page center-block">
    <div class="document-head text-center">
        <span class="form-header">заявление</span><br>
        <span class="form-header">о внесение изменений в сведения, содержащиеся в патенте</span><br>

        @if (Auth::user()->mmc)
        	<span class="authority-description">{{ Auth::user()->mmc->title }}</span>
        @endif

        <div class="photo-block">
            <div class="photo-title text-center">
                <span>
                    цветное фото
                </span>
            </div>
            <div class="photo-size text-center">
                <span>(30X40 мм)</span>
            </div>
        </div>
        <div class="foreigner-container text-right">
            <span>Приложение № 4</span><br>
            <span>к приказу МВД России</span><br>
            <span>от 14.08.2017 №635</span><br>
        </div>
    </div>

    <div class="form-content">
    	<div class="discription-wrapper text-center">
            <span>(наименование территориального органа МВД России)</span>
        </div>

		<div class="check-section">
			<div class="col-xs-12 without-padding">
            	<span class="check-section-label">Прошу внести изменения в сведения, содержащиеся в патенте, в связи с:</span>
			</div>
			<div class="col-xs-4 col-xs-offset-4 without-padding">
	            <span class="check-section-label mr10">(нужное отметить):</span>
	            <div class="example-block">
	                <div>
	                    <span>x</span>
	                </div>
	                <span>или</span>
	                <div>
	                    <span>v</span>
	                </div>
	            </div>
            </div>
            <div class="row">
                <div class="check-section-row col-xs-12 mb0">
                    <div style="position: relative; top: -10px">@if ($patentChange->reason == 0) X @endif</div>
                    <span>изменением фамилии, имени или отчества (при наличии)</span>
                </div>
                <div class="check-section-row col-xs-12 mb0">
                    <div style="position: relative; top: -10px">@if ($patentChange->reason == 1) X @endif</div>
                    <span>изменением реквизитов документа, удостоверяющего личность</span>
                </div>
                <div class="check-section-row col-xs-12 mb0">
                    <div style="position: relative; top: -10px">@if ($patentChange->reason == 2) X @endif</div>
                    <span>изменением профессии (специальности, должности, вида трудовой деятельности)</span>
                </div>
            </div>
        </div>

    	<div class="first-line">
          <span class="row-title">Сведения об иностранном гражданине (лице без гражданства):</span>
      </div>
    	<div class="form-section">
            <span class="row-title">Фамилия:</span>
            <ul>
				{!! FormOutput::string($foreigner->surname, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Имя:</span>
            <ul>
				{!! FormOutput::string($foreigner->name, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Отчество:</span><br>
            <span class="row-title-qualification">(при наличии)</span>
            <ul>
				{!! FormOutput::string($foreigner->middle_name, 37) !!}
            </ul>
        </div>
        <div class="form-section section-offset">
            <span class="row-title">Гражданство (подданство):</span><br>
            <span class="row-title-qualification">(или государство постоянного <br> (преимущественного) проживания)</span>
            <ul>
				{!! FormOutput::string($foreigner->nationality, 31) !!}
            </ul>
        </div>
        <div class="form-section">
            <ul>
				{!! FormOutput::string($foreigner->nationality_line2, 42) !!}
            </ul>
        </div>
        <div class="form-section section-offset">
            <span class="row-title">Место рождения:</span>
            <ul>
				{!! FormOutput::string($patent->birthday_place, 35) !!}
            </ul>
            <div class="table-qualification text-center">
                <span>(государство, населенный пункт)</span>
            </div>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">Дата рождения:</span>
	        <ul class="short-table-qualification day">
                {!! FormOutput::day($foreigner->birthday) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($foreigner->birthday) !!}
            </ul>
            <ul class="short-table-qualification year">
                {!! FormOutput::year($foreigner->birthday) !!}
            </ul>
            <span class="row-title">Пол:</span>
            <ul>
                <li>@if (!$foreigner->gender) X @endif</li>
            </ul>
            <span class="row-title">&#160;М</span>
            <ul>
                <li>@if ($foreigner->gender) X @endif</li>
            </ul>
            <span class="row-title">&#160;Ж</span>
        </div>
        <div class="form-section">
            <span class="row-title">Адрес постоянного <br> проживания:</span><br>
            <ul>
				{!! FormOutput::string($patent->registration_address, 31) !!}
            </ul>
        </div>
        <div class="form-section">
            <ul>
            	{!! FormOutput::string($patent->registration_address_line2, 42) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Документ, удостоверяющий личность:</span><br>
            <span class="row-title-qualification view">(вид)</span>
            <ul>
				{!! FormOutput::string($foreigner->document_name, 26) !!}
            </ul>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">серия</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($foreigner->document_series, 8) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($foreigner->document_number, 11) !!}
            </ul>
            <span class="row-title print-size">Дата выдачи</span>
            <ul class="short-table-qualification day">
                {!! FormOutput::day($foreigner->document_date) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($foreigner->document_date) !!}
            </ul>
            <ul class="short-table-qualification year last-item">
                {!! FormOutput::year($foreigner->document_date) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">кем выдан:</span>
            <ul>
				{!! FormOutput::string($patent->document_organization, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Контактный телефон:</span>
            <ul>
				{!! FormOutput::string($foreigner->phone, 30) !!}
            </ul>
        </div>

        <div class="first-line">
            <span class="row-title">Сведения о ранее выданном патенте</span>
        </div>
        <div class="form-section section-offset">
            <span class="row-title">Патент выдан:</span>
            <ul>
				{!! FormOutput::string($patent->prev_patent, 33) !!}
            </ul>
            <div class="table-qualification text-center">
                <span>(наименование территориального органа МВД России, выдавшего патент)</span>
            </div>
        </div>
        <div class="form-section">
            <ul>
            	{!! FormOutput::string($patent->prev_patent_line2, 42) !!}
            </ul>
        </div>
        <div class="short-form-section">
            <span class="row-title">Патент: серия</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($patent->prev_patent_series, 6) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($patent->prev_patent_number, 15) !!}
            </ul>
        </div>
        <div class="short-form-section">
            <span class="row-title">Бланк патента: серия</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($patent->prev_patent_blank_series, 6) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($patent->prev_patent_blank_number, 15) !!}
            </ul>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">Срок действия:&#160;&#160;&#160;&#160;с</span>
	        <ul class="short-table-qualification day">
                {!! FormOutput::day($patent->prev_patent_date_from) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($patent->prev_patent_date_from) !!}
            </ul>
            <ul class="short-table-qualification year">
                {!! FormOutput::year($patent->prev_patent_date_from) !!}
            </ul>
            <span class="row-title">по</span>
	        <ul class="short-table-qualification day">
                {!! FormOutput::day($patent->prev_patent_date_to) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($patent->prev_patent_date_to) !!}
            </ul>
            <ul class="short-table-qualification year last-item">
                {!! FormOutput::year($patent->prev_patent_date_to) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title profession-title">Профессия (специальность, должность, вид трудовой деятельности):</span><br>
            <ul>
				{!! FormOutput::string($patent->profession, 36) !!}
            </ul>
        </div>
        <div class="form-section">
            <ul>
            	{!! FormOutput::string($patent->profession_line2, 36) !!}
            </ul>
        </div>
        <br>

        <div class="first-line">
            <span class="row-title">Изменившиеся сведения, которые должны быть внесены в патент:</span>
        </div>

        <div class="form-section">
            <span class="row-title">Фамилия:</span>
            <ul>
				{!! FormOutput::string($patentChange->surname_change, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Имя:</span>
            <ul>
				{!! FormOutput::string($patentChange->name_change, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Отчество:</span><br>
            <span class="row-title-qualification">(при наличии)</span>
            <ul>
				{!! FormOutput::string($patentChange->middle_name_change, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">Документ, удостоверяющий личность:</span><br>
            <span class="row-title-qualification view">(вид)</span>
            <ul>
				{!! FormOutput::string($patentChange->document_name_change, 26) !!}
            </ul>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">серия</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($patentChange->document_series_change, 8) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($patentChange->document_number_change, 11) !!}
            </ul>
            <span class="row-title print-size">Дата выдачи</span>
            <ul class="short-table-qualification day">
                {!! FormOutput::day($patentChange->document_date_change) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($patentChange->document_date_change) !!}
            </ul>
            <ul class="short-table-qualification year last-item">
                {!! FormOutput::year($patentChange->document_date_change) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title">кем выдан:</span>
            <ul>
				{!! FormOutput::string($patentChange->document_organization_change, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <span class="row-title profession-title">Профессия (специальность, должность, вид трудовой деятельности):</span><br>
            <ul>
				{!! FormOutput::string($patentChange->profession_change, 37) !!}
            </ul>
        </div>
        <div class="form-section">
            <ul>
            	{!! FormOutput::string($patentChange->profession_line2_change, 37) !!}
            </ul>
        </div>
        <br>

        <div class="confirmation-block section-offset">
            <p>Мне разъяснено, что указание в заявлении неправильных (ложных) сведений может повлечь за собой отказ в выдаче патента.</p>
            <p>Подтверждаю достоверность указанных мною в заявлении сведений.</p>
            <p>С обработкой, передачей и хранением моих персональных данных, необходимых для получения патента, согласен.</p>
            <div class="underline-block signature"></div>
            <div class="underline-block document-date"></div>
            <div class="clearfix"></div>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">Дата приема документов:</span>
            <ul class="short-table-qualification day">
                <li></li><li></li>
            </ul>
            <ul class="short-table-qualification month">
                <li></li><li></li>
            </ul>
            <ul class="short-table-qualification year">
                <li></li><li></li><li></li><li></li>
            </ul>
            <span class="row-title">регистр. №:<span class="underline"></span></span>
        </div>
        <div><span>Документы принял:</span></div>
        <div class="signature-field section-offset">
            <div class="underline-block user-data"></div>
            <div class="underline-block document-signature"></div>
        </div>
        <div><span>Решение об оформлении патента принял:</span></div>
        <div class="accept">
            <div class="underline-block employee"></div>
            <div class="underline-block employee-signature"></div>
            <div class="underline-block date"></div>
        </div>

        <div class="first-line mt35">
            <span class="row-title">Сведения о выданном патенте с измененными сведениями:</span>
        </div>
        <div class="short-form-section">
            <span class="row-title">Патент: серия</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($patentChange->patent_series_change, 6) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
            	{!! FormOutput::string($patentChange->patent_number_change, 15) !!}
            </ul>
        </div>
        <div class="short-form-section">
            <span class="row-title">Бланк патента: серия</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($patentChange->blank_patent_series_change, 6) !!}
            </ul>
            <span class="row-title">№</span>
            <ul class="short-table-qualification">
				{!! FormOutput::string($patentChange->blank_patent_number_change, 15) !!}
            </ul>
        </div>
        <div class="short-form-section section-offset">
            <span class="row-title">Дата выдачи</span>
	        <ul class="short-table-qualification day">
                {!! FormOutput::day($patentChange->date_change) !!}
            </ul>
            <ul class="short-table-qualification month">
                {!! FormOutput::month($patentChange->date_change) !!}
            </ul>
            <ul class="short-table-qualification year">
                {!! FormOutput::year($patentChange->date_change) !!}
            </ul>
		</div>
    </div>
</div>

<div class="document-action container">
    <div class="row">
        <div class="col-md-2 col-md-offset-4">
            <a href="/operator/foreigners/{{ $patent->foreigner_id }}/patentchange/{{ $patentChange->id }}/edit" class="btn btn-default btn-block">Изменить</a>
        </div>

        <div class="col-md-3">
            <a href="" class="btn btn-primary btn-block btn-print">Распечатать</a>
        </div>
    </div>
</div>

@endsection
