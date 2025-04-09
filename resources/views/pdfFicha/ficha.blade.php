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
            <td>{{ $direccion_procedencia['tipo_via'] ?? 'N/A' }}</td>
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
        
        <div class="section">
            <h2>II. ASPECTO FAMILIAR</h2>
            <table>
                <th>¿Vive su padre?</th>
                <td>SI</td>
                <th>¿Vive su padre?</th>
                <td>SI</td>             
            </table>

            <h3>Dirección domiciliaria en Moquegua</h3>
            <table> 
                <tr>
                    <th>2.1- Dirección actual del Padre</th>
                    <td>{{ $direccion_padre['tipo_via'] }}</td> 
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
                    <td>{{ $direccion_padre['nombre_via'] }}</td>   
                    <td>{{ $direccion_padre['numero_puerta'] }}</td>
                    <td>{{ $direccion_padre['block'] }}</td>
                    <td>{{ $direccion_padre['interior'] }}</td>
                    <td>{{ $direccion_padre['piso'] }}</td>
                    <td>{{ $direccion_padre['mz'] }}</td>
                    <td>{{ $direccion_padre['lote'] }}</td>
                    <td>{{ $direccion_padre['km'] }}</td>
                </tr>
            </table>
            <table>
                <th>Referencia de ubicación domiciliaria</th>
                <td>{{ $direccion_padre['referencia'] }}</td>
            </table>
        </div>
        
 
            <table> 
                <tr>
                    <th>2.1- Dirección actual de la Madre</th>
                    <td>{{ $direccion_madre['tipo_via'] }}</td> 
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
                    <td>{{ $direccion_madre['nombre_via'] }}</td>   
                    <td>{{ $direccion_madre['numero_puerta'] }}</td>
                    <td>{{ $direccion_madre['block'] }}</td>
                    <td>{{ $direccion_madre['interior'] }}</td>
                    <td>{{ $direccion_madre['piso'] }}</td>
                    <td>{{ $direccion_madre['mz'] }}</td>
                    <td>{{ $direccion_madre['lote'] }}</td>
                    <td>{{ $direccion_madre['km'] }}</td>
                </tr>
            </table>
            <table>
                <th>Referencia de ubicación domiciliaria</th>
                <td>{{ $direccion_madre['referencia'] }}</td>
            </table>
 
            <!-- Sección 6: Lugar de Nacimiento -->
             <div class="section">
                <table>
                    <tr>
                        <th>2.3. Estado civil de los padres:</th>
                        <td>{{ $est_civil_padres['estado_civil'] }}</td>
                    </tr>
                </table>

             </div>
           
             <!-- Sección 7: Estructura Familiar -->
           <div class="section">
                <table>
                    <tr>
                        <th>2.4. Estructura familiar (Incluido el estudiante)</th>
                    <table>
                        <tr>
                            <th>Nombres y Apellidos</th>
                            <th>Edad</th>
                            <th>Parentesco</th>
                            <th>Estado civil</th>
                            <th>Grado de instrucción</th>                            
                            <th>Ocupación</th>                            
                            <th>Residencia actual</th>                            
                        </tr>
                        <tr>
                            <td>MARIA FERNANDA RODRIGUEZ FERNANDEZ</td>
                            <td>32</td>
                            <td>Madre</td>
                            <td>Casada</td>
                            <td>Superior Universitaria</td>
                            <td>Ama de casa</td>
                            <td>San Antonio Z-24</td>
                        </tr>
                        <tr>    
                            <td>PEDRO ROMERO RODRIGUEZ</td>
                            <td>33</td>
                            <td>Padre</td>
                            <td>Casado</td>
                            <td>Superior Universitaria</td>
                            <td>Ing de Sistemas e Inform</td>
                            <td>San Antonio Z-24</td>
                        </tr>
                            <td>ORLANDO ROMERO SANTIVAÑEZ</td>
                            <td>33</td>
                            <td>Padre</td>
                            <td>Casado</td>
                            <td>Superior Universitaria</td>
                            <td>Ing de Sistemas e Inform</td>
                            <td>San Antonio Z-24</td>
                        </tr>
                            <td>MILAGROS ROMERO SANTIVAÑEZ</td>
                            <td>33</td>
                            <td>Padre</td>
                            <td>Casado</td>
                            <td>Superior Universitaria</td>
                            <td>Ing de Sistemas e Informa</td>
                            <td>San Antonio Z-24</td>
                        </tr>
                    </table>
                    </tr>
                </table>
             </div>
            
             <!-- Sección 8: Lugar de Nacimiento -->           
            <div class="section">
                <table>
                    <tr>
                        <th>2.5. ¿Con quién reside actualmente?:</th>
                        <td>Otros: Cuarto Alquilado</td>
                    </tr>
                </table>
            </div>

            <!-- Sección 9: Persona de contacto emergencia -->           
            <div class="section">
                <table>
                    <tr>
                        <th>2.6. Nombre de la persona y parentesco en caso de emergencia:</th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>Nombres y Apellidos</th>
                        <th>Parentesco</th>
                        <th>Teléfono fijo o celularl
                        </th>
                    </tr>
                    <tr>
                        <td>MILAGROS ROMERO SANTIVAÑEZ</td>
                        <td>mamá</td>
                        <td>990464520</td>
                    </tr>
                </table>
            </div>
            
            <!-- Sección 10: Persona de contacto emergencia --> 
            <div class="section">       
                <h2>III. ASPECTO ECONÓMICO</h2>
                </div>
                <table>
                    <tr>
                        <th>3.1. Ingreso Familiar</th>
                        <td>De S/. 951.00 a S/. 1500.00</td>
                    </tr>
                    <tr>
                        <th>3.2. Depeden Económivcamente de:</th>
                        <td>ambos padres</td>
                    </tr>
                    <tr>
                        <th>3.3. Apoyo que recibe es:</th>
                        <td>Ninguno</td>
                    </tr>
                    <tr>
                        <th>3.4. El estudiante, ¿Desempeña alguna actividad económica:</th>
                        <td>ambos padres</td>
                    </tr>
                    <tr>
                        <th>3.5. Ingreso mensual del estudiante:</th>
                        <td>-</td>
                    </tr>
                    <tr>
                        <th>3.6. Su labor es:</th>
                        <td>ambos padres</td>
                    </tr>
                    <tr>
                        <th>3.7. Horas destinadas al trabajo que realiza :</th>
                        <td>0</td>
                    </tr>
                </table>  
            
           <!-- Sección 11: Persona de contacto emergencia --> 
            <div class="section">
                <table>
                    <tr>
                        <h2>IV. ASPECTOS DE LA VIVIENDA (Donde actualmente radica el estudiante)</h2>
                        <th>4.1.La vivienda que ocupa su hogar es:</th>
                        <th>4.2 ¿Cuántos pisos tiene la vivienda que ocupa?:</th>
                        <th>4.3 Estado de la vivienda:</th>
                    </tr>   
                    <tr>
                        <td>Propia</td>
                        <td>3 pisos</td>
                        <td>terminada</td>
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
                        <td>ladrillo revestido</td>
                        <td>mayolica</td>
                        <td>concreto armado</td>
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
                        <td>Cuarto / habitacióno</td>
                        <td>1</td>
                        <td>1</td>
                    </tr>
                </table>
                
                <table>
                    <tr>
                        <th> 4.10. El abastecimiento de agua en su hogar procede de:</th>
                        <th>4.11. El baño o servicio higiénico que tiene su hogar está conectado a:</th>
                        <th>4.12. ¿Cuál es el tipo de alumbrado que tiene su hogar? (puede marcar más de una alternativa)</th>
                    </tr>
                    <tr>
                        <td>Red pública dentro de la vivienda</td>
                        <td>Red pública de desagüe</td>  
                            <td>
                                <ul>
                                    <li>Electricidad: SI</li>
                                    <li>Mechero: NO</li>
                                    <li>Vela: NO</li>
                                    <li>Panel Solar: NO</li>
                            </td>
                            </ul>
                    </tr> 
                 </table>
          
<!-- Sección 11: Persona de contacto emergencia --> 
        <div class="section">
            <table>
                    <th>4.13 Su hogar tiene:</th> 
                    <th>Opcion</th>
                    <th>Su hogar tiene</th> 
                    <th>Opcion</th>          
                    <tr>
                        <td>1. Equipo de sonido</td>
                        <td>No</td>
                        <td>8. Computadora (PC)</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>2. Televisor</td>
                        <td>Si</td>
                        <td>9. Laptop</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>3. Servicio de cable</td>
                        <td>No</td>
                        <td>10. Servicio de internet</td>
                    </tr>
                    <tr>
                        <td>4. Refrigeradora/congeladora</td>
                        <td>Si</td>
                        <td>11. Tablet
                        <td>No</td>
                        </td>
                    </tr>    
                    <tr>
                        <td>5. Cocina a gas</td>
                        <td>Si</td>
                        <td>12. Automóvil / camioneta</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>6. Teléfono fijo</td>
                        <td>No</td>
                        <td>13. Moto / mototaxi</td>
                        <td>No</td>
                    </tr>
                        <td>7. Celular</td>
                        <td>No</td>
                        <td>14. Otro</td>
                        <td>No</td>
                    </tr>
                </table> 
            </div>

            <!-- Sección 12: Persona de contacto emergencia --> 
                <div class="section">
                    <table>
                        <h2>V. ALIMENTACIÓN DEL ESTUDIANTE</h2>
                        <tr>
                        <th>5.1. ¿Dónde consume sus alimentos el estudiante? (De Lunes a viernes)</th> 
                    </table>
                    <table>
                        <tr>
                            <td>a) Desayuno</td>
                            <td>Pensión</td>
                        </tr>
                        <tr>
                            <td>b) Almuerzo</td>
                            <td>Pensión</td>
                        </tr>
                        <tr>
                            <td>c) Cena</td>
                            <td>Hogar</td>
                        </tr>
                    </table>
                        <table>
                            <tr>
                                <th>5.2. ¿Tuvo acceso al comedor universitario?</th>
                                <td>Si</td>
                            </tr>
                        </table>
                        <!-- Sección 13: Persona de contacto emergencia --> 
                        <div class="section">
                            <table>
                                <h2>VI. DISCAPACIDAD</h2>
                                <th>6.1. ¿tiene ud. limitaciones de forma permanente para?:</th>
                            </table>
                            <table>
                                <tr>
                                    <td>1. Moverse o caminar, para usar brazos o piernas</td>
                                    <td>No</td>
                                </tr>
                                <tr>
                                    <td>2. Ver, aun usando anteojos</td>
                                    <td>No</td>
                                </tr>
                                <tr>
                                    <td>3. Hablar o comunicarse, aún usando la lengua de señas u otro</td>
                                    <td>No</td>
                                </tr>
                                <tr>
                                    <td>4. Oír, aun usando audífonos</td>
                                    <td>Si</td>
                                </tr>
                                <tr>
                                    <td>5. Entender o aprender (concentrarse y recordar)s</td>
                                    <td>Si</td>
                                </tr>
                                <tr>
                                    <td>6. Relacionarse con los demás, por sus pensamientos, sentimientos, emociones o conductas</td>
                                    <td>Si</td>
                                </tr>
                    </table>
                    <table>
                        <tr>
                            <th>6.2. ¿ESTÁ REGISTRADO EN (Puede marcar las dos opciones)</th>
                        </tr>
                    </table>    
                    <table>
                        <tr>
                            <td>1.- OMAPED: NO</td>
                            <td>2.- CONADIS: NO</td>
                        </tr>
                    </table>
                </div>
            </div>
                
                         <!-- Sección 14: Persona de contacto emergencia --> 
            <div class="section">
                <table>
                    <tr>
                        <h2>VII. SALUD</h2>
                        <h3>7.1. ¿padece de alguna enfermedad crónica (puede marcar una o más opciones)</h3>
                    </tr>
                </table>    
                <table>
                    <tr>
                        <td>Asma</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Diabetes</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Epilepsia</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Artritis</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Reumatismo</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Hipertensión</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Estrés</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Otros</td>
                        <td>No</td>
                    </tr>
                </table>
             </div>            
                <table>
                    <tr>
                        <th>7.2 ¿Padece de algún tipo de alergia?</th>
                    </tr>
                </table>
                <table>
                <tr>
                    <td>A medicamentos</td>
                    <td>No</td>
                </tr> 
                <tr>
                    <td>A alimentos</td>
                    <td>No</td>
                </tr>                  
                <tr>
                    <td>Otros</td>
                    <td>No</td>
                </tr>                
                </table>
                <table>
                    <tr>
                        <th>7.3 Seguro de Salud</th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>El Sistema de prestación de Seguro de Salud al cual Ud. esta afiliado actualmente es:</th>
                        <th>¿Quién aporta las cuotas por estar afiliado?</th>
                    </tr>  
                    <tr>
                        <td>ESSALUD</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Seguro Privado de salud</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Entidad Prestadora de salud</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Seguro de Fuerzas Armadas/Policiales</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Seguro Integral de Salud</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Otro</td>
                        <td>No</td>
                    </tr>                 
                    
                </table>
 
                <!-- Sección 14: Persona de contacto emergencia --> 
               <!-- <div class="section">-->
                    <table>
                        <tr>
                            <h2>VIII. INFORMACIÓN COMPLEMENTARIA</h2>
                            <h3>Deporte</h3>
                            <th>8.1. ¿Que disciplinas deportivas practica?</th>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>Fútbol</td>
                            <td>Si</td>
                        </tr>
                        <tr>
                            <td>Vóley</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Básquet</td>
                            <td>Si</td>
                        </tr>
                        <tr>
                            <td>Natación</td>
                            <td>Si</td>
                        </tr>
                        <tr>
                            <td>Otros</td>
                            <td>No</td>
                        </tr>
                    </table>
                    <table>
                    <tr>
                        <th style="width: 512px;">8.2 ¿Has participado o participaras en un Club, Liga, o Federación Deportiva?</th>
                        <td>Si</td>
                    </tr>
                    </table>
                    <table>
                    <tr>  
                    <th>CULTURA Y RECREACIÓN</th>
                    </tr> 
                    </table>
                    <table>
                    <tr>  
                        <th>8.3 ¿Que Actividad Artística Prácticas?</th>
                    </tr> 
                    </table>           
                    <table>
                 <tr>     
                        <td>Danza</td>
                        <td>Si</td>
                    </tr>
                    <tr>     
                        <td>Teatro</td>
                        <td>Si</td>
                    </tr>
                    <tr>     
                        <td>Música</td>
                        <td>Si</td>
                    </tr>
                    <tr>     
                        <td>Natación</td>
                        <td>Si</td>
                    </tr>          
                    </table>
                    <table>
                    <tr>
                        <th>8.4 ¿Has formado o formas parte de un Centro Artístico o Cultural?</th>
                        <td>No</td>
                    </tr>
                    </table>
        
                    <table>
                    <tr>
                        <th style="width: 512px;">8.5 ¿Qué religión profesas?</th>
                        <td>Católica</td>
                    </tr>
                    </table>
                    <table>
                    <th>8.6 ¿Qué actividades realizas como pasatiempo?</th>
                    </table>
                    <table>
                    <tr>
                        <td>Cine</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Lectura</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Escuchar música</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Videojuegos</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Juegos online</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Reuniones con amigos</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Pasear</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Otros</td>
                        <td>Si</td>
                    </tr>
                    </table>
                    <table>
                        <tr>
                            <th>PSICOPEDAGÓGICO</th>
                        </tr>
                    </table>    
                    <table>
                        <tr>
                            <th>8.7 ¿Has asistido alguna vez a una consulta Psicológica?</th>
                            <td>Si, cuando postule</td>
                        </tr>
                        </table>
                    <table>
                        <tr>
                            <th>8.8 ¿A quién acudes cuando tienes un problema emocional?</th>
                        </tr>
                        </table>
                    <table>
                        <tr>
                            <td>Padre</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Madre</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Hermanos</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Amigos</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Tutor</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Psicólogo</td>
                            <td>No</td>
                        </tr>
                        <tr>
                            <td>Otros</td>
                            <td>No</td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th>8.9 ¿Cómo califica Ud. su relación con sus padres o familiares? </th>
                            <td>Buena</td>
                        </tr>
                    </table>
                <table>
                    <tr>
                        <th>TRANSPORTE</th>
                    </tr>
                    <tr>
                        <th>8.10 ¿Qué temas te gustaria abordar para mejorar tu desarrollo personal? </th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Inteligencia Emocional</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Habilidades Socioemocionales</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Control de las emociones</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Resilencia</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Autoestima</td>
                        <td>Si</td>
                    </tr>
                    <tr>
                        <td>Otros</td>
                        <td>No</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>8.11 ¿Cuál es el medio de transporte que mas que mas utilizas? </th>
                        <td>Transporte Urbano</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>8.12 ¿Cuánto gastas aproximadamente en pasajes para asistir a diario a la Universidad? </th>
                        <td>5 soles o menos</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>8.13 ¿Qué tan seguidp utilizas el transporte de la UNAM?</th>
                        <td>Tres o cuatro veces a la semana</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <h2>IX. CUESTIONARIO</h2>
                    </tr>
                    <tr>
                        <th>9.1 ¿Presenta usted alguna enfermedad crónica no transmosible?</th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>Diabetes</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Hipertensión</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Obesidad</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Cáncer</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Otros</td>
                        <td>No</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th>9.2 ¿Recibió la vacuna contra el COVID-19?</th>
                        <td>Si, 3era dosis</td>
                    </tr>
                </table>
                <table>
                    <th>9.3 ¿Tuvo usted algún familiar que enfermo gravemente y falleció con COVID-19?</th>
                </table>
                <table>
                    <tr>
                        <td>Madre</td>
                        <td>No</td>
                    </tr>
                 <tr>
                    <td>Padre</td>
                    <td>No</td>
                 </tr>
                 <tr>
                    <td>Hermano(a)</td>
                    <td>No</td>
                 </tr>
                </table>
                <!--</div>-->
    </div>
</body>
</html>




