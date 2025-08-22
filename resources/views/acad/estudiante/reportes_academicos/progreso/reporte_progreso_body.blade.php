@php
    use App\Services\acad\ReportesAcademicosService;
@endphp

@extends('layouts.pdf')
@section('title', 'Reporte académico de progreso')
@section('content')
    <style>
        @page {
            margin-top: 4cm;
            margin-bottom: 1.5cm;
            margin-left: 1.5cm;
            margin-right: 2cm;
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
            /*word-break: break-word;
            hyphens: auto;*/
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

        .page-break {
            page-break-before: always;
        }
        .text-justify {
            text-align: justify;
        }
    </style>


    <main>
        <div class="cabecera" style="width: 100%">
            <table>
                <tbody>
                    <tr>
                        <td style="width: 18%; border: 0px; padding: 5px">
                            <img src="{{ public_path('images/logo_IE/Sello_Peru.png') }}" alt="Logo IE">
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
                    <th rowspan="2">Área curricular</th>
                    <th rowspan="2" style="width: 25%">Competencia</th>
                    <th colspan="2">PRIMER PERIODO</th>
                    <th colspan="2">SEGUNDO PERIODO</th>
                    <th colspan="2">TERCER PERIODO</th>
                    <th colspan="2">CUARTO PERIODO</th>
                    <th rowspan="2" style="width: 9%">NL alcanzado al finalizar el periodo lectivo</th>
                </tr>
                <tr>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                </tr>
            </thead>
            <tobdy>
                @php
                    $cursos = ReportesAcademicosService::obtenerCursosPorIe(
                        $matricula->iSedeId,
                        $matricula->iNivelGradoId,
                    );
                    $contador = 0;
                    $totalFilas=count($cursos);
                    foreach ($cursos as $curso) {
                        $contador++;
                        echo '<tr>';
                        echo '<td rowspan=' . $curso->iCantidadFilas . '>' . $curso->cCursoNombre . '</td>';

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
                                            echo '<td lang="es" class="conclusion-descriptiva">' . $resultadoCompetencia->cDescripcion . '</td>';
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
                        if ($contador % 500 == 0 && $totalFilas>$contador) {
                            echo '</tbody></table>';
                            echo '<div style="page-break-before: always;"></div>';
                            echo '<table class="table table-condensed table-areas-curriculares">
            <thead>
                <tr>
                    <th rowspan="2">Área curricular</th>
                    <th rowspan="2" style="width: 25%">Competencia</th>
                    <th colspan="2">PRIMER PERIODO</th>
                    <th colspan="2">SEGUNDO PERIODO</th>
                    <th colspan="2">TERCER PERIODO</th>
                    <th colspan="2">CUARTO PERIODO</th>
                    <th rowspan="2" style="width: 9%">NL alcanzado al finalizar el periodo lectivo</th>
                </tr>
                <tr>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                    <th style="width: 3%">NL</th>
                    <th style="width: 9%">Conclusión descriptiva</th>
                </tr>
            </thead>
            <tobdy>';
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
                    <th>Comentario general</th>
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
                    <th rowspan="2">Periodo</th>
                    <th colspan="2">Inasistencia</th>
                    <th colspan="2">Tardanzas</th>
                </tr>
                <tr>
                    <th>Justificadas</th>
                    <th>Injustificadas</th>
                    <th>Justificadas</th>
                    <th>Injustificadas</th>
                </tr>
            </thead>
        </table>


        <!--<br><br><table class="table table-condensed" id="tableSituacionFinal">
                                <tbody>
                                    <tr>
                                        <th style="width: 55%">Situación al finalizar el periodo lectivo</th>
                                        <td>{{ $matricula->cEscalaCalifNombre }} - {{ $matricula->cEscalaCalifDescripcion }}</td>
                                    </tr>
                                </tbody>
                            </table>-->
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

    </main>
@endsection
