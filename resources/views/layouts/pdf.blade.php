<!doctype html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>@yield('title', 'Sistema')</title>

    <!-- Desktop favicons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" sizes="16x16">

    <!-- Styles -->
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/pdf.css') }}"> -->
    <style>
        .table-bordered tr {
            page-break-inside: avoid;
        }

        .table-bordered td,
        .table-bordered th  {
            border: 1 solid black !important;
            white-space: pre-line;
        }

        body {
            line-height: 1;
        }

        html {
            font-size: 12px;
        }

        .font-xs {
            font-size: 6px !important;
        }

        .font-sm {
            font-size: 8px !important;
        }

        .font-md {
            font-size: 10px !important
        }

        .font-lg {
            font-size: 14px !important
        }

        .font-xl {
            font-size: 16px !important
        }

        @page {
            margin-top: 3cm;
            margin-bottom: 1.5cm;
            margin-left: 1.5cm;
            margin-right: 2cm;
        }

        header {
            position: fixed;
            top: -1.5cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        footer {
            position: fixed;
            bottom: -2cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        footer .paginacion:before {
            content: counter(page);
        }

        table.table th,
        table.table td {
            padding: 0.5em 0.5em;
        }

        hr {
            border-color: black;
            margin: 0.5em -0.5em;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.9rem;
        }
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .table {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 0;
        }
        .table-aside {
            width: 25%;
            table-layout: fixed;
            margin-bottom: 0;
            float: right;
            page-break-inside: avoid;
        }
        .table-borderless {
            border: none;
        }
        .table-bordered {
            border: 1px solid black;
        }
        .table-condensed {
            border-collapse: collapse;
            border-spacing: 0;
        }
        .table-sm {
            font-size: 0.8rem;
        }
        .align-middle {
            vertical-align: middle;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .py-0 {
            padding-top: 0;
            padding-bottom: 0;
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .p-0 {
            padding: 0;
        }
        .m-0 {
            margin: 0;
        }
        .bg-light {
            background-color: #dae0e5;
        }
        .border {
            border: 1px solid;
        }
        .border-bottom {
            border-bottom: 1px solid;
        }
        .border-dark {
            border-color: black;
        }
        div.page-break {
            page-break-after: always;
        }
        .text-danger {
            color: darkred;
        }
        .text-success {
            color: darkgreen;
        }
    </style>

</head>

<body>
    @yield('content')
</body>

</html>
