<?php

namespace App\Models\bienestar;

use Illuminate\Support\Facades\DB;

class FichaPdf
{
    public static function selDatosGenerales($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                tpv.cTipoViaNombre,
                fic.cTipoViaOtro,
                fic.cFichaDGDireccionNombreVia,
                fic.cFichaDGDireccionNroPuerta,
                fic.cFichaDGDireccionBlock,
                fic.cFichaDGDireccionInterior,
                fic.iFichaDGDireccionPiso,
                fic.cFichaDGDireccionManzana,
                fic.cFichaDGDireccionLote,
                fic.cFichaDGDireccionKm,
                dep.cDptoNombre,
                prv.cPrvnNombre,
                dst.cDsttNombre,
                fic.cFichaDGDireccionReferencia,
                std.cEstCodigo,
                std.cEstTelefono,
                per.cPersPaterno AS cEstPaterno,
                per.cPersMaterno AS cEstMaterno,
                per.cPersNombre AS cEstNombres,
                tid.cTipoIdentSigla,
                per.cPersDocumento,
                per.dPersNacimiento,
                per.cPersSexo,
                tec.cTipoEstCivilNombre,
                fic.iFichaDGNroHijos,
                IIF(fic.bFichaDGTieneHijos = 1, 'SÍ', 'NO') AS bFichaDGTieneHijos,
                pai.cPaisNombre,
                ubg.cUbigeoDpto,
                ubg.cUbigeoProvincia,
                ubg.cUbigeoDistrito,
                IIF(fic.bFamiliarPadreVive = 1, 'SÍ', 'NO') AS bFamiliarPadreVive,
                IIF(fic.bFamiliarMadreVive = 1, 'SÍ', 'NO') AS bFamiliarMadreVive
            FROM obe.ficha_datos_grales AS fic
                INNER JOIN grl.personas per ON 
                    fic.iPersId = per.iPersId
                LEFT JOIN obe.tipo_vias tpv ON 
                    fic.iTipoViaId = tpv.iTipoViaId
                LEFT JOIN grl.departamentos dep ON 
                    per.iDptoId = dep.iDptoId
                LEFT JOIN grl.provincias prv ON 
                    per.iPrvnId = prv.iPrvnId
                LEFT JOIN grl.distritos dst ON 
                    per.iDsttId = dst.iDsttId
                LEFT JOIN grl.paises pai ON 
                    per.iPaisId = pai.iPaisId
                LEFT JOIN acad.estudiantes std ON 
                    fic.iPersId = std.iPersId
                LEFT JOIN grl.ubigeos ubg ON 
                    std.cEstUbigeoNacimiento = ubg.cUbigeoReniec
                LEFT JOIN grl.tipos_Identificaciones tid ON
                    per.iTipoIdentId = tid.iTipoIdentId
                LEFT JOIN grl.tipos_estados_civiles tec ON 
                    per.iTipoEstCivId = tec.iTipoEstCivId
            WHERE fic.iFichaDGId = ?; ", 
            [$iFichaDGId]);
    }

    public static function selFamiliares($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::select("
            SELECT 
                fam.iPersId,
                per.cPersPaterno,
                per.cPersMaterno,
                per.cPersNombre,
                per.dPersNacimiento,
                DATEDIFF(year, per.dPersNacimiento, GETDATE()) AS EdadFam,
                tif.cTipoFamiliarDescripcion,
                tec.cTipoEstCivilNombre,
                gdi.cGradoInstNombre,
                ocp.cOcupacionNombre,
                dep.cDptoNombre,
                prv.cPrvnNombre,
                dst.cDsttNombre,
                CASE 
                    WHEN per.cPersSexo = 'M' THEN 'MASCULINO' 
                    WHEN per.cPersSexo = 'F' THEN 'FEMENINO'
                    ELSE 'NO ESPECIFICADO'
                END AS cPersSexo,
                tid.cTipoIdentSigla,
                per.cPersDocumento,
                IIF(fam.bFamiliarVivoConEl = 1, 'SÍ', 'NO') AS bFamiliarVivoConEl
            FROM obe.ficha_datos_grales AS fic
                INNER JOIN obe.familiares fam ON 
                    fic.iFichaDGId = fam.iFichaDGId
                INNER JOIN grl.personas per ON 
                    fam.iPersId = per.iPersId
                LEFT JOIN grl.tipos_Identificaciones tid ON
                    per.iTipoIdentId = tid.iTipoIdentId
                LEFT JOIN obe.tipo_vias tpv ON 
                    fam.iTipoViaId = tpv.iTipoViaId
                LEFT JOIN obe.tipo_familiares tif ON 
                    fam.iTipoFamiliarId = tif.iTipoFamiliarId
                LEFT JOIN grl.tipos_estados_civiles tec ON 
                    per.iTipoEstCivId = tec.iTipoEstCivId
                LEFT JOIN obe.grado_instrucciones gdi ON 
                    fam.iGradoInstId = gdi.iGradoInstId
                LEFT JOIN obe.ocupaciones ocp ON 
                    fam.iOcupacionId = ocp.iOcupacionId
                LEFT JOIN grl.departamentos dep ON 
                    per.iDptoId = dep.iDptoId
                LEFT JOIN grl.provincias prv ON 
                    per.iPrvnId = prv.iPrvnId
                LEFT JOIN grl.distritos dst ON 
                    per.iDsttId = dst.iDsttId
            WHERE fic.iFichaDGId = ?; ", 
            [$iFichaDGId]);
    }

    public static function selEconomico($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT 
                COALESCE(CAST(iIngresoEcoFamiliar AS VARCHAR), rng.cRangoSueldoDescripcion) AS cRangoSueldoDescripcion,
                COALESCE(CAST(iIngresoEcoEstudiante AS VARCHAR), rnp.cRangoSueldoDescripcion) AS cRangoSueldoDescripcionPersona,
                dep.cDepEcoDescripcion,
                tae.cTipoAEcoDescripcion,
                IIF(bIngresoEcoTrabaja = 1, 'SÍ', 'NO') AS bIngresoEcoTrabaja,
                fiecon.cIngresoEcoActividad,
                fiecon.cIngresoEcoDependeDe,
                fiecon.iIngresoEcoTrabajoHoras,
                jor.cJorTrabDescripcion
            FROM obe.ficha_datos_grales AS fic
                LEFT JOIN obe.ficha_ingresos_economicos fiecon ON 
                    fic.iFichaDGId = fiecon.iFichaDGId
                LEFT JOIN obe.rango_sueldos rng ON 
                    fiecon.iRangoSueldoId = rng.iRangoSueldoId
                LEFT JOIN obe.rango_sueldos rnp ON 
                    fiecon.iRangoSueldoIdPersona = rnp.iRangoSueldoId
                LEFT JOIN obe.depende_economicos dep ON 
                    fiecon.iDepEcoId = dep.iDepEcoId
                LEFT JOIN obe.tipos_apoyo_economicos tae ON 
                    fiecon.iTipoAEcoId = tae.iTipoAEcoId
                LEFT JOIN obe.jornada_trabajos jor ON 
                    fiecon.iJorTrabId = jor.iJorTrabId
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selVivienda($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT 
                COALESCE(cTipoOcupaVivOtro, toc.cTipoOcupaVivDescripcion) AS cTipoOcupaVivDescripcion,
                vcf.iViviendaCarNroPisos,
                COALESCE(cEstadoVivOtro, esv.cEstadoVivDescripcion) AS cEstadoVivDescripcion,
                COALESCE(cMatPreOtro, mpa.cMatPreDescripcion) AS cMatPreDescripcion,
                COALESCE(cMatPisoVivOtro, mpp.cMatPisoVivDescripcion) AS cMatPisoVivDescripcion,
                COALESCE(cMatTecVivOtro, mte.cMatTecVivDescripcion) AS cMatTecVivDescripcion,
                COALESCE(cTipoVivOtro, tipviv.cTipoVivDescripcion) AS cTipoVivDescripcion,
                vcf.iViviendaCarNroAmbientes,
                vcf.iViviendaCarNroHabitaciones,
                COALESCE(cTipoSumAOtro, sua.cTipoSumADescripcion) AS cTipoSumADescripcion,
                COALESCE(cTipoSsHhOtro, tsh.cTipoSsHhDescripcion) AS cTipoSsHhDescripcion,
                CONCAT_WS(', ', alu.cTipoAlumDescripcion, vcf.cTipoAlumOtro) AS cTipoAlumDescripcion
            FROM obe.ficha_datos_grales AS fic
                INNER JOIN obe.vienda_caracteristicas_fichas vcf ON 
                    fic.iFichaDGId = vcf.iFichaDGId
                LEFT JOIN obe.tipo_ocupacion_viviendas toc ON 
                    vcf.iTipoOcupaVivId = toc.iTipoOcupaVivId
                LEFT JOIN obe.estado_viviendas esv ON 
                    vcf.iEstadoVivId = esv.iEstadoVivId
                LEFT JOIN obe.material_predominantes mpa ON 
                    vcf.iMatPreId = mpa.iMatPreId
                LEFT JOIN obe.material_piso_viviendas mpp ON 
                    vcf.iMatPisoVivId = mpp.iMatPisoVivId
                LEFT JOIN obe.material_techo_viviendas mte ON 
                    vcf.iMatTecVivId = mte.iMatTecVivId
                LEFT JOIN obe.tipo_viviendas tipviv ON 
                    vcf.iTipoVivId = tipviv.iTipoVivId
                LEFT JOIN obe.tipos_suministro_agua sua ON 
                    vcf.iTipoSumAId = sua.iTipoSumAId
                LEFT JOIN obe.tipos_sshh_viviendas tsh ON 
                    vcf.iTiposSsHhId = tsh.iTiposSsHhId
                LEFT JOIN (
                    SELECT
                        alv.iViendaCarId,
                        STRING_AGG(alu.cTipoAlumDescripcion, ', ') AS cTipoAlumDescripcion
                    FROM obe.tipos_alumbrado_viviendas alv
                        LEFT JOIN obe.tipos_alumbrado alu ON 
                            alv.iTipoAlumId = alu.iTipoAlumId
                    WHERE alu.iTipoAlumId > 1
                    GROUP BY iViendaCarId
                ) alu ON 
                    alu.iViendaCarId = vcf.iViendaCarId
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selEquipamiento($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::select("
            SELECT 
                vcf.iFichaDGId,
                ele.iViendaCarId,
                IIF(epv.iEleParaVivId = 1, vcf.cEleParaVivOtro, epv.cEleParaVivDescripcion) AS cEleParaVivDescripcion
            FROM obe.ficha_datos_grales AS fic
                LEFT JOIN obe.vienda_caracteristicas_fichas vcf ON
                    fic.iFichaDGId = vcf.iFichaDGId
                LEFT JOIN obe.elementos_viviendas ele ON
                    vcf.iViendaCarId = ele.iViendaCarId
                LEFT JOIN obe.elementos_para_vivienda epv ON
                    ele.iEleParaVivId = epv.iEleParaVivId
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selAlimentacion($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                fic.iFichaDGId,
                COALESCE(cLugarAlimDesayuno, des.cLugAlimDescripcion) AS lugarDesayuno,
                COALESCE(cLugarAlimAlmuerzo, alm.cLugAlimDescripcion) AS lugarAlmuerzo,
                COALESCE(cLugarAlimCena, cen.cLugAlimDescripcion) AS lugarCena,
                IIF(bDietaEspecial = 1, 'SÍ', 'NO') AS bDietaEspecial,
                cDietaEspecialObs,
                IIF(bIntoleranciaAlim = 1, 'SÍ', 'NO') AS bIntoleranciaAlim,
                cIntoleranciaAlimObs,
                IIF(bSumplementosAlim = 1, 'SÍ', 'NO') AS bSumplementosAlim,
                cSumplementosAlimObs,
                IIF(bDificultadAlim = 1, 'SÍ', 'NO') AS bDificultadAlim,
                cDificultadAlimObs,
                cAlimObs
            FROM obe.ficha_datos_grales AS fic
                INNER JOIN obe.alimentacion_fichas AS af ON 
                    fic.iFichaDGId = af.iFichaDGId
                LEFT JOIN obe.lugar_alimentacion AS des ON 
                    af.iLugarAlimIdDesayuno = des.iLugAlimId
                LEFT JOIN obe.lugar_alimentacion AS alm ON 
                    af.iLugarAlimIdAlmuerzo = alm.iLugAlimId
                LEFT JOIN obe.lugar_alimentacion AS cen ON 
                    af.iLugarAlimIdCena = cen.iLugAlimId
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selProgramasAlimentacion($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                STRING_AGG(cProgAlimNombre, ', ') AS cProgAlimNombre
            FROM obe.programas_alimentarios_ficha paf
                INNER JOIN obe.programas_alimentarios pal ON
                    pal.iProgAlimId = paf.iProgAlimId
            WHERE paf.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selTieneDiscapacidad($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                CASE WHEN COALESCE(cFichaDGCodigoOMAPED, '') = '' AND
                    COALESCE(cFichaDGCodigoCONADIS, '') = '' AND 
                    COALESCE(cOtroProgramaDiscapacidad, '') = '' THEN 0 ELSE 1
                END AS EstaEnProgramaDiscapacidad
            FROM obe.ficha_datos_grales
            WHERE iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selProgramasDiscapacidad($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                fic.iFichaDGId,
                IIF(fic.bFichaDGEstaEnOMAPED = 1,'SÍ','NO') AS EstaEnOMAPED,
                cFichaDGCodigoOMAPED,
                IIF(fic.bFichaDGEstaEnCONADIS = 1, 'SÍ', 'NO') AS EstaEnCONADIS,
                cFichaDGCodigoCONADIS,
                IIF(fic.bOtroProgramaDiscapacidad = 1, 'SÍ', 'NO') AS EstaOtroProgramaDiscapacidad,
                cOtroProgramaDiscapacidad
            FROM obe.ficha_datos_grales fic
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selDiscapacidades($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::select("
            SELECT
                dpf.iDiscId,
                dis.cDiscNombre,
                dpf.cDiscFichaObs
            FROM obe.discapcidades_fichas dpf
                INNER JOIN obe.discapacidades dis ON
                    dpf.iDiscId = dis.iDiscId
            WHERE dpf.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selSalud($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                fic.cFichaDGAlergiaMedicamentos,
                fic.cFichaDGAlergiaOtros
            FROM obe.ficha_datos_grales fic
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selDolenciasSalud($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::select("
            SELECT
                dol.cDolenciaNombre,
                dlf.cDolFichaObs
            FROM obe.dolencias_fichas dlf
                INNER JOIN obe.dolencias dol ON
                    dlf.iDolenciaId = dol.iDolenciaId
            WHERE dlf.iFichaDGId = ?;
            ", [$iFichaDGId]);
    }

    public static function selSeguros($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT STRING_AGG(sis.cSegSaludNombre, ', ') AS cSegSaludNombre
            FROM obe.seguros_aportacion AS sap
                LEFT JOIN obe.seguros_salud sis ON sis.iSegSaludId = sap.iSegSaludId
            WHERE sap.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selDosisVacunas($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::select("
            SELECT
                pdf.iPanDFichaId,
                pdf.dtPanDFichaDosis,
                pdf.iPanDFichaNroDosis,
                pan.cPandemiaNombre
            FROM obe.pandemia_dosis_fichas pdf
                INNER JOIN obe.pandemias pan ON
                    pdf.iPandemiaId = pan.iPandemiaId
            WHERE pdf.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }

    public static function selRecreacion($request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        return DB::selectOne("
            SELECT
                CONCAT_WS(', ', cDeporteNombre, cDeporteOtro) AS cDeporteNombre,
                cFichaDGPerteneceLigaDeportiva,
                COALESCE(cReligionOtro, rel.cReligionNombre) AS cReligionNombre,
                cFichaDGPerteneceCentroArtistico,
                cActArtisticaNombre,
                CONCAT_WS(', ', cPasaTiempoNombre, cPasaTiempoOtro) AS cPasaTiempoNombre,
                cFichaDGAsistioConsultaPsicologica,
                CONCAT_WS(', ', cTransporteNombre, cTransporteOtro) AS cTransporteNombre,
                CONCAT_WS(', ', cTipoFamiliarDescripcion, cTipoFamiliarOtro) AS cTipoFamiliarDescripcion,
                CASE
                    WHEN iEstadoRelFamiliar = 1 THEN 'BUENA'
                    WHEN iEstadoRelFamiliar = 2 THEN 'REGULAR'
                    WHEN iEstadoRelFamiliar = 3 THEN 'MALA'
                    ELSE 'NO ESPECIFICADO'
                END AS cEstadoRelFamiliar
            FROM obe.ficha_datos_grales fic
                LEFT JOIN obe.religiones rel ON
                    fic.iReligionId = rel.iReligionId
                LEFT JOIN (
                    SELECT
                        iFichaDGId,
                        STRING_AGG(dep.cDeporteNombre, ', ') AS cDeporteNombre
                    FROM obe.deportes_fichas dpf
                    INNER JOIN obe.deportes dep ON
                        dpf.iDeporteId = dep.iDeporteId
                    WHERE dep.iDeporteId > 1
                    GROUP BY dpf.iFichaDGId
                ) dep ON
                    fic.iFichaDGId = dep.iFichaDGId
                LEFT JOIN (
                    SELECT
                        iFichaDGId,
                        STRING_AGG(pas.cPasaTiempoNombre, ', ') AS cActArtisticaNombre
                    FROM obe.pasatiempo_fichas paf
                    INNER JOIN obe.pasatiempos pas ON
                        paf.iPasaTiempoId = pas.iPasaTiempoId
                    WHERE
                        pas.iPasaTiempoId > 1 AND
                        pas.bPasaTiempoEsActividadArtistica = 1
                    GROUP BY paf.iFichaDGId
                ) art ON
                    art.iFichaDGId = fic.iFichaDGId
                LEFT JOIN (
                    SELECT
                        iFichaDGId,
                        STRING_AGG(pas.cPasaTiempoNombre, ', ') AS cPasaTiempoNombre
                    FROM obe.pasatiempo_fichas paf
                    INNER JOIN obe.pasatiempos pas ON
                        paf.iPasaTiempoId = pas.iPasaTiempoId
                    WHERE
                        pas.iPasaTiempoId > 1 AND
                        pas.bPasaTiempoEsActividadArtistica = 0
                    GROUP BY paf.iFichaDGId
                ) pas ON
                    pas.iFichaDGId = fic.iFichaDGId
                LEFT JOIN (
                    SELECT
                        iFichaDGId,
                        STRING_AGG(tra.cTransporteNombre, ', ') AS cTransporteNombre
                    FROM obe.transportes_fichas trf
                    INNER JOIN obe.transportes tra ON
                        tra.iTransporteId = trf.iTransporteId
                    WHERE trf.iTransporteId > 1
                    GROUP BY trf.iFichaDGId
                ) tra ON
                    tra.iFichaDGId = fic.iFichaDGId
                LEFT JOIN (
                    SELECT
                        iFichaDGId,
                        STRING_AGG(tif.cTipoFamiliarDescripcion, ', ') AS cTipoFamiliarDescripcion
                    FROM obe.problemas_emocionales_ficha pef
                    INNER JOIN obe.tipo_familiares tif ON
                        pef.iTipoFamiliarId = tif.iTipoFamiliarId
                    WHERE tif.iTipoFamiliarId > 1
                    GROUP BY pef.iFichaDGId
                ) tif ON
                    tif.iFichaDGId = fic.iFichaDGId
            WHERE fic.iFichaDGId = ?; ",
            [$iFichaDGId]);
    }
}