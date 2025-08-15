@php
    use App\Services\acad\ReportesAcademicosService;
@endphp

@extends('layouts.pdf')

@section('title', 'RESULTADOS ERE')

@section('content')

    <style>
        @page {
            margin-top: 4cm;
            margin-bottom: 1.5cm;
            margin-left: 1.5cm;
            margin-right: 2cm;
        }

        #tableDatosMatricula,
        #tableComentarioGeneral {
            width: 65%;
            margin-left: auto;
            margin-right: auto;
        }

        #tableDatosMatricula th {
            text-align: left;
            background-color: #dae0e5;
        }

        #tableAreasCurriculares th,
        #tableCompetenciasSinArea th,
        #tableInasistencias th,
        #tableComentarioGeneral th {
            background-color: #dae0e5;
            font-size: 0.9em;
        }

        th,
        td {
            border: 1px solid black;
        }
    </style>


    <main>
        <table class="table table-condensed" id="tableDatosMatricula">
            <tbody>
                <tr>
                    <th style="width: 25%;">DRE:</th>
                    <td>DRE MOQUEGUA</td>
                    <th style="width: 15%;">UGEL:</th>
                    <td>UGEL Mariscal Nieto</td>
                </tr>
                <tr>
                    <th>Nivel:</th>
                    <td>{{ $matricula->cNivelTipoNombre }}</td>
                    <th>Código Modular:</th>
                    <td>{{ $matricula->cIieeCodigoModular }}</td>
                </tr>
                <tr>
                    <th>Institución educativa:</th>
                    <td colspan="3">{{ $matricula->cIieeNombre }}</td>
                </tr>
                <tr>
                    <th>Grado:</th>
                    <td>{{ $matricula->cGradoNombre }}</td>
                    <th>Sección:</th>
                    <td>{{ $matricula->cGradoAbreviacion }} {{ $matricula->cSeccionNombre }}</td>
                </tr>
                <tr>
                    <th>Apellidos y nombres del estudiante:</th>
                    <td colspan="3">{{ $matricula->cPersPaterno }} {{ $matricula->cPersMaterno }},
                        {{ $matricula->cPersNombre }}</td>
                </tr>
                <tr>
                    <th>Código del estudiante:</th>
                    <td>{{ $matricula->cEstCodigo }}</td>
                    <th>{{ $matricula->cTipoIdentSigla }}:</th>
                    <td>{{ $matricula->cPersDocumento }}</td>
                </tr>
                <tr>
                    <th>Apellidos y nombres del docente o tutor:</th>
                    <td colspan="3">MELGAREJO MAMANI, MARIA LUPE</td>
                </tr>
            </tbody>
        </table>

        <br><br>

        <table class="table table-condensed" id="tableAreasCurriculares">
            <thead>
                <tr>
                    <th rowspan="2">Area curricular</th>
                    <th rowspan="2">Competencia</th>
                    <th colspan="2">PRIMER PERIODO</th>
                    <th colspan="2">SEGUNDO PERIODO</th>
                    <th colspan="2">TERCER PERIODO</th>
                    <th colspan="2">CUARTO PERIODO</th>
                    <th rowspan="2" style="width: 10%">NL alcanzado al finalizar el periodo lectivo</th>
                </tr>
                <tr>
                    <th>NL</th>
                    <th>Conclusión descriptiva</th>
                    <th>NL</th>
                    <th>Conclusión descriptiva</th>
                    <th>NL</th>
                    <th>Conclusión descriptiva</th>
                    <th>NL</th>
                    <th>Conclusión descriptiva</th>
                </tr>
            </thead>
            <tobdy>
                @php
                    $cursos = ReportesAcademicosService::obtenerCursosPorIe(
                        $matricula->iSedeId,
                        $matricula->iNivelGradoId,
                    );

                    foreach ($cursos as $curso) {
                        echo '<tr>';
                        echo '<td rowspan=' . $curso->iCantidadFilas . '>' . $curso->cCursoNombre . '</td>';

                        $competencias = ReportesAcademicosService::obtenerCompetenciasPorCurso(
                            $curso->iNivelTipoId,
                            $curso->iCursoId,
                        );
                        $cantidadCompetencias = count($competencias);
                        $primeraFila = true;
                        if ($cantidadCompetencias == 0) {
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo '</tr>';
                        } else {
                            foreach ($competencias as $competencia) {
                                if (!$primeraFila) {
                                    echo '<tr>';
                                } else {
                                    $primeraFila = false;
                                }
                                echo '<td>' . $competencia->cCompetenciaNombre . '</td>';
                                for ($i = 1; $i <= 4; $i++) {
                                    $competencia = ReportesAcademicosService::obtenerResultadosPorCompetencia(
                                        $matricula->iMatrId,
                                        $competencia->iCompetenciaId ?? 0,
                                        $curso->iIeCursoId,
                                        $i,
                                    );
                                    if ($competencia) {
                                        echo '<td>' . $competencia->cNivelLogro . '</td>'; // NL
                                        echo '<td>' . $competencia->cDescripcion . '</td>'; // Conclusión descriptiva
                                    } else {
                                        echo '<td></td>'; // NL
                                        echo '<td></td>'; // Conclusión descriptiva
                                    }
                                }
                                echo '<td></td>'; // NL alcanzado al finalizar el periodo lectivo
                                echo '</tr>';
                            }
                        }
                    }
                @endphp
            </tobdy>
        </table>

        <br><br>

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
        </table>

        <br><br>

        <table class="table table-condensed" id="tableComentarioGeneral">
            <thead>
                <tr>
                    <th>Comentario general</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
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
    </main>

@endsection
