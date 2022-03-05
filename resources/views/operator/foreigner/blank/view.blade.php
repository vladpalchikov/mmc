@extends('layouts.print')

@section('content')

<style>
  .document-preview {
        font: 10pt/12pt "Times New Roman";
        margin-top: 100px;
        border: 0px;
        padding: 0px;
        box-shadow: 1px 1px 10px 5px rgba(44, 62, 80,0.2);
        position: relative;
        height: auto;
        background-color: #FFFFFF;
    }

  .blank_watermark {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 15%;
    overflow: hidden;
    background: url(/img/watermark.png);
    background-repeat: repeat-y;
    background-size: cover;
  }

  .blank_watermark img {
    height: 100% !important;
    width: auto;
  }

.blank_title {
  position: absolute;
  top: 30px;
  text-align: center;
  width: 100%;
  text-transform: uppercase;
}

  .blank_number {
    position: absolute;
    bottom: 35px;
    text-align: center;
    width: 100%;
    font: 12pt/12pt "Arial";
    font-weight: bold;
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
    border: 0px solid #000 !important;
  }

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  line-height: 1;
  padding: 2px;
}

  .blank-img {
    width: 100%;
    height: auto;
  }

  .blank-img img {
    max-width: 100%;
    height: auto;
  }

@media print {
  @page {
    size:  auto;   /* auto is the initial value */
    margin: 0mm 0mm 0mm -5mm;  /* this affects the margin in the printer settings */
    -webkit-print-color-adjust: exact;
  }
  .document-preview {
      width: 100%;
      margin: 0;
      padding: 0;
      border: none;
      position: relative;
  }
  .blank_watermark {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 15%;
    overflow: hidden;
    background: url('/img/watermark.png') !important;
    background-repeat: repeat-y !important;
    background-size: cover !important;
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

<div class="document-preview">
	<div class="blank-img">
		<div class="blank_watermark">&nbsp;</div>
		<div class="blank_title">уполномоченная организация самарской области</div>
		<img src="/blanks/{{ $blank->blank_id }}/file?file=side_a" alt="">
		<div class="blank_number">{{ $blank->full_number }}</div>
	</div>
</div>

<div class="document-preview" style="margin-top: 50px">
	<div class="blank-img">
		<img src="/blanks/{{ $blank->blank_id }}/file?file=side_b" alt="">
	</div>
</div>
@endsection
