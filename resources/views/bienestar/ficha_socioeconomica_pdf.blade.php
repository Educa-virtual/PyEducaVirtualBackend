<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FICHA SOCIEOCONÓMICA</title>
    <style>
        /* Estilos optimizados para PDF */
        @page {
            size: A4;
            margin: 15mm;
        }
        
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            line-height: 1.4;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-size: 11pt;
            color: #333;
        }
        
        .container {
            max-width: 100%;
            padding: 0;
        }
        
        .title {
            text-align: center;
            color: #1a3d6d;
            margin: 15px 0;
            font-size: 16pt;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a3d6d;
        }
        
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        
        h2 {
            background-color: #1a3d6d;
            color: white;
            padding: 5px 8px;
            font-size: 13pt;
            margin: 10px 0;
        }
        
        h3 {
            background-color: #e6f0ff;
            padding: 4px 8px;
            font-size: 12pt;
            margin: 8px 0;
            border-left: 4px solid #1a3d6d;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            page-break-inside: avoid;
        }
        
        th, td {
            border: 1px solid #d0d0d0;
            padding: 6px 8px;
            font-size: 9pt;
            vertical-align: top;
        }
        
        th {
            background-color: #f0f5ff;
            text-align: left;
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .header-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .header-info th {
            background: none;
            border: none;
            text-align: right;
            padding-right: 5px;
            color: black;
            width: 30%;
        }
        
        .header-info td {
            border: none;
            text-align: left;
            font-size: 12px;
            padding-left: 0;
            width: 70%;
        }
        
        .signature-section {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }
        
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 0 2%;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 1.5em;
            margin: 20px 0 5px;
        }
        
        .section-divider {
            border-top: 1px solid #1a3d6d;
            margin: 15px 0;
        }
        
        .note {
            font-size: 9pt;
            font-style: italic;
            color: #666;
            margin-top: 5px;
        }
        
        /* Mejoras para datos vacíos */
        .empty-data {
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="header-info">
            <tr>
                @if($ficha_estudiante)
                    <th>CÓDIGO DEL ESTUDIANTE:</th>
                    <td>{{ $persona['codigo_alumno'] ?? 'N/A' }}</td>
                @else
                    <th>DOCUMENTO DE IDENTIDAD:</th>
                    <td>{{ $persona['tipo_documento'] ?? 'N/A' }} {{ $persona['documento'] ?? '' }}</td>
                @endif
            </tr>
            <tr>
                <th>NÚMERO DE CELULAR:</th>
                <td>{{ $persona['num_telefono'] ?? 'N/A' }}</td>
            </tr>
        </table>

        <h1 class="title">FICHA SOCIOECONÓMICA - DECLARACIÓN JURADA</h1>

        <h2>I. DATOS GENERALES</h2>

        <!-- Datos Personales -->
        <div class="section">
            <h3>DATOS PERSONALES</h3>
            <table>
                <tr>
                    <th width="25%">PRIMER APELLIDO</th>
                    <th width="25%">SEGUNDO APELLIDO</th>
                    <th colspan="2" width="50%">NOMBRES</th>
                </tr>
                <tr>
                    <td>{{ $persona['apellido_paterno'] ?? 'N/A' }}</td>
                    <td>{{ $persona['apellido_materno'] ?? 'N/A' }}</td>
                    <td colspan="2">{{ $persona['nombres'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>TIPO DE DOCUMENTO</th>
                    <th>N° DE DOCUMENTO</th>
                    <th>FECHA DE NACIMIENTO</th>
                    <th>SEXO</th>
                </tr>
                <tr>
                    <td>{{ $persona['tipo_documento'] ?? 'N/A' }}</td>
                    <td>{{ $persona['documento'] ?? 'N/A' }}</td>
                    <td>{{ $persona['fecha_nacimiento'] ?? 'N/A' }}</td>
                    <td>{{ $persona['sexo'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>LUGAR DE NACIMIENTO</h3>
            <table>
                <tr>
                    <th width="25%">PAÍS</th>
                    <th width="25%">DEPARTAMENTO</th>
                    <th width="25%">PROVINCIA</th>
                    <th width="25%">DISTRITO</th>
                </tr>
                <tr>
                    <td>{{ $nacimiento['pais'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['departamento'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['provincia'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['distrito'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Sección : Dirección -->
        <div class="section">
            <h3>DIRECCIÓN</h3>
            <table>
                <tr>
                    <th colspan="3">TIPO DE VÍA</th>
                    <th colspan="3">NOMBRE DE VÍA</th>
                </tr>
                <tr>
                    <td colspan="3">{{ $direccion_domiciliaria['tipo_via'] ?? 'N/A' }}</td>
                    <td colspan="3">{{ $direccion_domiciliaria['nombre_via'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th width="17%">N° DE PUERTA</th>
                    <th width="17%">BLOQUE</th>
                    <th width="16%">INTERIOR</th>
                    <th width="17%">PISO</th>
                    <th width="17%">MZ.</th>
                    <th width="16%">LT.</th>
                </tr>
                <tr>
                    <td>{{ $direccion_domiciliaria['numero_puerta'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['block'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['interior'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['piso'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['mz'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['lote'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>KM</th>
                    <th colspan="5">REFERENCIA</th>
                </tr>
                <tr>
                    <td>{{ $direccion_domiciliaria['km'] ?? 'N/A' }}</td>
                    <td colspan="5">{{ $direccion_domiciliaria['referencia'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Sección : Familiares -->
        <div class="section">
            <h2>II. FAMILIA</h2>
            <table>
                <tr>
                    <th>¿VIVE SU PADRE?</th>
                    <th>¿VIVE SU MADRE?</th>
                    <th>¿TIENE HIJOS?</th>
                    <th>¿CUÁNTOS HIJOS?</th>
                </tr>
                <tr>
                    <td>{{ $persona['vive_padre'] ?? 'N/A' }}</td>
                    <td>{{ $persona['vive_madre'] ?? 'N/A' }}</td>
                    <td>{{ $persona['tiene_hijos'] ?? 'N/A' }}</td>
                    <td>{{ $persona['num_hijos'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h3>ESTRUCTURA FAMILIAR</h3>

            @if( isset($familiares) && count($familiares) > 0 )
                <table>
                    <tr>
                        <th width="10%">TIPO DE FAMILIAR</th>
                        <th width="10%">DOCUMENTO</th>
                        <th width="35%">APELLIDOS Y NOMBRES</th>
                        <th width="10%">GÉNERO</th>
                        <th width="5%">EDAD</th>
                        <th width="10%">VIVE CON EST.</th>
                        <th width="10%">OCUPACIÓN</th>
                        <th width="10%">GRADO DE INST.</th>
                    </tr>
                    @foreach ($familiares as $familiar)
                        <tr>
                            <td width="10%">{{ $familiar['tipo_familiar'] ?? 'N/A' }}</td>
                            <td width="10%">{{ $familiar['tipo_documento'] ?? '' }} {{ $familiar['documento'] ?? '' }}</td>
                            <td width="35%">{{ $familiar['apellido_paterno'] ?? '' }} {{ $familiar['apellido_materno'] ?? '' }} {{ $familiar['nombres'] ?? '' }}</td>
                            <td width="10%">{{ $familiar['sexo'] ?? 'N/A' }}</td>
                            <td width="5%">{{ $familiar['edad'] ?? 'N/A' }}</td>
                            <td width="10%">{{ $familiar['comparte_vivienda'] ?? 'N/A' }}</td>
                            <td width="10%">{{ $familiar['ocupacion'] ?? 'N/A' }}</td>
                            <td width="10%">{{ $familiar['grado_instruccion'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <table>
                    <tr>
                        <td colspan="8" class="empty-data">NO HAY FAMILIARES REGISTRADOS</td>
                    </tr>
                </table>
            @endif
        </div>
        
        <!-- Sección 3: Aspecto Económico -->
        <div class="section">
            <h2>III. ASPECTO ECONÓMICO</h2>
            <table>
                <tr>
                    <th width="50%">INGRESO MENSUAL FAMILIAR</th>
                    <td width="50%">{{ $aspecto_economico['rango_ingresos'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>ACTIVIDAD ECONÓMICA DE SU FAMILIA</th>
                    <td>{{ $aspecto_economico['actividad_ingreso'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>{{ $ficha_estudiante ? '¿EL APODERADO TRABAJA?' : '¿EL(A) JEFE DE FAMILIA TRABAJA?' }}</th>
                    <td>{{ $aspecto_economico['apoderado_jefe_trabaja'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>APOYO ECONÓMICO DE {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}</th>
                    <td>{{ $aspecto_economico['tipo_apoyo_economico'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>INGRESO MENSUAL DEL {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}</th>
                    <td>{{ $aspecto_economico['rango_ingresos_apoderado'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>HORAS TRABAJADAS POR {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}</th>
                    <td>{{ $aspecto_economico['horas_trabajo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>ACTIVIDAD ECONÓMICA DEL {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}</th>
                    <td>{{ $aspecto_economico['apoderado_depende_de'] ?? 'N/A' }}</td>
                </tr>
                    <th>¿DE QUIÉN DEPENDE EL {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}?</th>
                    <td>{{ $aspecto_economico['depende_economicamente_de'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>JORNADA LABORAL DEL {{ $ficha_estudiante ? 'APODERADO' : 'JEFE DE FAMILIA' }}</th>
                    <td>{{ $aspecto_economico['jornada_trabajo'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Sección 4: Aspectos de la Vivienda -->
        <div class="section">
            <h2>IV. ASPECTOS DE LA VIVIENDA DONDE RESIDE</h2>
            
            <table>
                <tr>
                    <th width="33%">TIPO DE OCUPACIÓN</th>
                    <th width="34%">ESTADO DE VIVIENDA</th>
                    <th width="33%">TIPO DE VIVIENDA</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['vivienda_es'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['estado'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['tipo_vivienda'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>N° DE PISOS</th>
                    <th>N° DE CUARTOS</th>
                    <th>N° DE DORMITORIOS</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['npisos'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['nro_ambientes'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['nro_habitaciones'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>MATERIAL DE PAREDES</th>
                    <th>MATERIAL DE PISOS</th>
                    <th>MATERIAL DE TECHOS</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['mat_pred_pared'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['mat_piso'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['mat_techo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>SUMINISTRO DE AGUA</th>
                    <th>SERVICIO HIGIENICO</th>
                    <th>TIPOS DE ALUMBRADO</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['tipo_servicio'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['tipo_sshh'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['tipo_alumbrado'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>ELEMENTOS EN SU HOGAR</h3>
            @if( isset($equipamiento) && count($equipamiento) > 0 )
                <table>
                    <tr>
                        <th width="10%">ITEM</th>
                        <th width="90%">ELEMENTO</th>
                    </tr>
                    @foreach ($equipamiento as $equipos)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $equipos['electrodm_hogar'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <table>
                    <tr>
                        <td colspan="2" class="empty-data">NO REGISTRÓ SI TIENE ELEMENTOS EN EL HOGAR</td>
                    </tr>
                </table>
            @endif
        </div>
        
        <!-- Sección 5: Alimentación -->
        <div class="section">
            <h2>V. ALIMENTACIÓN</h2>
            
            <h3>LUGARES DE ALIMENTACIÓN DURANTE LA SEMANA</h3>
            <table>
                <tr>
                    <th width="33%">DESAYUNO</th>
                    <th width="34%">ALMUERZO</th>
                    <th width="33%">CENA</th>
                </tr>
                <tr>
                    <td>{{ $alimentos_std['lugar_desayuno'] ?? 'N/A' }}</td>
                    <td>{{ $alimentos_std['lugar_almuerzo'] ?? 'N/A' }}</td>
                    <td>{{ $alimentos_std['lugar_cena'] ?? 'N/A' }}</td>
                </tr>
                @if( $alimentos_std['tiene_dificultades'] )
                <tr>
                    <th>¿TIENE DIFICULTADES PARA CONSEGUIR ALIMENTOS?</th> 
                    <td colspan="2">{{ $alimentos_std['dificultades_alimenticias'] ?? 'N/A' }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="section">
            <h3>DETALLES SOBRE SU ALIMENTACIÓN</h3>
            <table>
                <tr>
                    <th width="20%">PROGRAMAS DE ALIMENTACIÓN</th>
                    <td width="80%">{{ $alimentos_std['programas_alimentacion'] ?? 'N/A' }}</td>
                </tr>
                @if( $alimentos_std['alergias_alimenticias'] )
                <tr>
                    <th>ALERGIAS ALIMENTICIAS</th>
                    <td>{{ $alimentos_std['alergias_alimenticias'] ?? 'N/A' }}</td>
                </tr>
                @endif
                <tr>
                    <th>DIETA ESPECIAL</th>
                    <td>{{ $alimentos_std['dieta_especial'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>INTOLERANCIAS ALIMENTICIAS</th>
                    <td>{{ $alimentos_std['intolerancia_alimenticia'] ?? 'N/A' }}</td>
                <tr>
                @if( $alimentos_std['toma_suplementos'] )
                <tr>
                    <th>REQUIERE TOMAR SUPLEMENTOS</th>
                    <td>{{ $alimentos_std['suplemenetos_alimenticios'] ?? 'N/A' }}</td>
                </tr>
                @endif
                @if( $alimentos_std['observaciones_alimenticias'] )
                <tr>
                    <th>OTRAS OBSERVACIONES SOBRE SU ALIMENTACIÓN</th>
                    <td>{{ $alimentos_std['observaciones_alimenticias'] ?? 'N/A' }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <!-- Sección 6: Discapacidad -->
        <div class="section">
            <h2>VI. DISCAPACIDAD</h2>
            
            @if( !$programas_discapacidad['tiene_discapacidad'] )
                <h3>NO TIENE DISCAPACIDAD</h3>
            @else
                <h3>AFILIACIÓN A PROGRAMAS</h3>
                <table>
                    <tr>
                        <th width="17%">CONADIS</th>
                        <td width="17%">{{ $programas_discapacidad['codigo_conadis'] ?? 'NO' }}</td>
                        <th width="16%">OMAPED</th>
                        <td width="17%">{{ $programas_discapacidad['codigo_omaped'] ?? 'NO' }}</td>
                        <th width="17%">OTRO</th>
                        <td width="16%">{{ $programas_discapacidad['codigo_otro_programa'] ?? 'NO' }}</td>
                    </tr>
                </table>
            @endif
        </div>

        @if( isset($discapacidades) && count($discapacidades) > 0 )
        <div class="section">
            <h3>DISCAPACIDADES</h3>
            <table>
                <tr>
                    <th width="20%">DISCAPACIDAD</th>
                    <th width="80%">OBSERVACIONES</th>
                </tr>
                @foreach($discapacidades as $index => $discapacidad)
                    <tr>
                        <td>{{ $discapacidad['nomb_discapacidad'] ?? 'N/A' }}</td>
                        <td>{{ $discapacidad['observaciones'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif
        
        <!-- Sección 7: Salud -->
        <div class="section">
            <h2>VII. SALUD</h2>

            <h3>INFORMACIÓN GENERAL DE SALUD</h3>
            <table>
                <tr>
                    <th width="20%">SEGUROS DE SALUD</th>
                    <td width="80%">{{ $ficha_salud['SeguroSalud'] ?? 'NO' }}</td>
                </tr>
                <tr>
                    <th width="20%">ALERGIAS A MEDICAMENTOS</th>
                    <td>{{ $ficha_salud['AlergiaMedicamentos'] ?? 'NO' }}</td>
                </tr>
                <tr>
                    <th width="20%">OTRAS ALERGIAS</th>
                    <td>{{ $ficha_salud['AlergiaOtros'] ?? 'NO' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>DOLENCIAS Y/O ENFERMEDADES</h3>
            @if( isset($dolencias_salud) && count($dolencias_salud) > 0 )
                <table>
                    <tr>
                        <th width="20%">DOLENCIA</th>
                        <th width="80%">OBSERVACIONES</th>
                    </tr>
                    @foreach($dolencias_salud as $index => $item)
                    <tr>
                        <td>{{ $item['nomb_dolencia'] ?? 'N/A' }}</td>
                        <td>{{ $item['observaciones'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </table>
            @else
                <table>
                    <tr>
                        <td colspan="2" class="empty-data">NO SE REGISTRON DOLENCIAS NI ENFERMEDADES</td>
                    </tr>
                </table>
            @endif
        </div>

        <div class="section">
            <h3>DOSIS DE VACUNA</h3>
            @if( isset($dosis_vacuna) && count($dosis_vacuna) > 0 )
                <table>
                    <tr>
                        <th width="15%">ITEM</th>
                        <th width="35%">PANDEMIA</th>
                        <th width="20%">N° DE DOSIS</th>
                        <th width="30%">FECHA DE DOSIS</th>
                    </tr>
                    @foreach($dosis_vacuna as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['pandemia'] ?? 'N/A' }}</td>
                        <td>{{ $item['num_dosis'] ?? 'N/A' }}</td>
                        <td>{{ $item['fecha_dosis'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </table>
            @else
                <table>
                    <tr>
                        <td colspan="3" class="empty-data">NO SE REGISTRON DOSIS DE VACUNAS</td>
                    </tr>
                </table>
            @endif
        </div>
        <!-- Sección 8: Información Complementaria -->
        <div class="section">
            <h2>VIII. INFORMACIÓN COMPLEMENTARIA</h2>
            
            <h3>DEPORTES</h3>
            <table>
                <tr>
                    <th width="20%">DEPORTES</th>
                    <td width="80%">{{ $recreacion['deportes'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>¿PERTENECE A LIGA DEPORTIVA?</th>
                    <td>{{ $recreacion['liga_deportiva'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>CULTURA Y RECREACIÓN</h3>
            <table>
                <tr>
                    <th width="20%">PASATIEMPOS</th>
                    <td width="80%">{{ $recreacion['pasatiempo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>ACTIVIDADES ARTÍSTICAS</th>
                    <td>{{ $recreacion['act_artistica'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>¿PERTENECE A CLUB ARTÍSTICO?</th>
                    <td>{{ $recreacion['centro_artistico'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>RELIGIÓN</th>
                    <td>{{ $recreacion['religion'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>PSICOPEDAGÓGICO</h3>
            <table>
                <tr>
                    <th width="50%">¿RECIBE CONSULTAS PSICOLÓGICAS?</th>
                    <td width="50%">{{ $recreacion['consulta_psicologica'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>¿A QUIÉN ACUDE CUANDO TIENE PROBLEMAS EMOCIONALES?</th>
                    <td>{{ $recreacion['problemas_emocionales'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>¿CÓMO ES LA RELACIÓN CON SU FAMILIA?</th>
                    <td>{{ $recreacion['relacion_familiar'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>TRANSPORTE</h3>
            <table>
                <tr>
                    <th width="20%">MEDIOS DE TRANSPORTE MÁS USADOS</th>
                    <td width="80%">{{ $recreacion['transporte'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Firmas -->
        <div class="signature-section section">
            <div class="signature-box">
                @if($ficha_estudiante)
                    <p>Firma del Apoderado</p>
                @else
                    <p>Firma</p>
                @endif
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Firma del Responsable</p>
                <div class="signature-line"></div>
            </div>
            
            <p class="note">FECHA DE IMPRESIÓN: {{ date('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>