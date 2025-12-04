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

    <div id="pie-izquierdo">IMP. POR {{ $persona->cPersPaterno }} {{ $persona->cPersMaterno }}, {{ $persona->cPersNombre }}</div>
    <div id="pie-derecho">FECHA IMP.: {{ date('d/m/Y') }} A LAS {{ date('h:i') }}</div>

    <div class="cabecera" style="width: 100%">
        <table class="table table-condensed text-center table-sm py-2 sin-borde">
            <tbody>
                <tr>
                    <td style="width: 15%" class="text-left align-middle"><img
                            src="{{ ImagenABase64::convertir(public_path('images/logo-dremo.png')) }}"></td>
                    <td style="width: 70%" class="text-center align-middle">{{ $yearAcademico->iYearId }}<br></td>
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
                                    <td>UGEL {{ mb_strtoupper($ie->cUgelNombre) }}</td>
                                </tr>
                                <tr>
                                    <th>Nivel:</th>
                                    <td>{{ mb_strtoupper(str_replace('Educación ', '', $ie->cNivelTipoNombre)) }}
                                    </td>
                                    <th>Código Modular:</th>
                                    <td>{{ $ie->cIieeCodigoModular }}</td>
                                </tr>
                                <tr>
                                    <th>Institución educativa:</th>
                                    <td colspan="3">{{ mb_strtoupper($ie->cIieeNombre) }}</td>
                                </tr>
                                <tr>
                                    <th>Área Curricular:</th>
                                    <td colspan="3">{{mb_strtoupper($area)}}</td>
                                </tr>
                                <tr>
                                    <th>Grado:</th>
                                    <td>{{mb_strtoupper($grado)}}</td>
                                    <th>Sección:</th>
                                    <td>{{mb_strtoupper($seccion)}}</td>
                                </tr>
                                <tr>
                                    <th>Periodo:</th>
                                    <td colspan="3">{{mb_strtoupper($periodo)}}</td>
                                </tr>
                                <tr>
                                    <th>Apellidos y nombres del Docente:</th>
                                    <td colspan="3">
                                        @php
                                            echo mb_strtoupper(
                                                $persona->cPersPaterno .
                                                    ' ' .
                                                    $persona->cPersMaterno .
                                                    ', ' .
                                                    $persona->cPersNombre,
                                            );
                                        @endphp
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 18%; border: 0px; padding: 5px">
                        @php
                            if ($ie->cIieeLogo != null) {
                                echo '<img src="' . $ie->cIieeLogo . '" alt="Logo IE">';
                            }
                        @endphp
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>

    <table class="table table-condensed table-areas-curriculares">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center" style="width: 25%">Tipo de actividad</th>
                <th class="text-center">Titulo</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Fecha de inicio</th>
                <th class="text-center">Fecha de fin</th>
                <th class="text-center">Fecha de Publicación</th>
                <th class="text-center" style="width: 9%">Estado</th>
            </tr>
        </thead>
        <tobdy>
            @php
                foreach ($actividades as $index => $actividad) {
                    echo '<tr>';
                    echo '<td>'.($index+1).'</td>';
                    echo '<td>'.$actividad->cActTipoNombre.'</td>';
                    echo '<td>'.$actividad->cProgActTituloLeccion.'</td>';
                    echo '<td>'.$actividad->cProgActDescripcion.'</td>';
                    echo '<td>'.$actividad->dtProgActInicio.'</td>';
                    echo '<td>'.$actividad->dtProgActFin.'</td>';
                    echo '<td>'.$actividad->dtProgActPublicacion.'</td>';
                    echo '<td>'.$actividad->iEstado.'</td>';
                    echo '</tr>';
                }
            @endphp
        </tobdy>
    </table>

@endsection
