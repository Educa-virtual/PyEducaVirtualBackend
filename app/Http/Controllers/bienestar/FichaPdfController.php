<?php
namespace App\Http\Controllers\bienestar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para usar consultas SQL crudas

class FichaPdfController extends Controller
{
    public function mostrarFichaPdf()
    {
        $id = 474; // o el ID que recibas dinámicamente
        $anio = 2025;

        // Consulta 1: Datos generales y de dirección
        $datosGenerales = DB::selectOne("
            SELECT
                tpv.cTipoViaNombre,
                fdg.cFichaDGDireccionNombreVia,
                fdg.cFichaDGDireccionNroPuerta,
                fdg.cFichaDGDireccionBlock,
                fdg.cFichaDGDirecionInterior,
                fdg.cFichaDGDirecionPiso,
                fdg.cFichaDGDireccionManzana,
                fdg.cFichaDGDireccionLote,
                fdg.cFichaDGDireccionKm,
                dep.cDptoNombre,
                prv.cPrvnNombre,
                dstr.cDsttNombre,
                fdg.cFichaDGDireccionReferencia,
                std.cEstPaterno,
                std.cEstMaterno,
                std.cEstNombres,
                p.cPersDocumento,
                p.dPersNacimiento,
                p.cPersSexo,
                ec.cTipoEstCivilNombre,
                fdg.iFichaDGNroHijos,
                ps.cPaisNombre,
                ubg.cUbigeoDpto,
                ubg.cUbigeoProvincia,
                ubg.cUbigeoDistrito,
                IIF(fdg.bFamiliarPadreVive = 1, 'SI', 'NO') AS bFamiliarPadreVive,
		        IIF(fdg.bFamiliarMadreVive = 1, 'SI', 'NO') AS bFamiliarMadreVive
            FROM obe.ficha_datos_grales AS fdg
            INNER JOIN grl.personas p ON fdg.iPersId = p.iPersId
            INNER JOIN obe.tipo_vias tpv ON fdg.iTipoViaId = tpv.iTipoViaId
            INNER JOIN grl.departamentos dep ON p.iDptoId = dep.iDptoId
            INNER JOIN grl.provincias prv ON p.iPrvnId = prv.iPrvnId
            INNER JOIN grl.distritos dstr ON p.iDsttId = dstr.iDsttId
            INNER JOIN grl.paises ps ON p.iPaisId = ps.iPaisId
            INNER JOIN acad.estudiantes std ON fdg.iPersId = std.iPersId
            INNER JOIN grl.ubigeos ubg ON std.cEstUbigeoNacimiento = ubg.cUbigeoReniec
            INNER JOIN grl.tipos_estados_civiles ec ON p.iTipoEstCivId = ec.iTipoEstCivId
            WHERE fdg.iPersId = ? AND YEAR(fdg.dtFichaDG) = 2025
            ", [$id]);

        // Consulta 2: Familiares
        $familiares = DB::select("
                SELECT 
                    fam.iPersId,
                    gper.cPersPaterno,
                    gper.cPersMaterno,
                    gper.cPersNombre,
                    gper.dPersNacimiento,
                    --DATEDIFF(YEAR, gper.dPersNacimiento, GETDATE()) AS EdadFam,
                    FLOOR(DATEDIFF(DAY, gper.dPersNacimiento, GETDATE()) / 365.25) AS EdadFam,
                    tfam.cTipoFamiliarDescripcion,
                    stcv.cTipoEstCivilNombre,
                    gdi.cGradoInstNombre,
                    ocup.cOcupacionNombre,
                    fam.cFamiliarResidenciaActual
                FROM
                        obe.ficha_datos_grales AS fichdat
                    INNER JOIN 
                        obe.familiares fam ON fichdat.iFichaDGId = fam.iFichaDGId
                    LEFT JOIN
                        grl.personas gper ON fam.iPersId = gper.iPersId
                    LEFT JOIN
                            obe.tipo_vias tpvias ON fam.iTipoViaId = tpvias.iTipoViaId
                    LEFT JOIN 
                            obe.tipo_familiares tfam ON fam.iTipoFamiliarId = tfam.iTipoFamiliarId
                    LEFT JOIN 
                            grl.tipos_estados_civiles stcv ON gper.iTipoEstCivId = stcv.iTipoEstCivId
                    LEFT JOIN
                            obe.grado_instrucciones gdi ON fam.iGradoInstId = gdi.iGradoInstId
                    LEFT JOIN
                            obe.ocupaciones ocup ON fam.iOcupacionId = ocup.iOcupacionId
                    WHERE 
                        fichdat.iPersId = 474
                        AND YEAR(fichdat.dtFichaDG) = '2025';
                        ", [$id]);
 
        $aspeconimico = DB::selectOne("
                 SELECT 
                        rangsueld.cRangoSueldoDescripcion,
                        dpecon.cDepEcoDescripcion,
                        tipapoyecon.cTipoAEcoDescripcion,
                        fiecon.cIngresoEcoActividad,
                        fiecon.iIngresoEcoEstudiante,
                        fiecon.iIngresoEcoTrabajoHoras,
                        jorndtrab.cJorTrabDescripcion
                    FROM
                        obe.ficha_datos_grales AS fdgen
                    INNER JOIN 
                        obe.ficha_ingresos_economicos fiecon ON fdgen.iFichaDGId = fiecon.iFichaDGId
                    INNER JOIN
                        obe.rango_sueldos rangsueld ON fiecon.iRangoSueldoId = rangsueld.iRangoSueldoId
                    INNER JOIN
                        obe.depende_economicos dpecon ON fiecon.iDepEcoId = dpecon.iDepEcoId
                    INNER JOIN
                        obe.tipos_apoyo_economicos tipapoyecon ON fiecon.iTipoAEcoId = tipapoyecon.iTipoAEcoId
                    INNER JOIN
                        obe.jornada_trabajos jorndtrab ON fiecon.iJorTrabId = jorndtrab.iJorTrabId
                    WHERE fdgen.iPersId = ? AND YEAR(fdgen.dtFichaDG) = 2025
                    ", [$id]);

    // ---------IV ASPECTO DE LA VIVIENDA (DODE VIVE EL ESTUDIANTE----------------------------	
                $aspvivienda = DB::selectOne("
                    SELECT 
                            tipocup.cTipoOcupaVivDescripcion,
                            vndacar.iViviendaCarNroPisos,
                            estdviv.cEstadoVivDescripcion,
                            matpred.cMatPreDescripcion,
                            matpiso.cMatPisoVivDescripcion,
                            mattech.cMatTecVivDescripcion,
                            tipviv.cTipoVivDescripcion,
                            vndacar.iViviendaCarNroAmbientes,
                            vndacar.iViviendaCarNroHabitaciones,
                            tipsuminA.cTipoSumADescripcion,
                            tipsshh.cTipoSsHhDescripcion,
                            tipalumb.cTipoAlumDescripcion
                    FROM
                                obe.ficha_datos_grales AS fichdg
                            INNER JOIN 
                                obe.vienda_caracteristicas_fichas vndacar ON fichdg.iFichaDGId = vndacar.iFichaDGId
                            INNER JOIN 
                                obe.tipo_ocupacion_viviendas tipocup ON vndacar.iTipoOcupaVivId = tipocup.iTipoOcupaVivId
                            INNER JOIN 
                                obe.estado_viviendas estdviv ON vndacar.iEstadoVivId = estdviv.iEstadoVivId
                            INNER JOIN 
                                obe.material_predominantes matpred ON  vndacar.iMatPreId = matpred.iMatPreId
                            INNER JOIN 
                                obe.material_piso_viviendas matpiso ON vndacar.iMatPisoVivId = matpiso.iMatPisoVivId
                            INNER JOIN 
                                obe.material_techo_viviendas mattech ON vndacar.iMatTecVivId = mattech.iMatTecVivId
                            INNER JOIN 
                                obe.tipo_viviendas tipviv ON vndacar.iTipoVivId = tipviv.iTipoVivId
                            INNER JOIN 
                                obe.tipos_suministro_agua tipsuminA ON vndacar.iTipoSumAId = tipsuminA.iTipoSumAId
                            INNER JOIN 
                                obe.tipos_sshh_viviendas tipsshh ON vndacar.iTiposSsHhId = tipsshh.iTiposSsHhId
                            INNER JOIN 
                                obe.tipos_alumbrado_viviendas tipalumbviv ON vndacar.iViendaCarId = tipalumbviv.iViendaCarId
                            INNER JOIN 
                                obe.tipos_alumbrado tipalumb ON tipalumbviv.iTipoAlumId = tipalumb.iTipoAlumId
                            WHERE fichdg.iPersId = ? AND YEAR(fichdg.dtFichaDG) = '2025'
                             ", [$id]);

                            // ----------EQUIPAMIENTO EN EL HOGAR--------------------------
                $equipamiento = DB::select("
                    SELECT 
                             vndacarac.iFichaDGId,
                             elemviv.iViendaCarId,
                             elmpviv.cEleParaVivDescripcion
                    FROM
                             obe.ficha_datos_grales AS fdtsg
                             INNER JOIN 
                             obe.vienda_caracteristicas_fichas vndacarac ON fdtsg.iFichaDGId = vndacarac.iFichaDGId
                             INNER JOIN 
                             obe.elementos_viviendas elemviv ON vndacarac.iViendaCarId = elemviv.iViendaCarId
                             INNER JOIN 
                             obe.elementos_para_vivienda elmpviv ON elemviv.iEleParaVivId = elmpviv.iEleParaVivId
                             WHERE fdtsg.iPersId = ? AND YEAR(fdtsg.dtFichaDG) = '2025'
                            ", [$id]);

   

                $alimentacionstd = DB::selectOne("
                    SELECT
                        af.iFichaDGId,
                        des.cLugAlimDescripcion AS lugarDesayuno,
                        alm.cLugAlimDescripcion AS lugarAlmuerzo,
                        cen.cLugAlimDescripcion AS lugarCena
                    FROM
                        obe.ficha_datos_grales AS fdg
                        INNER JOIN obe.alimentacion_fichas AS af ON fdg.iFichaDGId = af.iFichaDGId
                        INNER JOIN obe.lugar_alimentacion AS des ON af.iLugarAlimIdDesayuno = des.iLugAlimId
                        INNER JOIN obe.lugar_alimentacion AS alm ON af.iLugarAlimIdAlmuerzo = alm.iLugAlimId
                        INNER JOIN obe.lugar_alimentacion AS cen ON af.iLugarAlimIdCena = cen.iLugAlimId
                    WHERE
                        fdg.iPersId =? AND YEAR(fdg.dtFichaDG) = '2025'
                    ", [$id]);

//--------------VI. DISCAPACIDAD-------------------------------------
                   $dispacidad  =  DB::select("
                    SELECT
                            fchdiscap.iFichaDGId,
                            discap.cDiscNombre,
                            IIF(fdatgnrls.bFichaDGEstaEnOMAPED = 1,'SI','NO') AS EstaEnOMAPED02,
                            IIF(fdatgnrls.bFichaDGEstaEnCONADIS = 1, 'SI', 'NO') AS EstaEnCONADIS
                    FROM
                        obe.ficha_datos_grales AS fdatgnrls 
                        INNER JOIN obe.discapcidades_fichas fchdiscap ON fdatgnrls.iFichaDGId = fchdiscap.iFichaDGId
                        INNER JOIN obe.discapacidades discap ON fchdiscap.iDiscId =  discap.iDiscId
                        WHERE fdatgnrls.iPersId = ? AND YEAR(fdatgnrls.dtFichaDG) = '2025'
                    ", [$id]);
                   
 // ---------------VII SALUD----------------------------------------
                    $salud = DB::select("
                    SELECT
                        fdolenc.iFichaDGId,
                        dolenc.cDolenciaNombre,
                        IIF(fdatsgnral.cFichaDGAlergiaMedicamentos = 1,'SI','NO') AS AlergiaMedicamentos,
                        IIF(fdatsgnral.cFichaDGAlergiaAlimentos = 1,'SI','NO') AS AlergiaAlimentos,
                        IIF(fdatsgnral.cFichaDGAlergiaOtros = 1, 'SI','NO') AS AlergiaOtros
                    FROM
                        obe.ficha_datos_grales AS fdatsgnral
                        INNER JOIN obe.dolencias_fichas fdolenc ON fdatsgnral.iFichaDGId = fdolenc.iFichaDGId
                        INNER JOIN obe.dolencias dolenc ON fdolenc.iDolenciaId = dolenc.iDolenciaId
                    WHERE fdatsgnral.iPersId = ?
                        AND YEAR(fdatsgnral.dtFichaDG) = '2025';
                    ", [$id]); // $id puede ser 474 u otro valor dinámico


//--------------------------SEGURO DE SALUD------------------------------
                $seguros = DB::select("
                SELECT
                    segaport.iFichaDGId,
                    segsalud.cSegSaludNombre,
                    tipsegaport.cTipSegAportaNombre
        
                FROM
                    obe.ficha_datos_grales AS fdtsgnls
                    INNER JOIN obe.seguros_aportacion segaport ON fdtsgnls.iFichaDGId = segaport.iFichaDGId
                    INNER JOIN obe.seguros_salud segsalud ON segaport.iSegSaludId = segsalud.iSegSaludId
                    INNER JOIN obe.tipo_seguro_aportacion tipsegaport ON segaport.iSegAportaId = tipsegaport.iTipSegAportaId
                    WHERE fdtsgnls.iPersId = 474
                                AND YEAR(fdtsgnls.dtFichaDG) = '2025';
                   ", [$id]);

 
// ------------VIII-INFORMACION COMPLEMENTARIA---(DEPORTE)------------------

                   $deportes = DB::select("
                   SELECT 
                       fdeports.iFichaDGId,
                       deports.cDeporteNombre,
                       IIF(fdtsgral.cFichaDGPerteneceLigaDeportiva = 1,'SI','NO') AS PerteneceLigaDeportiva
                   FROM
                       obe.ficha_datos_grales AS fdtsgral
                       INNER JOIN obe.deportes_fichas fdeports ON fdtsgral.iFichaDGId = fdeports.iFichaDGId
                       INNER JOIN obe.deportes deports ON fdeports.iDeporteId = deports.iDeporteId
                   WHERE fdtsgral.iPersId = ?
                       AND YEAR(fdtsgral.dtFichaDG) = '2025';
               ", [$id]);
               
// -------------INFORMACION COMPLEMENTARIA---CULTURA Y RECREACION----------------

                $cultura = DB::select("
                    SELECT 
                        fchpasatiemp.iFichaDGId,
                        pastiempo.cPasaTiempoNombre,
                        IIF(fdtsgrl.cFichaDGPerteneceCentroArtistico = 1,'SI', 'NO') AS PerteCentroArtistico
                    FROM
                        obe.ficha_datos_grales AS fdtsgrl
                        INNER JOIN obe.pasatiempo_fichas fchpasatiemp ON fdtsgrl.iFichaDGId = fchpasatiemp.iFichaDGId
                        INNER JOIN obe.pasatiempos pastiempo ON fchpasatiemp.iPasaTiempoId = pastiempo.iPasaTiempoId 
                            AND pastiempo.bPasaTiempoEsActividadArtistica = 1
                        INNER JOIN obe.religiones relig ON fdtsgrl.iReligionId = relig.iReligionId
                    WHERE fdtsgrl.iPersId = ?
                        AND YEAR(fdtsgrl.dtFichaDG) = '2025';
                ", [$id]);

//-------PASATIEMPOS-------------------------------

                $pasatiempos = DB::select("
                    SELECT 
                        psatmpos.cPasaTiempoNombre
                    FROM
                        obe.ficha_datos_grales AS fdatsgen
                        INNER JOIN obe.pasatiempo_fichas fpasatmpo ON fdatsgen.iFichaDGId = fpasatmpo.iFichaDGId
                        INNER JOIN obe.pasatiempos psatmpos ON fpasatmpo.iPasaTiempoId = psatmpos.iPasaTiempoId 
                            AND psatmpos.bPasaTiempoEsActividadArtistica = 0
                    WHERE fdatsgen.iPersId = ?
                        AND YEAR(fdatsgen.dtFichaDG) = '2025';
                ", [$id]);

// ---------PSICOPEDAGOGICO-----------------------------
$emocionales = DB::select("
    SELECT 
        fdgrales.cFichaDGAsistioConsultaPsicologica,
        fproblem.iTipoFamiliarId,
        tipfamles.cTipoFamiliarDescripcion
    FROM
        obe.ficha_datos_grales AS fdgrales
        INNER JOIN obe.problemas_emocionales_ficha fproblem ON fdgrales.iFichaDGId = fproblem.iFichaDGId
        INNER JOIN obe.tipo_familiares tipfamles ON fproblem.iTipoFamiliarId = tipfamles.iTipoFamiliarId
    WHERE fdgrales.iPersId = ?
        AND YEAR(fdgrales.dtFichaDG) = '2025';
", [$id]);

     
//-----------------TRANSPORTE------------------  
$transporte = DB::select("
    SELECT
        fichtransport.iTransporteId,
        trasnprt.cTransporteNombre,
        dolencs.cDolenciaNombre
    FROM
        obe.ficha_datos_grales AS ficdtosgrals
        INNER JOIN obe.transportes_fichas fichtransport 
            ON ficdtosgrals.iFichaDGId = fichtransport.iFichaDGId
        INNER JOIN obe.transportes trasnprt 
            ON fichtransport.iTransporteId = trasnprt.iTransporteId
        LEFT JOIN obe.dolencias_fichas fdolencs 
            ON ficdtosgrals.iFichaDGId = fdolencs.iFichaDGId
        LEFT JOIN obe.dolencias dolencs 
            ON fdolencs.iDolenciaId = dolencs.iDolenciaId
    WHERE 
        ficdtosgrals.iPersId = ?
        AND YEAR(ficdtosgrals.dtFichaDG) = ?;
", [$id, $anio]);




//-------------------------------------------------------------------
  
        // Ahora, mapear esos datos a tu estructura:
            // Paso 1: Mapeo principal sin familiares
            $datos = [
                'direccion_domiciliaria' => [
                    'tipo_via' => $datosGenerales->cTipoViaNombre ?? 'N/A',
                    'nombre_via' => $datosGenerales->cFichaDGDireccionNombreVia ?? 'N/A',
                    'numero_puerta' => $datosGenerales->cFichaDGDireccionNroPuerta ?? 'N/A',
                    'block' => $datosGenerales->cFichaDGDireccionBlock ?? 'N/A',
                    'interior' => $datosGenerales->cFichaDGDirecionInterior ?? 'N/A',
                    'piso' => $datosGenerales->cFichaDGDirecionPiso ?? 'N/A',
                    'mz' => $datosGenerales->cFichaDGDireccionManzana ?? 'N/A',
                    'lote' => $datosGenerales->cFichaDGDireccionLote ?? 'N/A',
                    'km' => $datosGenerales->cFichaDGDireccionKm ?? 'N/A',
                    'departamento' => $datosGenerales->cDptoNombre ?? 'N/A',
                    'provincia' => $datosGenerales->cPrvnNombre ?? 'N/A',
                    'distrito' => $datosGenerales->cDsttNombre ?? 'N/A',
                    'vive_padre' => $datosGenerales->bFamiliarPadreVive ?? 'N/A',
                    'vive_madre' => $datosGenerales->bFamiliarMadreVive ?? 'N/A',
                    'referencia' => $datosGenerales->cFichaDGDireccionReferencia ?? 'N/A'
                ],
                
                'estudiante' => [
                    'apellido_paterno' => $datosGenerales->cEstPaterno ?? '',
                    'apellido_materno' => $datosGenerales->cEstMaterno ?? '',
                    'nombres' => $datosGenerales->cEstNombres ?? '',
                    'dni' => $datosGenerales->cPersDocumento ?? '',
                    'fecha_nacimiento' => date('d-m-Y', strtotime($datosGenerales->dPersNacimiento ?? '')),
                    'sexo' => $datosGenerales->cPersSexo == 'M' ? 'Masculino' : 'Femenino',
                    'estado_civil' => $datosGenerales->cTipoEstCivilNombre ?? '',
                    'num_hijos' => $datosGenerales->iFichaDGNroHijos ?? '',
                ],
            
                'nacimiento' => [
                    'pais' => $datosGenerales->cPaisNombre ?? '',
                    'departamento' => $datosGenerales->cUbigeoDpto ?? '',
                    'provincia' => $datosGenerales->cUbigeoProvincia ?? '',
                    'distrito' => $datosGenerales->cUbigeoDistrito ?? '',
                ],

              ];

        //Agregar familiares fuera del array original
        $datos['familiares'] = [];
        foreach ($familiares as $familiar) {
            $datos['familiares'][] = [
                'id' => $familiar->iPersId ?? null,
                'apellido_paterno' => $familiar->cPersPaterno ?? '',
                'apellido_materno' => $familiar->cPersMaterno ?? '',
                'nombres' => $familiar->cPersNombre ?? '',
                'fecha_nacimiento' => isset($familiar->dPersNacimiento) ? date('d-m-Y', strtotime($familiar->dPersNacimiento)) : '',
                'edad' => $familiar->EdadFam ?? '',
                'tipo_familiar' => $familiar->cTipoFamiliarDescripcion ?? '',
                'estado_civil' => $familiar->cTipoEstCivilNombre ?? '',
                'grado_instruccion' => $familiar->cGradoInstNombre ?? '',
                'ocupacion' => $familiar->cOcupacionNombre ?? '',
                'residencia_actual' => $familiar->cFamiliarResidenciaActual ?? '',
            ];
        }

        $datos['aspecto_economico'] = [
            'rango_sueldo' => $aspeconimico->cRangoSueldoDescripcion ?? '',
            'depende_economicamente_de' => $aspeconimico->cDepEcoDescripcion ?? '',
            'tipo_apoyo_economico' => $aspeconimico->cTipoAEcoDescripcion ?? '',
            'actividad_ingreso' => $aspeconimico->cIngresoEcoActividad ?? '',
            'aporte_estudiante' => $aspeconimico->iIngresoEcoEstudiante ?? '',
            'horas_trabajo' => $aspeconimico->iIngresoEcoTrabajoHoras ?? '',
            'jornada_trabajo' => $aspeconimico->cJorTrabDescripcion ?? '',
        ];
        
        $datos['aspecto_vivienda'] = [
            'vivienda_es' => $aspvivienda->cTipoOcupaVivDescripcion ?? '',
            'npisos' => $aspvivienda->iViviendaCarNroPisos ?? '',
            'estado' => $aspvivienda->cEstadoVivDescripcion ?? '',
            'mat_pred_pared' => $aspvivienda->cMatPreDescripcion ?? '',
            'mat_piso' => $aspvivienda->cMatPisoVivDescripcion ?? '',
            'mat_techo' => $aspvivienda->cMatTecVivDescripcion ?? '',
            'tipo_vivienda' => $aspvivienda->cTipoVivDescripcion ?? '',
            'nro_ambientes' => $aspvivienda->iViviendaCarNroAmbientes ?? '',
            'nro_habitaciones' => $aspvivienda->iViviendaCarNroHabitaciones ?? '',
            'tipo_servicio' => $aspvivienda->cTipoSumADescripcion ?? '',
            'tipo_sshh' => $aspvivienda->cTipoSsHhDescripcion ?? '',
            'tipo_alumbrado' => $aspvivienda->cTipoAlumDescripcion ?? '',
        ];

        $datos['equipamiento'] = [];
        foreach ($equipamiento as $equipos) {
            $datos['equipamiento'][] = [
                'electrodm_hogar' => $equipos->cEleParaVivDescripcion ?? '',
            ];
        };
        
        $datos['alimentos_std'] = [
            'lugar_desayuno' =>$alimentacionstd ->lugarDesayuno ?? '',
            'lugar_almuerzo' =>$alimentacionstd ->lugarAlmuerzo ?? '',           
            'lugar_ceba' =>$alimentacionstd ->lugarCena ?? '',           

        ];

        $datos['pers_discapacidad'] = [];

        foreach ($dispacidad as $discapacidad) {
            $datos['pers_discapacidad'][] = [
                'id_ficha'          => $discapacidad->iFichaDGId ?? null,
                'nomb_discapacidad' => $discapacidad->cDiscNombre ?? '',
                'esta_en_omaped'    => $discapacidad->EstaEnOMAPED02 ?? 'NO',
                'esta_en_conadis'   => $discapacidad->EstaEnCONADIS ?? 'NO',
            ];
        }
   
        $datos['pers_salud'] = [];
        $datos['alergias'] = [
            'AlergiaMedicamentos' => null,
            'AlergiaAlimentos' => null,
            'AlergiaOtros' => null,
        ];
        
        foreach ($salud as $index => $psalud) {
            // Guardar enfermedades
            if (!empty($psalud->cDolenciaNombre)) {
                $datos['pers_salud'][] = [
                    'enfermedad_nomb' => $psalud->cDolenciaNombre,
                ];
            }
        
            // Solo una vez cargar las alergias (vienen repetidas si hay varias enfermedades)
            if ($index === 0) {
                $datos['alergias'] = [
                    'AlergiaMedicamentos' => $psalud->AlergiaMedicamentos ?? 'NO',
                    'AlergiaAlimentos'    => $psalud->AlergiaAlimentos ?? 'NO',
                    'AlergiaOtros'        => $psalud->AlergiaOtros ?? 'NO',
                ];
            }
        }
        
  
        $datos['pers_seguros'] = [];

        foreach ($seguros as $seguro) {
            $datos['pers_seguros'][] = [
                'seguro_salud'       => $seguro->cSegSaludNombre ?? null,
                'seguro_aportacion'  => $seguro->cTipSegAportaNombre ?? null,
            ];
        }
          
        
        $datos['pers_deportes'] = [];
        $datos['liga_deportiva'] = null;
        
        foreach ($deportes as $index => $deporte) {
            // Guardar deportes
            if (!empty($deporte->cDeporteNombre)) {
                $datos['pers_deportes'][] = [
                    'deporte_nombre' => $deporte->cDeporteNombre,
                ];
            }
        
            // Solo una vez guardar si pertenece a liga (ya que se repite por cada deporte)
            if ($index === 0) {
                $datos['liga_deportiva'] = $deporte->PerteneceLigaDeportiva ?? 'NO';
            }
        }
        
    
        $datos['pers_artes'] = [];
        $datos['centro_artistico'] = null;
        
        foreach ($cultura as $index => $item) {
            // Agregar pasatiempos artísticos
            if (!empty($item->cPasaTiempoNombre)) {
                $datos['pers_artes'][] = [
                    'pasatiempo_artistico' => $item->cPasaTiempoNombre,
                ];
            }
        
            // Guardar si pertenece a un centro artístico (una sola vez)
            if ($index === 0) {
                $datos['centro_artistico'] = $item->PerteCentroArtistico ?? 'NO';
            }
        }
   
        $datos['pers_pasatiempos'] = [];

        foreach ($pasatiempos as $item) {
            if (!empty($item->cPasaTiempoNombre)) {
                $datos['pers_pasatiempos'][] = [
                    'pasatiempo_nombre' => $item->cPasaTiempoNombre,
                ];
            }
        }
             
        $datos['pers_psicopedagogico'] = [];

        $asistio = null;
        $familiares = [];
        
        foreach ($emocionales as $item) {
            if (!empty($item->cFichaDGAsistioConsultaPsicologica)) {
                $asistio = $item->cFichaDGAsistioConsultaPsicologica;
            }
        
            if (!empty($item->cTipoFamiliarDescripcion)) {
                $familiares[] = $item->cTipoFamiliarDescripcion;
            }
        }
        
        $datos['pers_psicopedagogico'][] = [
            'asistio_consulta' => $asistio ?? 'No hay información',
            'familiares' => $familiares
        ];
        

        $datos['medio_transporte'] = [];

        foreach ($transporte as $mtransporte){
        $datos['medio_transporte'][] = [
            'transporte_nombre' => $mtransporte->cTransporteNombre,
            'dolencia_nombre' => $mtransporte->cDolenciaNombre ?? 'Sin información',
    
        ];
        }
   
        $datos['pers_seguros'] = [];

        foreach ($seguros as $seguro) {
            $datos['pers_seguros'][] = [
                'seguro_salud'       => $seguro->cSegSaludNombre ?? null,
                'seguro_aportacion'  => $seguro->cTipSegAportaNombre ?? null,
            ];
        }


return view('pdfFicha.ficha', $datos);

    }
}

