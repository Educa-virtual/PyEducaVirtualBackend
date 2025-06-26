<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Socioeconómica</title>
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
                <th>Código del Estudiante:</th>
                <td>{{ $estudiante['codigo_alumno'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Número de Celular:</th>
                <td>{{ $estudiante['num_telefono'] ?? 'N/A' }}</td>
            </tr>
        </table>

        <h1 class="title">FICHA SOCIOECONÓMICA - DECLARACIÓN JURADA</h1>

        <!-- Sección 1: Dirección en Moquegua -->
        <div class="section">
            <h2>I. DATOS GENERALES DEL ESTUDIANTE</h2>
            <h3>Dirección del Estudiante</h3>
            
            <table>
                <tr>
                    <th>Dirección actual</th>
                    <td>{{ $direccion_domiciliaria['tipo_via'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>Nombre de Vía</th>
                    <th>N° de Puerta</th>
                    <th>Block</th>
                    <th>Interior</th>
                    <th>Piso</th>
                    <th>Mz</th>
                    <th>Lote</th>
                    <th>Km</th>
                </tr>
                <tr>
                    <td>{{ $direccion_domiciliaria['nombre_via'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['numero_puerta'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['block'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['interior'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['piso'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['mz'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['lote'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['km'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>Departamento:</th>
                    <th>Provincia:</th>
                    <th>Distrito:</th>
                </tr>
                <tr>
                    <td>{{ $direccion_domiciliaria['departamento'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['provincia'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_domiciliaria['distrito'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th colspan="3">Referencia:</th>
                </tr>
                <tr>
                    <td colspan="3">{{ $direccion_domiciliaria['referencia'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <!-- Datos del Estudiante -->
        <div class="section">
            <h3>Datos Personales</h3>
            <table>
                <tr>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombres</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['apellido_paterno'] ?? 'N/A' }}</td>
                    <td>{{ $estudiante['apellido_materno'] ?? 'N/A' }}</td>
                    <td>{{ $estudiante['nombres'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>DNI</th>
                    <th>Fecha Nacimiento</th>
                    <th>Sexo</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['dni'] ?? 'N/A' }}</td>
                    <td>{{ $estudiante['fecha_nacimiento'] ?? 'N/A' }}</td>
                    <td>{{ $estudiante['sexo'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>Estado Civil</th>
                    <th>N° de Hijos</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['estado_civil'] ?? 'N/A' }}</td>
                    <td>{{ $estudiante['num_hijos'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>Lugar de Nacimiento</h3>
            <table>
                <tr>
                    <th>País</th>
                    <th>Departamento</th>
                    <th>Provincia</th>
                    <th>Distrito</th>
                </tr>
                <tr>
                    <td>{{ $nacimiento['pais'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['departamento'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['provincia'] ?? 'N/A' }}</td>
                    <td>{{ $nacimiento['distrito'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>Institución Educativa</h3>
            <table>
                <tr>
                    <th>Nombre de la Institución</th>
                    <th>Tipo de Sector</th>
                </tr>
                @if(isset($ieducativas) && count($ieducativas) > 0)
                    @foreach ($ieducativas as $ie)
                    <tr>
                        <td>{{ $ie['nombre_iedu'] ?? 'N/A' }}</td>
                        <td>{{ $ie['tipo_sector'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="empty-data">No hay instituciones registradas</td>
                    </tr>
                @endif
            </table>
        </div>
        
        <!-- Sección 2: Aspecto Familiar -->
        <div class="section">
            <h2>II. ASPECTO FAMILIAR</h2>
            <table>
                <tr>
                    <th>¿Vive su padre?</th>
                    <td>{{ $direccion_domiciliaria['vive_padre'] ?? 'N/A' }}</td>
                    <th>¿Vive su madre?</th>
                    <td>{{ $direccion_domiciliaria['vive_madre'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            @php
            $direccion_padre = $direc_familiares[0] ?? [];
            $direccion_madre = $direc_familiares[1] ?? [];
            @endphp

            <h3>Dirección actual del Padre</h3>
            <table>
                <tr>
                    <th>Tipo de vía</th>
                    <td colspan="7">{{ $direccion_padre['tipo_via'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nombre de Vía</th>
                    <th>N° de Puerta</th>
                    <th>Block</th>
                    <th>Interior</th>
                    <th>Piso</th>
                    <th>Mz</th>
                    <th>Lote</th>
                    <th>Km</th>
                </tr>
                <tr>
                    <td>{{ $direccion_padre['Nombre_via'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionNroPuerta'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionBlock'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionInterior'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionPiso'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionManzana'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionLote'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_padre['DireccionKm'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th colspan="2">Referencia de ubicación domiciliaria</th>
                    <td colspan="6">{{ $direccion_padre['DireccionReferencia'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>Dirección actual de la Madre</h3>
            <table>
                <tr>
                    <th>Tipo de vía</th>
                    <td colspan="7">{{ $direccion_madre['tipo_via'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Nombre de Vía</th>
                    <th>N° de Puerta</th>
                    <th>Block</th>
                    <th>Interior</th>
                    <th>Piso</th>
                    <th>Mz</th>
                    <th>Lote</th>
                    <th>Km</th>
                </tr>
                <tr>
                    <td>{{ $direccion_madre['Nombre_via'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionNroPuerta'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionBlock'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionInterior'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionPiso'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionManzana'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionLote'] ?? 'N/A' }}</td>
                    <td>{{ $direccion_madre['DireccionKm'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th colspan="2">Referencia de ubicación domiciliaria</th>
                    <td colspan="6">{{ $direccion_madre['DireccionReferencia'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>Estructura familiar (Incluido el estudiante)</h3>
            <table>
                <tr>
                    <th>Nombres</th>
                    <th>Edad</th>
                    <th>Parentesco</th>
                    <th>Estado civil</th>
                    <th>Grado de instrucción</th>
                    <th>Ocupación</th>
                    <th>Residencia actual</th>
                </tr>
                @if(isset($familiares) && count($familiares) > 0)
                    @foreach ($familiares as $familiar)
                    <tr>
                        <td>{{ $familiar['apellido_paterno'] ?? '' }} {{ $familiar['apellido_materno'] ?? '' }} {{ $familiar['nombres'] ?? '' }}</td>
                        <td>{{ $familiar['edad'] ?? 'N/A' }}</td>
                        <td>{{ $familiar['tipo_familiar'] ?? 'N/A' }}</td>
                        <td>{{ $familiar['estado_civil'] ?? 'N/A' }}</td>
                        <td>{{ $familiar['grado_instruccion'] ?? 'N/A' }}</td>
                        <td>{{ $familiar['ocupacion'] ?? 'N/A' }}</td>
                        <td>{{ $familiar['departamento'] ?? '' }} / {{ $familiar['provincia'] ?? '' }} / {{ $familiar['distrito'] ?? '' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="empty-data">No hay familiares registrados</td>
                    </tr>
                @endif
            </table>
        </div>
        
        <!-- Sección 3: Aspecto Económico -->
        <div class="section">
            <h2>III. ASPECTO ECONÓMICO</h2>
            <table>
                <tr>
                    <th>3.1. Ingreso Familiar</th>
                    <td>{{ $aspecto_economico['rango_sueldo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.2. Depende Económicamente de:</th>
                    <td>{{ $aspecto_economico['depende_economicamente_de'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.3. Apoyo que recibe es:</th>
                    <td>{{ $aspecto_economico['tipo_apoyo_economico'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.4. ¿Desempeña alguna actividad económica?</th>
                    <td>{{ $aspecto_economico['actividad_ingreso'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.5. Ingreso mensual del estudiante:</th>
                    <td>{{ $aspecto_economico['aporte_estudiante'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.6. Su labor es:</th>
                    <td>{{ $aspecto_economico['horas_trabajo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>3.7. Horas destinadas al trabajo:</th>
                    <td>{{ $aspecto_economico['jornada_trabajo'] ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Sección 4: Aspectos de la Vivienda -->
        <div class="section">
            <h2>IV. ASPECTOS DE LA VIVIENDA (Donde actualmente radica el estudiante)</h2>
            
            <table>
                <tr>
                    <th>4.1. La vivienda que ocupa su hogar es:</th>
                    <th>4.2. ¿Cuántos pisos tiene la vivienda?</th>
                    <th>4.3. Estado de la vivienda:</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['vivienda_es'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['npisos'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['estado'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>Material predominante</h3>
            <table>
                <tr>
                    <th>4.4. Paredes exteriores</th>
                    <th>4.5. Pisos</th>
                    <th>4.6. Techos</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['mat_pred_pared'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['mat_piso'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['mat_techo'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>4.7. Tipo de vivienda:</th>
                    <th>4.8. Ambientes (sin contar baño, cocina, pasadizos ni garaje):</th>
                    <th>4.9. Habitaciones para dormir:</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['tipo_vivienda'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['nro_ambientes'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['nro_habitaciones'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>4.10. Abastecimiento de agua:</th>
                    <th>4.11. Servicio higiénico conectado a:</th>
                    <th>4.12. Tipo de alumbrado:</th>
                </tr>
                <tr>
                    <td>{{ $aspecto_vivienda['tipo_servicio'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['tipo_sshh'] ?? 'N/A' }}</td>
                    <td>{{ $aspecto_vivienda['tipo_alumbrado'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <h3>4.13 Su hogar tiene:</h3>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Equipamiento</th>
                </tr>
                @if(isset($equipamiento) && count($equipamiento) > 0)
                    @foreach ($equipamiento as $equipos)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $equipos['electrodm_hogar'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="empty-data">No hay equipamiento registrado</td>
                    </tr>
                @endif
            </table>
        </div>
        
        <!-- Sección 5: Alimentación -->
        <div class="section">
            <h2>V. ALIMENTACIÓN DEL ESTUDIANTE</h2>
            
            <h3>5.1. ¿Dónde consume sus alimentos? (De lunes a viernes)</h3>
            <table>
                <tr>
                    <th>Desayuno</th>
                    <td>{{ $alimentos_std['lugar_desayuno'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Almuerzo</th>
                    <td>{{ $alimentos_std['lugar_almuerzo'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Cena</th>
                    <td>{{ $alimentos_std['lugar_ceba'] ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>5.2. ¿Tuvo acceso al comedor universitario?</th>
                    <td>{{ $acceso_comedor ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Sección 6: Discapacidad -->
        <div class="section">
            <h2>VI. DISCAPACIDAD</h2>
            
            <h3>6.1. ¿Tiene usted limitaciones de forma permanente?</h3>
            <table>
                <tr>
                    <th>N°</th>
                    <th>Limitación</th>
                </tr>
                @if(isset($pers_discapacidad) && count($pers_discapacidad) > 0)
                    @foreach($pers_discapacidad as $index => $discapacidad)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $discapacidad['nomb_discapacidad'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="empty-data">No se registran discapacidades</td>
                    </tr>
                @endif
            </table>
            
            <table>
                <tr>
                    <th>6.2. ¿Está registrado en OMAPED?</th>
                    <td>{{ $discapacidad['esta_en_omaped'] ?? 'No' }}</td>
                    <th>¿Está registrado en CONADIS?</th>
                    <td>{{ $discapacidad['esta_en_conadis'] ?? 'No' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Sección 7: Salud -->
        <div class="section">
            <h2>VII. SALUD</h2>
            
            <h3>7.1. ¿Padece de alguna enfermedad crónica?</h3>
            <table>
                <tr>
                    <th>N°</th>
                    <th>Enfermedad</th>
                </tr>
                @if(isset($pers_salud) && count($pers_salud) > 0)
                    @foreach($pers_salud as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['enfermedad_nomb'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="empty-data">No registra enfermedades crónicas</td>
                    </tr>
                @endif
            </table>
            
            <h3>7.2. ¿Padece de algún tipo de alergia?</h3>
            <table>
                <tr>
                    <th>Tipo de Alergia</th>
                    <th>Respuesta</th>
                </tr>
                <tr>
                    <td>A medicamentos</td>
                    <td>{{ $alergias['AlergiaMedicamentos'] ?? 'No' }}</td>
                </tr>
                <tr>
                    <td>A alimentos</td>
                    <td>{{ $alergias['AlergiaAlimentos'] ?? 'No' }}</td>
                </tr>
                <tr>
                    <td>Otros</td>
                    <td>{{ $alergias['AlergiaOtros'] ?? 'No' }}</td>
                </tr>
            </table>
            
            <h3>Sistema de prestación de salud</h3>
            <table>
                <tr>
                    <th>Seguro de Salud</th>
                    <th>¿Quién aporta las cuotas?</th>
                </tr>
                @if(isset($seg_salud) && count($seg_salud) > 0)
                    @foreach($seg_salud as $item)
                    <tr>
                        <td>{{ $item['seguro_salud'] ?? 'N/A' }}</td>
                        <td>{{ $item['tip_aporte'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="empty-data">No se registran seguros de salud</td>
                    </tr>
                @endif
            </table>
        </div>
        
        <!-- Sección 8: Información Complementaria -->
        <div class="section">
            <h2>VIII. INFORMACIÓN COMPLEMENTARIA</h2>
            
            <h3>DEPORTE</h3>
            <table>
                <tr>
                    <th>8.1. ¿Qué disciplinas deportivas practica?</th>
                </tr>
                @if(isset($pers_deportes) && count($pers_deportes) > 0)
                    @foreach($pers_deportes as $item)
                    <tr>
                        <td>{{ $item['deporte_nombre'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran disciplinas deportivas</td>
                    </tr>
                @endif
            </table>
            
            <table>
                <tr>
                    <th>8.2. ¿Pertenece a una liga deportiva?</th>
                    <td>{{ $liga_deportiva ?? 'No' }}</td>
                </tr>
            </table>
            
            <h3>CULTURA Y RECREACIÓN</h3>
            <table>
                <tr>
                    <th>8.3. ¿Qué Actividad Artística Prácticas?</th>
                </tr>
                @if(isset($pers_artes) && count($pers_artes) > 0)
                    @foreach($pers_artes as $item)
                    <tr>
                        <td>{{ $item['pasatiempo_artistico'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran actividades artísticas</td>
                    </tr>
                @endif
            </table>
            
            <table>
                <tr>
                    <th>8.4. ¿Pertenece a un centro artístico?</th>
                    <td>{{ $centro_artistico ?? 'No' }}</td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>8.5. ¿Qué religión profesas?</th>
                    <td>
                        @if(isset($religiones) && count($religiones) > 0)
                            @foreach($religiones as $item)
                                {{ $item['religion_nombre'] ?? '' }}
                            @endforeach
                        @else
                            <span class="empty-data">No se registra religión</span>
                        @endif
                    </td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>8.6. ¿Qué actividades realizas como pasatiempo?</th>
                </tr>
                @if(isset($pers_pasatiempos) && count($pers_pasatiempos) > 0)
                    @foreach($pers_pasatiempos as $item)
                    <tr>
                        <td>{{ $item['pasatiempo_nombre'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran pasatiempos</td>
                    </tr>
                @endif
            </table>
            
            <h3>PSICOPEDAGÓGICO</h3>
            <table>
                <tr>
                    <th>8.7. ¿Has asistido alguna vez a una consulta Psicológica?</th>
                    <td>
                        @if(isset($asist_consulta) && count($asist_consulta) > 0)
                            @foreach($asist_consulta as $item)
                                {{ $item['consulta_psicolo'] ?? 'No' }}
                            @endforeach
                        @else
                            No
                        @endif
                    </td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <th>8.8. ¿A quién acudes cuando tienes un problema emocional?</th>
                </tr>
                @if(isset($fam_acompañantes) && count($fam_acompañantes) > 0)
                    @foreach($fam_acompañantes as $item)
                    <tr>
                        <td>{{ $item['nomb_acompañantes'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran acompañantes</td>
                    </tr>
                @endif
            </table>
            
            <h3>TRANSPORTE</h3>
            <table>
                <tr>
                    <th>8.9. ¿Qué temas te gustaría abordar para mejorar tu desarrollo personal?</th>
                    <td>Inteligencia Emocional</td>
                </tr>
                <tr>
                    <th>8.10. ¿Cuál es el medio de transporte que más utilizas?</th>
                    <td>
                        @if(isset($medio_transporte) && count($medio_transporte) > 0)
                            @foreach($medio_transporte as $item)
                                {{ $item['transporte_nombre'] ?? '' }}
                            @endforeach
                        @else
                            <span class="empty-data">No se registra medio de transporte</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Sección 9: Cuestionario -->
        <div class="section">
            <h2>IX. CUESTIONARIO</h2>
            
            <table>
                <tr>
                    <th>9.1. ¿Presenta usted alguna enfermedad crónica no transmisible?</th>
                </tr>
                @if(isset($enfermdad_cronic) && count($enfermdad_cronic) > 0)
                    @foreach($enfermdad_cronic as $item)
                    <tr>
                        <td>{{ $item['enfermedad'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran enfermedades</td>
                    </tr>
                @endif
            </table>
            
            <table>
                <tr>
                    <th>9.2. ¿Recibió la vacuna contra el COVID-19?</th>
                </tr>
                @if(isset($dosis_vacuna) && count($dosis_vacuna) > 0)
                    @foreach($dosis_vacuna as $item)
                    <tr>
                        <td>{{ $item['dosis_vacun'] ?? '' }} Dosis</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="empty-data">No se registran vacunas</td>
                    </tr>
                @endif
            </table>
        </div>
        
        <!-- Firmas -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Firma del Estudiante</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Firma del Responsable</p>
                <div class="signature-line"></div>
            </div>
            
            <p class="note">Fecha de registro: {{ date('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>