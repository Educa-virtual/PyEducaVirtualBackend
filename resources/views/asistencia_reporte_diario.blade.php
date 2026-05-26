@php
    use App\Services\acad\ReportesAcademicosService;
    use App\Services\asi\AsistenciaGeneralService;
    use App\Helpers\ImagenABase64;
    use Carbon\Carbon;
@endphp

@extends('layouts.pdf')
@section('title', 'Reporte académico de progreso')
@section('content')
    <style>
    #pie-izquierdo {
            position: running(pieIzquierdo);
        }

        #pie-derecho {
            position: running(pieDerecho);
        }

        @page {
            size: A4 portrait;
            margin-top: 1cm;
            margin-bottom: 2cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;

            @bottom-left {
                content: element(pieIzquierdo);
            }

            @bottom-center {
                content: "PÁGINA " counter(page) " DE " counter(pages);
                font-family: Arial, Helvetica, sans-serif;
                font-size: 0.9rem;
            }

            @bottom-right {
                content: element(pieDerecho);
            }
        }

        body {
            margin: 0;
        }

        #tableComentarioGeneral,
        #tableSituacionFinal {
            width: 65%;
            margin-left: auto;
            margin-right: auto;
        }

        #tableSituacionFinal th {
            background-color: #dae0e5;
        }

        #tableDatosMatricula th {
            text-align: left;
            background-color: #dae0e5;
        }

        #tableAreasCurriculares th,
        .table-areas-curriculares th,
        #tableCompetenciasSinArea th,
        #tableInasistencias th,
        #tableComentarioGeneral th {
            background-color: #dae0e5;
            font-size: 0.9em;
        }

        th,
        td {
            border: 1px solid black;
            padding: 4px !important;
        }

        td.conclusion-descriptiva {
            font-size: 10px;
        }

        div.cabecera span {
            display: inline-block;
        }

        #tableDatosMatricula {
            display: inline-block;
        }

        div.cabecera img {
            display: block;
            max-height: 140px;
            max-width: 100%;
            margin: 0 auto;
        }

        .text-justify {
            text-align: justify;
        }

        table.sin-borde td,
        table.sin-borde th {
            border: none;
        }
    </style>
</head>

    <div class="cabecera" style="width: 100%">
        <table class="table table-condensed text-center table-sm py-2 sin-borde">
            <tbody>
                <tr>
                    <td style="width: 15%" class="text-left align-middle"><img
                            src="{{ ImagenABase64::convertir(public_path('images/logo-dremo.png')) }}"></td>
                    <td style="width: 70%" class="text-center align-middle">{{$respuesta['yearAcademico']}}<br></td>
                    <td style="width: 10%"></td>
                    <td style="width: 5%" class="text-right align-middle"><img
                            src="{{ ImagenABase64::convertir(public_path('images/logo-plataforma-virtual.png')) }}" /></td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td style="width: 18%; border: 0px; padding: 5px">
                        <img src="{{ ImagenABase64::convertir(public_path('images/logo_IE/Sello_Peru.png')) }}"
                            alt="Logo IE">
                    </td>
                    <td style="border: 0px">
                        <table class="table table-condensed" id="tableDatosMatricula">
                            <tbody>
                                <tr>
                                    <th style="width: 25%;">DRE:</th>
                                    <td>DRE MOQUEGUA</td>
                                    <th style="width: 15%;">UGEL:</th>
                                    <td>UGEL {{$respuesta['nombreUgel']}}</td>
                                </tr>
                                <tr>
                                    <th>Nivel:</th>
                                    <td>{{ mb_strtoupper(str_replace('Educación ', '', $respuesta['nivel'])) }}
                                    </td>
                                    <th>Código Modular:</th>
                                    <td>{{ $respuesta['modular'] }}</td>
                                </tr>
                                <tr>
                                    <th>Institución educativa:</th>
                                    <td colspan="3">{{ mb_strtoupper($respuesta['iiee']) }}</td>
                                </tr>
                                <tr>
                                    <th>Área Curricular:</th>
                                    <td colspan="3">{{mb_strtoupper($respuesta['area_curricular'])}}</td>
                                </tr>
                                <tr>
                                    <th>Grado:</th>
                                    <td>{{mb_strtoupper($respuesta['grado'])}}</td>
                                    <th>Sección:</th>
                                    <td>{{mb_strtoupper($respuesta['seccion'])}}</td>
                                </tr>
                                <tr>
                                    <th>Apellidos y nombres del Docente:</th>
                                    <td colspan="3">
                                        @php
                                            echo mb_strtoupper($respuesta['docente']);
                                        @endphp
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 15%; border: 0px; padding: 5px">
                        @php
                            if ($respuesta['logo'] != null) {
                                echo '<img src="' . $respuesta['logo'] . '" alt="Logo IE">';
                            }
                        @endphp
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="table table-condensed table-areas-curriculares">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombres Y Apellidos</th>
                <th class="text-center">Asistencia - {{$respuesta['fecha_actual']}}</th>
            </tr>
        </thead>
        <tobdy>
            @if(!empty($respuesta['datos']))
                @foreach($respuesta['datos']['lista'] as $key => $index)
                <tr>
                    <th class="text-center">{{$key+1}}</th>
                    <td>{{$index[0]}}</td>
                    <th class="text-center">{{$index[1]}}</th> 
                </tr>
                @endforeach
            @endif
        </tobdy>
    </table>

    <footer>
        <table>
            <tr>
                <th>Legenda:</th>
                <th>[A] Asistió</th>
                <th>[I] Inasistencia</th>
                <th>[IJ] Inasistencia Justificada</th>
                <th>[T] Tardanza</th>
                <th>[TJ] Tardanza Justificada</th>
                <th>[-] Sin Registro</th>
            </tr>
        </table>
    </footer>
    
@endsection