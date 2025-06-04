<?php
namespace App\Http\Controllers\bienestar;
use Barryvdh\DomPDF\Facade\Pdf;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para usar consultas SQL crudas

class FichaPdfController extends Controller
{
    public function mostrarFichaPdf($id, $anio)
    {
        //---------------- Datos generales y de dirección------------
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
                std.cEstCodigo,
                std.cEstTelefono,
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
                WHERE fdg.iPersId = ? AND YEAR(fdg.dtFichaDG) = ?;
            ", [$id, $anio]);

        //--------------IE Procedencia------------------------------------------------------
        $ie_procedencia = DB::select("     
            SELECT 
                dtgnrales.iPersId,
                ie.iIieeId,
                ie.cIieeNombre,
                tipsect.cTipoSectorNombre
                FROM obe.ficha_datos_grales AS dtgnrales
                INNER JOIN obe.historial_ies hist_ie ON dtgnrales.iFichaDGId = hist_ie.iFichaDGId
                INNER JOIN acad.institucion_educativas ie ON hist_ie.iIieeId = ie.iIieeId
                INNER JOIN grl.tipos_sectores tipsect ON ie.iTipoSectorId = tipsect.iTipoSectorId
                INNER JOIN grl.personas persnas ON dtgnrales.iPersId = persnas.iPersId
            WHERE dtgnrales.iPersId = ? AND YEAR(dtgnrales.dtFichaDG) = ?;
                ", [$id, $anio]);

        $familiares = DB::select("
            SELECT 
                fam.iPersId,
                gper.cPersPaterno,
                gper.cPersMaterno,
                gper.cPersNombre,
                gper.dPersNacimiento,
                FLOOR(DATEDIFF(DAY, gper.dPersNacimiento, GETDATE()) / 365.25) AS EdadFam,
                tfam.cTipoFamiliarDescripcion,
                stcv.cTipoEstCivilNombre,
                gdi.cGradoInstNombre,
                ocup.cOcupacionNombre,
                deprto.cDptoNombre,
                provincs.cPrvnNombre,
                dstrit.cDsttNombre
            FROM
                obe.ficha_datos_grales AS fichdat
                INNER JOIN obe.familiares fam ON fichdat.iFichaDGId = fam.iFichaDGId
                LEFT JOIN grl.personas gper ON fam.iPersId = gper.iPersId
                LEFT JOIN obe.tipo_vias tpvias ON fam.iTipoViaId = tpvias.iTipoViaId
                LEFT JOIN obe.tipo_familiares tfam ON fam.iTipoFamiliarId = tfam.iTipoFamiliarId
                LEFT JOIN grl.tipos_estados_civiles stcv ON gper.iTipoEstCivId = stcv.iTipoEstCivId
                LEFT JOIN obe.grado_instrucciones gdi ON fam.iGradoInstId = gdi.iGradoInstId
                LEFT JOIN obe.ocupaciones ocup ON fam.iOcupacionId = ocup.iOcupacionId
                LEFT JOIN grl.departamentos deprto ON gper.iDptoId = deprto.iDptoId
                LEFT JOIN grl.provincias provincs ON gper.iPrvnId = provincs.iPrvnId
                LEFT JOIN grl.distritos dstrit ON gper.iDsttId = dstrit.iDsttId
            WHERE 
                fichdat.iPersId = ?
                AND YEAR(fichdat.dtFichaDG) = ?

            UNION ALL

            SELECT 
                std.iPersId,
                gper.cPersPaterno,
                gper.cPersMaterno,
                gper.cPersNombre,
                gper.dPersNacimiento,
                FLOOR(DATEDIFF(DAY, gper.dPersNacimiento, GETDATE()) / 365.25) AS EdadFam,
                'Hijo' AS cTipoFamiliarDescripcion,
                stcv.cTipoEstCivilNombre,
                'Estudiante' AS cGradoInstNombre,
                NULL AS cOcupacionNombre,
                deprto.cDptoNombre,
                provincs.cPrvnNombre,
                dstrit.cDsttNombre
            FROM
                obe.ficha_datos_grales AS fichdat
                LEFT JOIN acad.estudiantes std ON fichdat.iPersId = std.iPersId
                LEFT JOIN grl.personas gper ON std.iPersId = gper.iPersId
                LEFT JOIN grl.tipos_estados_civiles stcv ON gper.iTipoEstCivId = stcv.iTipoEstCivId
                LEFT JOIN grl.departamentos deprto ON gper.iDptoId = deprto.iDptoId
                LEFT JOIN grl.provincias provincs ON gper.iPrvnId = provincs.iPrvnId
                LEFT JOIN grl.distritos dstrit ON gper.iDsttId = dstrit.iDsttId
            WHERE 
                fichdat.iPersId = ?
                AND YEAR(fichdat.dtFichaDG) = ?
            ", [$id, $anio, $id, $anio]);


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
                    WHERE fdgen.iPersId = ? AND YEAR(fdgen.dtFichaDG) = ?
                    ", [$id, $anio]);

        //-----------------Direccion Actual de los Familiares de los Estudiantes----26 May-------------
        $direcc_familiares = DB::select("      
                    SELECT 
                        fam.iPersId,
                        fam.cFamiliarResidenciaActual,
                            fam.cFamiliarDireccionNombreVia,
                            fam.cFamiliarDireccionNroPuerta,
                            fam.cFamiliarDireccionBlock,
                            fam.cFamiliarDirecionInterior,
                            fam.cFamiliarDirecionPiso,
                            fam.cFamiliarDireccionManzana,
                            fam.cFamiliarDireccionLote,
                            fam.cFamiliarDireccionKm,
                            fam.cFamiliarDireccionReferencia,
                            tpvias.cTipoViaNombre 
                    FROM
                        obe.ficha_datos_grales AS fichdat
                        INNER JOIN obe.familiares fam ON fichdat.iFichaDGId = fam.iFichaDGId
                        LEFT JOIN grl.personas gper ON fam.iPersId = gper.iPersId
                        LEFT JOIN obe.tipo_vias tpvias ON fam.iTipoViaId = tpvias.iTipoViaId
                        LEFT JOIN obe.tipo_familiares tfam ON fam.iTipoFamiliarId = tfam.iTipoFamiliarId
                        LEFT JOIN grl.tipos_estados_civiles stcv ON gper.iTipoEstCivId = stcv.iTipoEstCivId
                        LEFT JOIN obe.grado_instrucciones gdi ON fam.iGradoInstId = gdi.iGradoInstId
                        LEFT JOIN obe.ocupaciones ocup ON fam.iOcupacionId = ocup.iOcupacionId
                        WHERE 
                            fichdat.iPersId = ?
                            AND YEAR(fichdat.dtFichaDG) = ?
                              ", [$id, $anio]);

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
                            WHERE fichdg.iPersId = ? AND YEAR(fichdg.dtFichaDG) = ?
                             ", [$id, $anio]);

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
                             WHERE fdtsg.iPersId = ? AND YEAR(fdtsg.dtFichaDG) = ?
                            ", [$id, $anio]);

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
                        fdg.iPersId =? AND YEAR(fdg.dtFichaDG) = ?
                    ", [$id, $anio]);

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
                        WHERE fdatgnrls.iPersId = ? AND YEAR(fdatgnrls.dtFichaDG) = ?
                    ", [$id, $anio]);

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
                    WHERE fdatsgnral.iPersId = ? AND dolenc.bDolenciaEsCronica = 1
                        AND YEAR(fdatsgnral.dtFichaDG) = ?;
                    ", [$id, $anio]);

        //--------------------------SEGURO DE SALUD------------------------------
        $sis_salud = DB::select("
                    SELECT
                        segaport.iFichaDGId,
                        segsalud.cSegSaludNombre,
                        tipsegaport.cTipSegAportaNombre
            
                    FROM
                        obe.ficha_datos_grales AS fdtsgnls
                        INNER JOIN obe.seguros_aportacion segaport ON fdtsgnls.iFichaDGId = segaport.iFichaDGId
                        INNER JOIN obe.seguros_salud segsalud ON segaport.iSegSaludId = segsalud.iSegSaludId
                        INNER JOIN obe.tipo_seguro_aportacion tipsegaport ON segaport.iSegAportaId = tipsegaport.iTipSegAportaId
                        WHERE fdtsgnls.iPersId = ?
                                    AND YEAR(fdtsgnls.dtFichaDG) = ?;
                    ", [$id, $anio]);


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
                       AND YEAR(fdtsgral.dtFichaDG) = ?;
                    ", [$id, $anio]);

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
                        AND YEAR(fdtsgrl.dtFichaDG) = ?;
                ", [$id, $anio]);

        //----------------RELIGION-----------------------------------------------
        $religiones = DB::select("
                    SELECT 
                    relig.cReligionNombre
                    FROM
                    obe.ficha_datos_grales as fdgrals
                    INNER JOIN obe.religiones relig ON fdgrals.iReligionId = relig.iReligionId
                    WHERE fdgrals.iPersId = ?
                            AND YEAR(fdgrals.dtFichaDG) = ?;
                    ", [$id, $anio]);

        //---------------PASATIEMPOS--------------------------------------------

        $pasatiempos = DB::select("
                    SELECT 
                        psatmpos.cPasaTiempoNombre
                    FROM
                        obe.ficha_datos_grales AS fdatsgen
                        INNER JOIN obe.pasatiempo_fichas fpasatmpo ON fdatsgen.iFichaDGId = fpasatmpo.iFichaDGId
                        INNER JOIN obe.pasatiempos psatmpos ON fpasatmpo.iPasaTiempoId = psatmpos.iPasaTiempoId 
                            AND psatmpos.bPasaTiempoEsActividadArtistica = 0
                    WHERE fdatsgen.iPersId = ?
                        AND YEAR(fdatsgen.dtFichaDG) = ?;
                ", [$id, $anio]);

        // -----------------------PSICOPEDAGOGICO-----------------------------
        $emociones = DB::select("
                    SELECT 
                        fdgrales.cFichaDGAsistioConsultaPsicologica,
                        fproblem.iTipoFamiliarId,
                        tipfamles.cTipoFamiliarDescripcion
                    FROM
                        obe.ficha_datos_grales AS fdgrales
                        INNER JOIN obe.problemas_emocionales_ficha fproblem ON fdgrales.iFichaDGId = fproblem.iFichaDGId
                        INNER JOIN obe.tipo_familiares tipfamles ON fproblem.iTipoFamiliarId = tipfamles.iTipoFamiliarId
                    WHERE fdgrales.iPersId = ?
                        AND YEAR(fdgrales.dtFichaDG) = ?;
                ", [$id, $anio]);

        //-----------------TRANSPORTE------------------  
        $med_transporte = DB::select("
                   SELECT
                        fichtransport.iTransporteId,
                        trasnprt.cTransporteNombre
                    FROM
                        obe.ficha_datos_grales AS ficdtosgrals
                        INNER JOIN obe.transportes_fichas fichtransport 
                            ON ficdtosgrals.iFichaDGId = fichtransport.iFichaDGId
                        INNER JOIN obe.transportes trasnprt 
                            ON fichtransport.iTransporteId = trasnprt.iTransporteId
                   WHERE 
                        ficdtosgrals.iPersId = ?
                        AND YEAR(ficdtosgrals.dtFichaDG) = ?;;
                ", [$id, $anio]);

        //------------------------27 Mayo-----------------------------------------

        $enfermedad_cronic = DB::select("
                    SELECT
                        fdolenc.iFichaDGId,
                        dolenc.cDolenciaNombre
                    FROM
                        obe.ficha_datos_grales AS fdatsgnral
                        INNER JOIN obe.dolencias_fichas fdolenc ON fdatsgnral.iFichaDGId = fdolenc.iFichaDGId
                        INNER JOIN obe.dolencias dolenc ON fdolenc.iDolenciaId = dolenc.iDolenciaId
                        WHERE fdatsgnral.iPersId = ? AND dolenc.bDolenciaEsTransmisible = 1
                                    AND YEAR(fdatsgnral.dtFichaDG) = ?;
                    ", [$id, $anio]);

        $dosis_vacunaCovid = DB::select("
                    SELECT
                            fpandedosis.iPanDFichaNroDosis
                    FROM
                            obe.ficha_datos_grales AS fdtsgrals 
                            INNER JOIN obe.pandemia_dosis_fichas fpandedosis ON fdtsgrals.iFichaDGId = fpandedosis.iFichaDGId
                            INNER JOIN obe.pandemia_murieron_fichas fpandmur ON  fpandedosis.iPanDFichaId = fpandmur.iPandemiaId
                            WHERE fdtsgrals.iPersId = ?
                                        AND YEAR(fdtsgrals.dtFichaDG) = ?;
                    ", [$id, $anio]);

        //-------------------------------------------------------------------
        $datos = [
            'direccion_domiciliaria' => [
                'tipo_via' => $datosGenerales->cTipoViaNombre ?? '',
                'nombre_via' => $datosGenerales->cFichaDGDireccionNombreVia ?? '',
                'numero_puerta' => $datosGenerales->cFichaDGDireccionNroPuerta ?? '',
                'block' => $datosGenerales->cFichaDGDireccionBlock ?? '',
                'interior' => $datosGenerales->cFichaDGDirecionInterior ?? '',
                'piso' => $datosGenerales->cFichaDGDirecionPiso ?? '',
                'mz' => $datosGenerales->cFichaDGDireccionManzana ?? '',
                'lote' => $datosGenerales->cFichaDGDireccionLote ?? '',
                'km' => $datosGenerales->cFichaDGDireccionKm ?? '',
                'departamento' => $datosGenerales->cDptoNombre ?? '',
                'provincia' => $datosGenerales->cPrvnNombre ?? '',
                'distrito' => $datosGenerales->cDsttNombre ?? '',
                'vive_padre' => $datosGenerales->bFamiliarPadreVive ?? '',
                'vive_madre' => $datosGenerales->bFamiliarMadreVive ?? '',
                'referencia' => $datosGenerales->cFichaDGDireccionReferencia ?? ''
            ],

            'estudiante' => [
                'codigo_alumno' => $datosGenerales->cEstCodigo ?? '',
                'apellido_paterno' => $datosGenerales->cEstPaterno ?? '',
                'apellido_materno' => $datosGenerales->cEstMaterno ?? '',
                'nombres' => $datosGenerales->cEstNombres ?? '',
                'dni' => $datosGenerales->cPersDocumento ?? '',
                'fecha_nacimiento' => date('d-m-Y', strtotime($datosGenerales->dPersNacimiento ?? '')),
                // 'sexo' => $datosGenerales->cPersSexo == 'M' ? 'Masculino' : 'Femenino',
                'sexo' => isset($datosGenerales) && isset($datosGenerales->cPersSexo)
                    ? ($datosGenerales->cPersSexo == 'M' ? 'Masculino' : 'Femenino')
                    : 'No especificado',
                'estado_civil' => $datosGenerales->cTipoEstCivilNombre ?? '',
                'num_hijos' => $datosGenerales->iFichaDGNroHijos ?? '',
                'num_telefono' => $datosGenerales->cEstTelefono ?? '',
            ],

            'nacimiento' => [
                'pais' => $datosGenerales->cPaisNombre ?? '',
                'departamento' => $datosGenerales->cUbigeoDpto ?? '',
                'provincia' => $datosGenerales->cUbigeoProvincia ?? '',
                'distrito' => $datosGenerales->cUbigeoDistrito ?? '',
            ],

        ];

        //---------------------------Direccion de Familiares------------------------------------------------

        $datos['direc_familiares'] = [];

        foreach ($direcc_familiares as $dir_familiar) {
            $datos['direc_familiares'][] = [
                'tipo_via' => $dir_familiar->cTipoViaNombre ?? '',
                'Nombre_via' => $dir_familiar->cFamiliarDireccionNombreVia ?? '',
                'DireccionNroPuerta' => $dir_familiar->cFamiliarDireccionNroPuerta ?? '',
                'DireccionBlock' => $dir_familiar->cFamiliarDireccionBlock ?? '',
                'DirecionInterior' => $dir_familiar->cFamiliarDirecionInterior ?? '',
                'DirecionPiso' => $dir_familiar->cFamiliarDirecionPiso ?? '',
                'DireccionManzana' => $dir_familiar->cFamiliarDireccionManzana ?? '',
                'DireccionLote' => $dir_familiar->cFamiliarDireccionLote ?? '',
                'DireccionKm' => $dir_familiar->cFamiliarDireccionKm ?? '',
                'DireccionReferencia' => $dir_familiar->cFamiliarDireccionReferencia ?? '',

            ];
        }
        //--------------------------------------------------------------------------------------
        $datos['ieducativas'] = [];

        foreach ($ie_procedencia as $ieducativas) {
            $datos['ieducativas'][] = [
                'nombre_iedu' => $ieducativas->cIieeNombre ?? '',
                'tipo_sector' => $ieducativas->cTipoSectorNombre ?? '',

            ];
        }

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
                'departamento' => $familiar->cDptoNombre ?? '',
                'provincia' => $familiar->cPrvnNombre ?? '',
                'distrito' => $familiar->cDsttNombre ?? '',
            ];
        }
        // dd($datos); // Verifica que contiene lo esperado

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
            'lugar_desayuno' => $alimentacionstd->lugarDesayuno ?? '',
            'lugar_almuerzo' => $alimentacionstd->lugarAlmuerzo ?? '',
            'lugar_ceba' => $alimentacionstd->lugarCena ?? '',

        ];

        $datos['pers_discapacidad'] = [];
        foreach ($dispacidad as $discapacidad) {
            $datos['pers_discapacidad'][] = [
                // 'id_ficha'          => $discapacidad->iFichaDGId ?? '',
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


        $datos['seg_salud'] = [];
        foreach ($sis_salud as $index => $seguro_salud) {
            $datos['seg_salud'][] = [
                'seguro_salud' => $seguro_salud->cSegSaludNombre,
                'tip_aporte'  => $seguro_salud->cTipSegAportaNombre,
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

        $datos['religiones'] = [];
        foreach ($religiones as $religion) {
            if (!empty($religion->cReligionNombre)) {
                $datos['religiones'][] = [
                    'religion_nombre' => $religion->cReligionNombre,
                ];
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

        $datos['fam_acompañantes'] = [];
        // $datos['asist_consulta'] = null;
        $datos['asist_consulta'] = $asist_consulta ?? [];
        foreach ($emociones as $index => $emocion) {
            if (!empty($emocion->cTipoFamiliarDescripcion)) {
                $datos['fam_acompañantes'][] = [
                    'nomb_acompañantes' => $emocion->cTipoFamiliarDescripcion,
                ];
            }

            if ($index === 0) {
                $datos['asist_consulta'][] = [
                    'consulta_psicolo' => $emocion->cFichaDGAsistioConsultaPsicologica ?? 'NO',
                ];
            }
        }

        $datos['medio_transporte'] = [];
        foreach ($med_transporte as $mtransporte) {
            $datos['medio_transporte'][] = [
                'transporte_nombre' => $mtransporte->cTransporteNombre,

            ];
        }

        $datos['enfermdad_cronic'] = [];
        foreach ($enfermedad_cronic as $enf_cronica) {
            $datos['enfermdad_cronic'][] = [
                'enfermedad' => $enf_cronica->cDolenciaNombre ?? 'Sin información',
            ];
        }

        $datos['dosis_vacuna'] = [];
        foreach ($dosis_vacunaCovid as $dosis_vacunaCov) {
            $datos['dosis_vacuna'][] = [
                'dosis_vacun' => $dosis_vacunaCov->iPanDFichaNroDosis ?? 'Sin información',
            ];
        }
        //  dd($datos); // Verifica que contiene lo esperado
        $pdf = Pdf::loadView('pdfFicha.ficha', $datos)->setPaper('A4');
        return $pdf->stream("ficha_socioeconomica_{$id}_{$anio}.pdf");
        // return view('pdfFicha.ficha', $datos);
    }
}
