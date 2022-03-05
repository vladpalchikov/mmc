@extends('layouts.print_report')

@section('content')
    <link rel="stylesheet" href="/css/print.css">
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
            <a href="/operator/report/refund?daterange={{ Request::get('daterange') }}&mmc={{ Request::get('mmc') }}" class="btn btn-danger pull-right">Закрыть</a>
        </div>
    </div>

    @include('report.refund.table', ['print' => true])

    <script>
        window.print();
    </script>
@endsection
