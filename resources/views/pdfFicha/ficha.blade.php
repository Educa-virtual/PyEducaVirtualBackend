<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Socioeconómica</title>
    <link rel="stylesheet" href="{{ asset('css/ficha.css') }}">
</head>
<body>
    <div class="container">
        <h1 class="title">FICHA SOCIOECONÓMICA - DECLARACIÓN JURADA</h1>

        <!-- Sección 1: Dirección en Moquegua -->
        <div class="section">
            <h2>I. DATOS GENERALES DEL ESTUDIANTE</h2>
            <h3>Dirección Domiciliaria en Moquegua</h3>
            <table>
            <th>Dirección actual</th>
            <td>{{ $direccion_domiciliaria['tipo_via'] ?? 'N/A' }}</td>

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
                    <th>Provincia::</th>          
                    <th>Distrito:</th>          
                </tr> 
                <tr>  
                    <td>{{ $direccion_domiciliaria['departamento'] }}</td>          
                    <td>{{ $direccion_domiciliaria['provincia'] }}</td>          
                    <td>{{ $direccion_domiciliaria['distrito'] ?? 'N/A'}}</td>   
                </tr>
                <table>
                    <th>Referencia:</th> 
                    <td>{{ $direccion_domiciliaria['referencia']?? 'N/A' }}</td>              
                </table>
            </table>
        </div>

         <!-- Sección 3: Datos del Estudiante -->
        <div class="section">
            <table>
                <tr>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombres</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['apellido_paterno'] }}</td>
                    <td>{{ $estudiante['apellido_materno'] }}</td>
                    <td>{{ $estudiante['nombres'] }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>DNI</th>
                    <th>Fecha Nacimiento</th>
                    <th>Sexo</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['dni'] }}</td>
                    <td>{{ $estudiante['fecha_nacimiento'] }}</td>
                    <td>{{ $estudiante['sexo'] }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Estado Civil</th>
                    <th>N° de Hijos</th>
                </tr>
                <tr>
                    <td>{{ $estudiante['estado_civil'] }}</td>
                    <td>{{ $estudiante['num_hijos'] }}</td>
                </tr>
            </table>
        </div>

        <!-- Sección 4: Lugar de Nacimiento -->
        <div class="section">
            <table>
                <th>Lugar de Nacimiento</th>
            </table>
            <table>
                    <tr>
                        <th>País</th>
                        <th>Departamento</th>
                        <th>Provincia</th>
                        <th>Distrito</th>
                    </tr>
                    <tr>
                        <td>{{ $nacimiento['pais'] }}</td>
                        <td>{{ $nacimiento['departamento'] }}</td>
                        <td>{{ $nacimiento['provincia'] }}</td>
                        <td>{{ $nacimiento['distrito'] }}</td>
                    </tr>
                </table>
          </div>
      
         <!-- Sección 6: Lugar de Nacimiento -->
        
            <h2>II. ASPECTO FAMILIAR</h2>
            <table>
                <th>¿Vive su padre?</th>
                <td>{{ $direccion_domiciliaria['vive_padre'] }}</td>
                <th>¿Vive su madre?</th>
                <td>{{ $direccion_domiciliaria['vive_madre'] }}</td> 
            </table>
 
            <!-- Sección 7: Estructura Familiar -->
            <div class="section">
                <table>
                    <tr>
                        <th>2.4. Estructura familiar (Incluido el estudiante)</th>
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
                        
                        <tr>
                            @foreach ($familiares as $familiar)
                                <tr>
                                    <td>{{ $familiar['apellido_paterno'] }} {{ $familiar['apellido_materno'] }} {{ $familiar['nombres'] }} </td>
                                    <td>{{ $familiar['edad'] }}</td>
                                    <td>{{ $familiar['tipo_familiar'] }}</td>
                                    <td>{{ $familiar['estado_civil'] }}</td>
                                    <td>{{ $familiar['grado_instruccion'] }}</td>
                                    <td>{{ $familiar['ocupacion'] }}</td>
                                    <td>{{ $familiar['residencia_actual'] }}</td>
                                </tr>
                            @endforeach
                        </tr>
                     </table>
                    </tr>
                </table>
             </div>

           <!-- Sección 10: Aspecto econimico --> 
           <div class="section">       
                <h2>III. ASPECTO ECONÓMICO</h2>
                </div>
                <table>
                    <tr>
                        <th>3.1. Ingreso Familiar</th>
                        <td>{{ $aspecto_economico['rango_sueldo'] }}</td>
                        <!--<td>De S/. 951.00 a S/. 1500.00</td>-->
                    </tr>
                    <tr>
                        <th>3.2. Depeden Económivcamente de:</th>
                        <td>{{ $aspecto_economico['depende_economicamente_de'] }}</td>
                    </tr>
                    <tr>
                        <th>3.3. Apoyo que recibe es:</th>
                        <td>{{ $aspecto_economico['tipo_apoyo_economico'] }}</td>
                    </tr>
                    <tr>
                        <th>3.4. El estudiante, ¿Desempeña alguna actividad económica:</th>
                        <td>{{ $aspecto_economico['actividad_ingreso'] }}</td>
                    </tr>
                    <tr>
                        <th>3.5. Ingreso mensual del estudiante:</th>
                        <td>{{ $aspecto_economico['aporte_estudiante'] }}</td>
                    </tr>
                    <tr>
                        <th>3.6. Su labor es:</th>
                        <td>{{ $aspecto_economico['horas_trabajo'] }}</td>
                    </tr>
                    <tr>
                        <th>3.7. Horas destinadas al trabajo que realiza :</th>
                        <td>{{ $aspecto_economico['jornada_trabajo'] }}</td>
                    </tr>
                </table>  
   

          <!-- Sección 11: Aspecto de la vivienda --> 
          <div class="section">
                <table>
                    <tr>
                        <h2>IV. ASPECTOS DE LA VIVIENDA (Donde actualmente radica el estudiante)</h2>
                        <th>4.1.La vivienda que ocupa su hogar es:</th>
                        <th>4.2 ¿Cuántos pisos tiene la vivienda que ocupa?:</th>
                        <th>4.3 Estado de la vivienda:</th>
                    </tr>   
                    <tr>
                    <td>{{ $aspecto_vivienda['vivienda_es'] }}</td>
                    <td>{{ $aspecto_vivienda['npisos'] }}</td>
                    <td>{{ $aspecto_vivienda['estado'] }}</td>
                    </tr>
                </table>
            </div>

          <!-- Sección 11: Persona de contacto emergencia --> 
            <div class="section">
                <table>
                    <h3>El material predominante en:</h3>
                    <tr>
                        <th>4.4 Las paredes exteriores de la vivienda es:</th>
                        <th>4.5 Los pisos de la vivienda es:</th>
                        <th>4.6 Los techos de la vivienda es:</th>
                    </tr>
                    <tr>
                        <td>{{ $aspecto_vivienda['mat_pred_pared'] }}</td>
                        <td>{{ $aspecto_vivienda['mat_piso'] }}</td>
                        <td>{{ $aspecto_vivienda['mat_techo'] }}</td>
                    </tr>
                </table>
             </div>
                <table>
                    <tr>
                        <th>4.7 Tipo de vivienda:</th>
                        <th>4.8. Sin contar baño, cocina, pasadizos ni garaje, ¿cuántos ambientes en total tiene la vivienda?</th>
                        <th>4.9. ¿Cuántas habitaciones se usan exclusivamente para dormir?</th>
                    </tr>
                    <tr>
                        <td>{{ $aspecto_vivienda['tipo_vivienda'] }}</td>
                        <td>{{ $aspecto_vivienda['nro_ambientes'] }}</td>
                        <td>{{ $aspecto_vivienda['nro_habitaciones'] }}</td>
                    </tr>
                </table>
                
                <table>
                    <tr>
                        <th> 4.10. El abastecimiento de agua en su hogar procede de:</th>
                        <th>4.11. El baño o servicio higiénico que tiene su hogar está conectado a:</th>
                        <th>4.12. ¿Cuál es el tipo de alumbrado que tiene su hogar? (puede marcar más de una alternativa)</th>
                    </tr>
                    <tr>
                        <td>{{ $aspecto_vivienda['tipo_servicio'] }}</td>
                        <td>{{ $aspecto_vivienda['tipo_sshh'] }}</td>
                        <td>{{ $aspecto_vivienda['tipo_alumbrado'] }}</td>
                    </tr> 
                 </table>

                <!-- Sección 11: Equipamiento --> 
                <div class="section">
                    <h2>IV. EQUIPAMIENTO</h2>
                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>4.13 Su hogar tiene:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipamiento as $equipos)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>    
                                    <td>{{ $equipos['electrodm_hogar'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Sección 12: Alimentación del estudiante --> 
                <div class="section">
                    <h2>V. ALIMENTACIÓN DEL ESTUDIANTE</h2>

                    <table border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th colspan="2">5.1. ¿Dónde consume sus alimentos el estudiante? (De lunes a viernes)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>a) Desayuno</td>
                                <td>{{ $alimentos_std['lugar_desayuno'] }}</td>
                            </tr>
                            <tr>
                                <td>b) Almuerzo</td>
                                <td>{{ $alimentos_std['lugar_almuerzo'] }}</td>
                            </tr>
                            <tr>
                                <td>c) Cena</td>
                                <td>{{ $alimentos_std['lugar_ceba'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <br>

                    <table border="1" cellspacing="0" cellpadding="5">
                        <tr>
                            <th>5.2. ¿Tuvo acceso al comedor universitario?</th>
                            <td>Si</td>
                        </tr>
                    </table>
                </div>



            <!-- Sección 13: Discapacidad --> 
            <div class="section">
           <h2>VI. DISCAPACIDAD</h2>

            <table border="1" cellpadding="6" cellspacing="0" width="100%">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th>N°</th>
                            <th>6.1. ¿Tiene usted limitaciones de forma permanente para?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pers_discapacidad as $index => $discapacidad)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $discapacidad['nomb_discapacidad'] }}</td>
        
                            </tr>
                        @endforeach
                    </tbody>
            </table>
            <table>
                <tr>
                    <th>6.1. ¿Esta registrado en:?</th>
                </tr>
            </table>
             <table>
                <tr>
                    <td>¿Está en OMAPED?</td> 
                    <td>{{ $discapacidad['esta_en_omaped'] }}</td>
                    <td>¿Está en CONADIS?</td>
                    <td>{{ $discapacidad['esta_en_conadis'] }}</td>
                </tr>
        </table>
    </div>

    <div class="section">
    <h2>VII. SALUD</h2>

    <h3>7.1. ¿Padece de alguna enfermedad crónica? (puede marcar una o más opciones)</h3>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width: 10%;">N°</th>
                <th>Enfermedad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pers_salud as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item['enfermedad_nomb'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No registra enfermedades crónicas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <h3>7.2. ¿Padece de algún tipo de alergia?</h3>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Tipo de Alergia</th>
                <th>Respuesta</th>
            </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
</div>

<table>
    <tr>
        <th>El Sistema de prestación de Seguro de Salud al cual Ud. está afiliado actualmente es:</th>
        <th>¿Quién aporta las cuotas por estar afiliado?</th>
    </tr>
    @forelse($pers_seguros as $item)
        <tr>
            <td>{{ $item['seguro_salud'] }}</td>
            <td>{{ $item['seguro_aportacion'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No se registran seguros de salud.</td>
        </tr>
    @endforelse
</table>

<div class="section">
    <h2>VIII. INFORMACIÓN COMPLEMENTARIA</h2>
    <h3>Deporte</h3>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>8.1. ¿Qué disciplinas deportivas practica?</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pers_deportes as $item)
                <tr>
                    <td>{{ $item['deporte_nombre'] }}</td>
                </tr>
            @empty
                <tr>
                    <td>No se registran disciplinas deportivas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>8.2. ¿Pertenece a una liga deportiva?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $liga_deportiva ?? 'NO' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<table>
    <tr>
        <th colspan="2">CULTURA Y RECREACIÓN</th>
    </tr>
</table>

<table>
    <tr>
        <th>8.3 ¿Qué Actividad Artística Prácticas?</th>
    </tr>
</table>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Actividad Artística</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pers_artes as $item)
            <tr>
                <td>{{ $item['pasatiempo_artistico'] }}</td>
            </tr>
        @empty
            <tr>
                <td>No se registran actividades artísticas.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>

        <table>
            <tr>
                <th>¿Pertenece a un centro artístico?</th>
            </tr>
            <tr>
                <td>{{ $centro_artistico ?? 'NO' }}</td>
            </tr>
        </table>

            <table>
                    <tr>
                        <th style="width: 512px;">8.5 ¿Qué religión profesas?</th>
                        <td>Católica</td>
                    </tr>


            <table>
            <tr>
                <th>8.6 ¿Qué actividades realizas como pasatiempo?</th>
            </tr>
        </table>

        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tbody>
                @forelse($pers_pasatiempos as $item)
                    <tr>
                        <td>{{ $item['pasatiempo_nombre'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>No se registran pasatiempos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table>
            <tr>
                <th>PSICOPEDAGÓGICO</th>
            </tr>
        </table>    

 <table>
    <tr>
        <th>8.7 ¿Has asistido alguna vez a una consulta Psicológica?</th>
        <td>
            {{ $datos['asist_consulta'][0] ?? 'No hay información' }}
        </td>
    </tr>
</table>

        <!-- <table>
            <thead>
                <tr>
                    <th>8.8 ¿A quién acudes cuando tienes un problema emocional? </th>
                </tr>
            </thead>
                <td>
                    @if(!empty($datos['pers_psicopedagogico'][0]['familiares']))
                        {{ implode(', ', $datos['pers_psicopedagogico'][0]['familiares']) }}
                    @else
                        No hay información
                    @endif
                </td>
        </table> -->



        <table>
            <tr>
                <th>TRANSPORTE</th>
            </tr>
            <tr>
                <th>8.10 ¿Qué temas te gustaría abordar para mejorar tu desarrollo personal?</th>
            </tr>

            @if (!empty($datos['medio_transporte']))
                @foreach ($datos['medio_transporte'] as $mtransporte)
                    <tr>
                        <td>
                            <strong>Transporte:</strong> {{ $mtransporte['transporte_nombre'] }} <br>
                            <strong>Dolencia:</strong> {{ $mtransporte['dolencia_nombre'] }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>No se encontraron registros de transporte asociados.</td>
                </tr>
            @endif
        </table>

    </body>
</html>




