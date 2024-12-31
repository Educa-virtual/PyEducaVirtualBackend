<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SYLLABUS</title>
    <style>
        .hojas{
            margin-top: 30px;
            margin-bottom: 10px;
        }
        .titulo{
            text-align: center;
            
        }
        h4{
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }
        h5{
            font-family: Arial, Helvetica, sans-serif;
            margin: 0px;
        }
        caption{
            font-family: Helvetica,Arial, sans-serif;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            padding: 7px;
        }
        th{
            font-family: Arial, Helvetica, sans-serif;
            border: 1px solid black;
            border-radius: 3px;
            text-align: left;
            font-size: 12px;
            padding: 7px;
        }
        td{
            font-family: Arial, Helvetica, sans-serif;
            border: 1px solid black;
            border-radius: 3px;
            text-align: justify;
            font-size: 12px;
            padding: 7px;
        }
        .sin_bordes{
            border: 0px;
        }
        .bordes{
            border: 1px solid black;
        }
        .page-break {
	    page-break-after: always;
	    }
    </style>
</head>
<body>

    <div class="hojas">
        <div class="titulo">
            <h4>SILABO DE UNIDAD DIDÁCTICA</h4>
        </div>
        <h5>I. INFORMACIÓN GENERAL</h5>
        <table>
            <tr>
                <th>PROGRAMA DE ESTUDIOS</th>
                <th>{{$query->cProgNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>MODULO FORMATIVO</th>
                <th>{{$query->cModuloNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>COMPONENTE CURRICULAR</th>
                <th>{{$query->cTipoCursoNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>UNIDAD DIDÁCTICA</th>
                <th>{{$query->cCursoNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>CARGA HORARIA (N° HORAS)</th>
                <th>{{$query->iCursoTotalHoras ?? '-'}}</th>
            </tr>
            <tr>
                <th>N° DE CRÉDITO</th>
                <th>{{$query->nCursoTotalCreditos ?? '-'}}</th>
            </tr>
            <tr>
                <th>PERIODO ACADÉMICO</th>
                <th>{{$query->cSemAcadNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>PERIODO LECTIVO</th>
                <th>{{$query->cYAcadNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>MODALIDAD</th>
                <th>{{$query->cModalServNombre ?? '-'}}</th>
            </tr>
            <tr>
                <th>DOCENTE</th>
                <th>{{$query->completos ?? '-'}}</th>
            </tr>
            <tr>
                <th>CORREO ELECTRÓNICO</th>
                <th>{{$query->cDocenteCorreo ?? '-'}}</th>
            </tr>
        </table>
        <table>
            <caption>II. PERFIL DE EGRESO</caption>
            <tr>
                <td>{{$query->cCurrPerfilEgresado ?? '-'}}</td>
            </tr>
        </table>
        <table>
            <caption>III. DESCRIPCIÓN DE UNIDAD DIDÁCTICA</caption>
            <tr>
                <td>{{$query->cSilaboDescripcionCurso ?? '-'}}</td>
            </tr>
        </table>
        <table>
            <caption>IV. CAPACIDAD</caption>
            <tr>
                <td>{{$query->cSilaboCapacidad ?? '-'}}</td>
            </tr>
        </table>
    </div>
    <div class="page-break"></div>
    <div class="hojas">
        <h5>V. METODOLOGÍA</h5>
        <table class="bordes">
            @if (!empty($query->metodo))
                @foreach (json_decode($query->metodo) as $tipo)
                <tr>
                    <th class="sin_bordes">{{$tipo->cTipoMetNombre}}</th>
                </tr>
                @if (!empty($tipo->metodologias))
                    <tr>
                        <td class="sin_bordes">
                            <ul>
                            @foreach ($tipo->metodologias as $met)
                                <li>{{$met->cSilMetDescripcion}}</li>
                            @endforeach
                            </ul>
                        </td>
                    </tr>
                @endif
                @endforeach
            @else
                <tr>
                    <td class="sin_bordes">
                        --
                    </td>
                </tr>
            @endif
            
        </table>
        <h5>VI. RECURSOS DIDACTICOS</h5>
        <table>
            @if (!empty($query->recursos))
            <tr>
                <td>
                    <ul>
                    @foreach (json_decode($query->recursos) as $rec)
                            <li>{{$rec->cRecSilaboDescripcion}}</li>
                            
                            @if (!empty($rec->recursosdidacticos))
                                <ul>
                                @foreach ($rec->recursosdidacticos as $recur)
                                <li>{{$recur->cRecDidacticoNombre}}:{{$recur->cRecDidacticoDescripcion}}</li>
                                @endforeach
                                </ul>
                            @endif
                            
                            
                    @endforeach
                    </ul>
                </td>
            </tr>
            @else
            <tr>
                <td class="sin_bordes">
                    --
                </td>
            </tr>
            @endif
        </table>
    </div>
    <div class="page-break"></div>
    <div class="hojas">
        <h5>VII. CRONOGRAMA - DESARROLLO DE APRENDIZAJE</h5>
        <table>
            <tr>
                <th>NRO ACTIVIDAD</th>
                <th>SEM.</th>
                <th>INDICADOR</th>
                <th>CONTENIDO BÁSICOS</th>
            </tr>
            @if (!empty($query->actividad))
                @foreach(json_decode($query->actividad) as $list)
                    @if (!empty($list->indicadores))
                        @foreach($list->indicadores AS $indi)
                            @if (!empty($indi->contenidos))
                                @foreach($indi->contenidos AS $con)
                                <tr>
                                    <td>{{$list->cSilaboActAprendNumero ?? '-'}}</td>
                                    <td>{{$indi->cIndActNumero ?? '-'}}</td>
                                    <td>{{$con->cContenidoSemNumero ?? '-'}}</td>
                                    <td>{{$con->cContenidoSemTitulo ?? '-'}}</td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach  
                    @endif
                @endforeach
                @else
                <tr>
                    <td class="sin_bordes">
                        --
                    </td>
                </tr>
            @endif
            
        </table>
        <h5>VIII. ACTIVIDADES DE EVALUACIÓN Y RECUPERACIÓN</h5>
        <table>
                <tr>
                    <th>SEMANA</th>
                    <th>INDICADOR DE LOGRO</th>
                    <th>INDICADOR</th>
                </tr>
                @if (!empty($query->actividad))
                    @foreach(json_decode($query->actividad) as $list)
                        @if (!empty($list->indicadores))
                            @foreach($list->indicadores AS $indi)
                            <tr>
                                <td>{{$indi->iIndActSemanaEval}}</td>
                                <td>{{$indi->cTipoIndLogNombre}}</td>
                                <td>{{$indi->cIndActNumero}}</td>
                            </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td>{{$list->iSilaboActAprendSemanaEval}}</td>
                            <td>{{$list->cSilaboActIndLogro}}</td>
                            <td>{{intval($list->cSilaboActAprendNumero)}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="sin_bordes">
                            --
                        </td>
                    </tr> 
                @endif
                
        </table>
    </div>
    <div class="page-break"></div>
    <div class="hojas">
        <h5>IX. EVALUACIÓN</h5>
        <table>
            @if (!empty($query->detalles))
                <tr>
                    <td>
                        <ul>
                        @foreach(json_decode($query->detalles) AS $det)
                            <li>{{$det->cDetEvalDetalles}}</li>
                        @endforeach
                        </ul>
                    </td>
                </tr>
            @else
                <tr>
                    <td class="sin_bordes">
                        --
                    </td>
                </tr>
            @endif
        </table>
        <h5>X. BIBLIOGRAFÍA</h5>
        <table>
            <tr>
                <th>NRO</th>
                <th>AUTOR</th>
                <th>AÑO EDICIÓN</th>
                <th>TÍTULO DE LA OBRA</th>
                <th>EDITORIAL</th>
            </tr>
            @if (!empty($query->bibliografias))
                @foreach(json_decode($query->bibliografias) AS $bib)
                <tr>
                    <td>{{($loop->index) + 1}}</td>
                    <td>{{$bib->cBiblioAutor ?? '-'}}</td>
                    <td>{{$bib->cBiblioAnioEdicion ?? '-'}}</td>
                    <td>{{$bib->cBiblioTitulo ?? '-'}}</td>
                    <td>{{$bib->cBiblioEditorial ?? '-'}}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="sin_bordes">
                        --
                    </td>
                </tr>
            @endif
        </table>
    </div>

    {{-- ver el numero de pagina en pie de pagina --}}
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 820, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>