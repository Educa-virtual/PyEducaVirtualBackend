<?php
namespace App\Http\Controllers\bienestar;
use Barryvdh\DomPDF\Facade\Pdf;


use App\Http\Controllers\Controller;
use App\Models\bienestar\FichaPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para usar consultas SQL crudas

class FichaPdfController extends Controller
{
    public function descargarFicha(Request $request)
    {
        $iFichaDGId = $request->get('iFichaDGId');

        //---------------- Datos generales y de dirección------------
        $datosGenerales = FichaPdf::selDatosGenerales($request);
        $familiares = FichaPdf::selFamiliares($request);
        $aspeconimico = FichaPdf::selEconomico($request);

        // ---------IV ASPECTO DE LA VIVIENDA (DODE VIVE EL ESTUDIANTE----------------------------	
        $aspvivienda = FichaPdf::selVivienda($request);

        // ----------EQUIPAMIENTO EN EL HOGAR--------------------------
        $equipamiento = FichaPdf::selEquipamiento($request);
        $alimentacionstd = FichaPdf::selAlimentacion($request);
        $programas_alimentacion = FichaPdf::selProgramasAlimentacion($request);

        //--------------VI. DISCAPACIDAD-------------------------------------
        $tiene_discapacidad = FichaPdf::selTieneDiscapacidad($request);
        $programas_discapacidad = FichaPdf::selProgramasDiscapacidad($request);
        $discapacidades = FichaPdf::selDiscapacidades($request);

        // ---------------VII SALUD----------------------------------------
        $ficha_salud = FichaPdf::selSalud($request);
        $dolencias_salud = FichaPdf::selDolenciasSalud($request);
        $seguros_salud = FichaPdf::selSeguros($request);
        $dosis_vacunas = FichaPdf::selDosisVacunas($request);

        // ------------VIII-RECREACION------------------
        $recreacion = FichaPdf::selRecreacion($request);

        //-------------------------------------------------------------------
        $datos = [
            'direccion_domiciliaria' => [
                'tipo_via' => $datosGenerales->cTipoViaNombre ?? $datosGenerales->cTipoViaOtro ?? '',
                'nombre_via' => $datosGenerales->cFichaDGDireccionNombreVia ?? '',
                'numero_puerta' => $datosGenerales->cFichaDGDireccionNroPuerta ?? '',
                'block' => $datosGenerales->cFichaDGDireccionBlock ?? '',
                'interior' => $datosGenerales->cFichaDGDireccionInterior ?? '',
                'piso' => $datosGenerales->iFichaDGDireccionPiso ?? '',
                'mz' => $datosGenerales->cFichaDGDireccionManzana ?? '',
                'lote' => $datosGenerales->cFichaDGDireccionLote ?? '',
                'km' => $datosGenerales->cFichaDGDireccionKm ?? '',
                'departamento' => $datosGenerales->cDptoNombre ?? '',
                'provincia' => $datosGenerales->cPrvnNombre ?? '',
                'distrito' => $datosGenerales->cDsttNombre ?? '',
                'referencia' => $datosGenerales->cFichaDGDireccionReferencia ?? ''
            ],

            'estudiante' => [
                'codigo_alumno' => $datosGenerales->cEstCodigo ?? '',
                'apellido_paterno' => $datosGenerales->cEstPaterno ?? '',
                'apellido_materno' => $datosGenerales->cEstMaterno ?? '',
                'nombres' => $datosGenerales->cEstNombres ?? '',
                'tipo_documento' => $datosGenerales->cTipoIdentSigla ?? '',
                'documento' => $datosGenerales->cPersDocumento ?? '',
                'fecha_nacimiento' => date('d/m/Y', strtotime($datosGenerales->dPersNacimiento ?? '')),
                // 'sexo' => $datosGenerales->cPersSexo == 'M' ? 'Masculino' : 'Femenino',
                'sexo' => isset($datosGenerales) && isset($datosGenerales->cPersSexo)
                    ? ($datosGenerales->cPersSexo == 'M' ? 'MASCULINO' : 'FEMENINO')
                    : 'No especificado',
                'estado_civil' => $datosGenerales->cTipoEstCivilNombre ?? '',
                'vive_padre' => $datosGenerales->bFamiliarPadreVive ?? '',
                'vive_madre' => $datosGenerales->bFamiliarMadreVive ?? '',
                'num_hijos' => $datosGenerales->iFichaDGNroHijos ?? '',
                'tiene_hijos' => $datosGenerales->bFichaDGTieneHijos ?? '',
                'num_telefono' => $datosGenerales->cEstTelefono ?? '',
            ],

            'nacimiento' => [
                'pais' => $datosGenerales->cPaisNombre ?? '',
                'departamento' => $datosGenerales->cUbigeoDpto ?? '',
                'provincia' => $datosGenerales->cUbigeoProvincia ?? '',
                'distrito' => $datosGenerales->cUbigeoDistrito ?? '',
            ],

        ];

        //--------------------------- Familiares ------------------------------------------------

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
                'comparte_vivienda' => $familiar->bFamiliarVivoConEl ?? '',
                'tipo_documento' => $familiar->cTipoIdentSigla ?? '',
                'documento' => $familiar->cPersDocumento ?? '',
                'sexo' => $familiar->cPersSexo ?? '',
            ];
        }
        // dd($datos); // Verifica que contiene lo esperado

        $datos['aspecto_economico'] = [
            'rango_ingresos' => $aspeconimico->cRangoSueldoDescripcion ?? '',
            'rango_ingresos_apoderado' => $aspeconimico->cRangoSueldoDescripcionPersona ?? '',
            'depende_economicamente_de' => $aspeconimico->cDepEcoDescripcion ?? '',
            'tipo_apoyo_economico' => $aspeconimico->cTipoAEcoDescripcion ?? '',
            'actividad_ingreso' => $aspeconimico->cIngresoEcoActividad ?? '',
            'estudiante_trabaja' => $aspeconimico->bIngresoEcoTrabaja ?? '',
            'apoderado_depende_de' => $aspeconimico->cIngresoEcoDependeDe ?? '',
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
            'lugar_cena' => $alimentacionstd->lugarCena ?? '',
            'tiene_dieta_especial' => $alimentacionstd->bDietaEspecial ?? '',
            'dieta_especial' => $alimentacionstd->cDietaEspecialObs ?? '',
            'tiene_intolerancia' => $alimentacionstd->bIntoleranciaAlim ?? '',
            'intolerancia_alimenticia' => $alimentacionstd->cIntoleranciaAlimObs ?? '',
            'toma_suplementos' => $alimentacionstd->bSumplementosAlim ?? '',
            'suplemenetos_alimenticios' => $alimentacionstd->cSumplementosAlimObs ?? '',
            'tiene_dificultades' => $alimentacionstd->bDificultadAlim ?? '',
            'dificultades_alimenticias' => $alimentacionstd->cDificultadAlimObs ?? '',
            'observaciones_alimenticias' => $alimentacionstd->cAlimObs ?? '',
            'programas_alimentacion' => $programas_alimentacion->cProgAlimNombre ?? '',
        ];

        $datos['programas_discapacidad'] = [
            'tiene_discapacidad' => $tiene_discapacidad->EstaEnProgramaDiscapacidad == 1 && count($discapacidades) > 0 ? true : false,
            'esta_en_omaped' => $programas_discapacidad->EstaEnOMAPED ?? '',
            'codigo_omaped' => $programas_discapacidad->cFichaDGCodigoOMAPED ?? '',
            'esta_en_conadis' => $programas_discapacidad->EstaEnCONADIS ?? '',
            'codigo_conadis' => $programas_discapacidad->cFichaDGCodigoCONADIS ?? '',
            'esta_otro_programa' => $programas_discapacidad->EstaOtroProgramaDiscapacidad ?? '',
            'codigo_otro_programa' => $programas_discapacidad->cOtroProgramaDiscapacidad ?? '',
        ];

        $datos['discapacidades'] = [];
        foreach ($discapacidades as $discapacidad) {
            $datos['discapacidades'][] = [
                'nomb_discapacidad' => $discapacidad->cDiscNombre ?? '',
                'observaciones' => $discapacidad->cDiscFichaObs ?? '',
            ];
        }

        $datos['ficha_salud'] = [];
        $datos['ficha_salud'] = [
            'AlergiaMedicamentos' => $ficha_salud->cFichaDGAlergiaMedicamentos ?? '',
            'AlergiaOtros' => $ficha_salud->cFichaDGAlergiaOtros ?? '',
            'SeguroSalud' => $seguros_salud->cSegSaludNombre ?? '',
        ];

        foreach ($dolencias_salud as $index => $dolencia) {
            $datos['dolencias_salud'][] = [
                'nomb_dolencia' => $dolencia->cDolenciaNombre,
                'observaciones' => $dolencia->cDolFichaObs,
            ];
        }

        foreach ($dosis_vacunas as $index => $dosis) {
            $datos['dosis_vacuna'][] = [
                'pandemia' => $dosis->cPandemiaNombre,
                'num_dosis' => $dosis->iPanDFichaNroDosis,
                'fecha_dosis' => isset($dosis->dtPanDFichaDosis) ? date('d-m-Y', strtotime($dosis->dtPanDFichaDosis)) : '',
            ];
        }

        $datos['recreacion'] = [];
        $datos['recreacion'] = [
            'deportes' => $recreacion->cDeporteNombre ?? '',
            'religion' => $recreacion->cReligionNombre ?? '',
            'centro_artistico' => $recreacion->cFichaDGPerteneceCentroArtistico ?? '',
            'act_artistica' => $recreacion->cActArtisticaNombre ?? '',
            'pasatiempo' => $recreacion->cPasaTiempoNombre ?? '',
            'transporte' => $recreacion->cTransporteNombre ?? '',
            'problemas_emocionales' => $recreacion->cTipoFamiliarDescripcion ?? '',
            'relacion_familiar' => $recreacion->cEstadoRelFamiliar ?? '',
            'liga_deportiva' => $recreacion->cFichaDGPerteneceLigaDeportiva ?? '',
            'centro_artistico' => $recreacion->cFichaDGPerteneceCentroArtistico ?? '',
            'consulta_psicologica' => $recreacion->cFichaDGAsistioConsultaPsicologica ?? '',
        ];

        //  dd($datos); // Verifica que contiene lo esperado
        $pdf = Pdf::loadView('pdfFicha.ficha', $datos)->setPaper('A4');
        return $pdf->stream("ficha_socioeconomica_{$iFichaDGId}.pdf");
        // return view('pdfFicha.ficha', $datos);
    }
}
