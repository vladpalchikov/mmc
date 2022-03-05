@extends('layouts.print_report')

@section('content')
    <style>
        * {
            font-size: 12px;
        }
        .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000 !important;
        }
        @media print {
            .btn-danger {
                display: none;
            }
        }
    </style>

    <div class="row mt20">
        <div class="col-md-12">
            <a href="/operator/report/documents?daterange={{ Request::get('daterange') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    <p class="text-muted"><small>Подготовлено в ММЦ Онлайн {{ date('d.m.Y в H:i') }}</small></p>
    <h3>Отчет о документах</h3>

    @if ($documents->count() > 0)
        @include('report.document.table', ['print' => true])
    @else
        <div class="alert alert-info text-center" role="alert">Нет документов</div>
    @endif
    <script>
        window.print();
    </script>
@endsection
