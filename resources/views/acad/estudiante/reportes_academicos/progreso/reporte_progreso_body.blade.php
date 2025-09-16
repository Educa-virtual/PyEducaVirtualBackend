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
            margin-top: 3cm;
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

        /*td[rowspan] {
            position: relative;
        }

        td[rowspan]::after {
            content: "";
            bottom: 0;
            left: 0;
            right: 0;
            border-bottom: 1px solid black;
        }*/
    </style>

    <div id="pie-izquierdo">IMP. POR {{ $persona->cPersPaterno }} {{ $persona->cPersMaterno }}, {{ $persona->cPersNombre }}
    </div>
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
                                    <td>UGEL {{ mb_strtoupper($matricula->cUgelNombre) }}</td>
                                </tr>
                                <tr>
                                    <th>Nivel:</th>
                                    <td>{{ mb_strtoupper(str_replace('Educación ', '', $matricula->cNivelTipoNombre)) }}
                                    </td>
                                    <th>Código Modular:</th>
                                    <td>{{ $matricula->cIieeCodigoModular }}</td>
                                </tr>
                                <tr>
                                    <th>Institución educativa:</th>
                                    <td colspan="3">{{ mb_strtoupper($matricula->cIieeNombre) }}</td>
                                </tr>
                                <tr>
                                    <th>Grado:</th>
                                    <td>{{ mb_strtoupper($matricula->cGradoNombre) }}</td>
                                    <th>Sección:</th>
                                    <td>{{ mb_strtoupper($matricula->cGradoAbreviacion) }}
                                        {{ $matricula->cSeccionNombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Apellidos y nombres del estudiante:</th>
                                    <td colspan="3">{{ mb_strtoupper($matricula->cPersPaterno) }}
                                        {{ mb_strtoupper($matricula->cPersMaterno) }},
                                        {{ mb_strtoupper($matricula->cPersNombre) }}</td>
                                </tr>
                                <tr>
                                    <th>Código del estudiante:</th>
                                    <td>{{ $matricula->cEstCodigo }}</td>
                                    <th>{{ $matricula->cTipoIdentSigla }}:</th>
                                    <td>{{ $matricula->cPersDocumento }}</td>
                                </tr>
                                <tr>
                                    <th>Apellidos y nombres del docente o tutor:</th>
                                    <td colspan="3">
                                        @php
                                            if ($tutor) {
                                                echo mb_strtoupper(
                                                    $tutor->cPersPaterno .
                                                        ' ' .
                                                        $tutor->cPersMaterno .
                                                        ', ' .
                                                        $tutor->cPersNombre,
                                                );
                                            }
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
                <th rowspan="2" class="text-center">Área curricular</th>
                <th rowspan="2" class="text-center" style="width: 25%">Competencia</th>
                <th colspan="2" class="text-center">PRIMER PERIODO</th>
                <th colspan="2" class="text-center">SEGUNDO PERIODO</th>
                <th colspan="2" class="text-center">TERCER PERIODO</th>
                <th colspan="2" class="text-center">CUARTO PERIODO</th>
                <th rowspan="2" class="text-center" style="width: 9%">NL alcanzado al finalizar el periodo lectivo</th>
            </tr>
            <tr>
                <th style="width: 3%" class="text-center">NL</th>
                <th style="width: 10%" class="text-center">Conclusión descriptiva</th>
                <th style="width: 3%" class="text-center">NL</th>
                <th style="width: 10%" class="text-center">Conclusión descriptiva</th>
                <th style="width: 3%" class="text-center">NL</th>
                <th style="width: 10%" class="text-center">Conclusión descriptiva</th>
                <th style="width: 3%" class="text-center">NL</th>
                <th style="width: 10%" class="text-center">Conclusión descriptiva</th>
            </tr>
        </thead>
        <tobdy>
            @php
                $cursos = ReportesAcademicosService::obtenerCursosPorIe($matricula->iSedeId, $matricula->iNivelGradoId, $matricula->iYAcadId);
                $contador = 0;
                $totalFilas = count($cursos);
                foreach ($cursos as $curso) {
                    $contador++;
                    echo '<tr>';
                    echo '<td rowspan=' .
                        ($curso->iCantidadFilas == '0' ? '1' : $curso->iCantidadFilas) .
                        '>' .
                        $curso->cCursoNombre .
                        '</td>';

                    $competencias = ReportesAcademicosService::obtenerCompetenciasPorCurso(
                        $curso->iNivelTipoId,
                        $curso->iCursoId,
                    );
                    $cantidadCompetencias = count($competencias);
                    $primeraFila = true;
                    if ($cantidadCompetencias == 0) {
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '</tr>';
                    } else {
                        foreach ($competencias as $competencia) {
                            if (!$primeraFila) {
                                echo '<tr>';
                            } else {
                                $primeraFila = false;
                            }
                            echo '<td class="text-justify">' . $competencia->cCompetenciaNombre . '</td>';
                            for ($i = 1; $i <= 5; $i++) {
                                $resultadoCompetencia = ReportesAcademicosService::obtenerResultadosPorCompetencia(
                                    $matricula->iMatrId,
                                    $competencia->iCompetenciaId ?? 0,
                                    $curso->iIeCursoId,
                                    $i,
                                );
                                if ($resultadoCompetencia) {
                                    if ($i == 5) {
                                        echo '<td class="text-center"><strong>' .
                                            $resultadoCompetencia->cNivelLogro .
                                            '</strong></td>'; // NL
                                    } else {
                                        echo '<td class="text-center"><strong>' .
                                            $resultadoCompetencia->cNivelLogro .
                                            '</strong></td>'; // NL
                                        echo '<td class="conclusion-descriptiva text-justify">' .
                                            $resultadoCompetencia->cDescripcion .
                                            '</td>';
                                    }
                                } else {
                                    if ($i == 5) {
                                        echo '<td></td>'; // NL
                                    } else {
                                        echo '<td></td>'; // NL
                                        echo '<td></td>'; // Conclusión descriptiva
                                    }
                                }
                            }
                            echo '</tr>';
                        }
                    }
                }
            @endphp
        </tobdy>
    </table>

    <!--<br><br>

                                                                                <table class="table table-condensed" id="tableCompetenciasSinArea">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Competencias transversales/No asociada(s) a área(s)</th>
                                                                                            <th style="width: 5%">NL</th>
                                                                                            <th>Conclusión descriptiva</th>
                                                                                            <th style="width: 5%">NL</th>
                                                                                            <th>Conclusión descriptiva</th>
                                                                                            <th style="width: 5%">NL</th>
                                                                                            <th>Conclusión descriptiva</th>
                                                                                            <th style="width: 5%">NL</th>
                                                                                            <th>Conclusión descriptiva</th>
                                                                                            <th style="width: 5%">NL alcanzado al finalizar el periodo lectivo</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                </table>-->

    <br><br>

    <table class="table table-condensed" id="tableComentarioGeneral">
        <thead>
            <tr>
                <th class="text-center">Comentario general</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-justify">{{ $matricula->cMatrConclusionDescriptiva }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <table class="table table-condensed " id="tableInasistencias">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">Periodo</th>
                <th colspan="2" class="text-center">Inasistencia</th>
                <th colspan="2" class="text-center">Tardanzas</th>
            </tr>
            <tr>
                <th class="text-center">Justificadas</th>
                <th class="text-center">Injustificadas</th>
                <th class="text-center">Justificadas</th>
                <th class="text-center">Injustificadas</th>
            </tr>
        </thead>
        <tbody>
            @php
                $contador = 1;
                foreach ($fechasInicioFin as $fecha) {
                    $fechaInicio = new Carbon($fecha->dtPeriodoEvalAperInicio);
                    $fechaFin = new Carbon($fecha->dtPeriodoEvalAperFin);

                    echo '<tr>';
                    echo '<td class="text-center">' . $fecha->cPeriodoEvalLetra . $contador . '</td>';
                    echo '<td class="text-center">';
                    $faltasJustificadas = AsistenciaGeneralService::obtenerCantidadRegistrosPorTipo(
                        $matricula->iEstudianteId,
                        $matricula->iYAcadId,
                        $matricula->iSedeId,
                        4,
                        $fechaInicio->format('Ymd'),
                        $fechaFin->format('Ymd'),
                    );
                    echo $faltasJustificadas->cantidad == 0 ? '-' : $faltasJustificadas->cantidad;
                    echo '</td>';

                    echo '<td class="text-center">';
                    $faltasInjustificadas = AsistenciaGeneralService::obtenerCantidadRegistrosPorTipo(
                        $matricula->iEstudianteId,
                        $matricula->iYAcadId,
                        $matricula->iSedeId,
                        3,
                        $fechaInicio->format('Ymd'),
                        $fechaFin->format('Ymd'),
                    );
                    echo $faltasInjustificadas->cantidad == 0 ? '-' : $faltasInjustificadas->cantidad;
                    echo '</td>';

                    echo '<td class="text-center">';
                    $tardanzasJustificadas = AsistenciaGeneralService::obtenerCantidadRegistrosPorTipo(
                        $matricula->iEstudianteId,
                        $matricula->iYAcadId,
                        $matricula->iSedeId,
                        9,
                        $fechaInicio->format('Ymd'),
                        $fechaFin->format('Ymd'),
                    );
                    echo $tardanzasJustificadas->cantidad == 0 ? '-' : $tardanzasJustificadas->cantidad;
                    echo '</td>';

                    echo '<td class="text-center">';
                    $tardanzasInjustificadas = AsistenciaGeneralService::obtenerCantidadRegistrosPorTipo(
                        $matricula->iEstudianteId,
                        $matricula->iYAcadId,
                        $matricula->iSedeId,
                        2,
                        $fechaInicio->format('Ymd'),
                        $fechaFin->format('Ymd'),
                    );
                    echo $tardanzasInjustificadas->cantidad == 0 ? '-' : $tardanzasInjustificadas->cantidad;
                    echo '</td>';
                    echo '</tr>';
                    $contador++;
                }
            @endphp

        </tbody>
    </table>

    <br><br><br>
    <table style="width: 100%; text-align: center; margin-top: 50px;">
        <tbody>
            <tr>
                <td style="width: 50%; border: none;">
                    <p>
                        _________________________________
                    </p>
                    <strong>Firma del Docente o Tutor(a)</strong>
                </td>
                <td style="width: 50%; border: none;">
                    <p>
                        _________________________________
                    </p>
                    <strong>Firma y sello del Director(a)</strong>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
