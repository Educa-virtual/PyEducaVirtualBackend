<?php
namespace App\Http\Controllers\bienestar;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\bienestar\Ficha;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FichaPdfController extends Controller
{
    private $permitidos = [
        Perfil::ESTUDIANTE,
        Perfil::APODERADO,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
        Perfil::DOCENTE,
        Perfil::DIRECTOR_IE,
        Perfil::SUBDIRECTOR_IE,
        Perfil::ASISTENTE_SOCIAL,
    ];

    public function descargarFicha(Request $request)
    {
        try {

            Gate::authorize('tiene-perfil', [$this->permitidos]);

            $iFichaDGId = $request->get('iFichaDGId');
            $datos_ficha = Ficha::selFichaImpresion($request);

            $ficha_estudiante = $datos_ficha->es_estudiante_apoderado == 1 ? true : false;
            /* DATOS GENERALES, DIRECCIÓN Y FAMILIA */
            $datos_generales = json_decode($datos_ficha->datos_generales)[0] ?? [];
            $familiares = json_decode($datos_ficha->familiares) ?? [];
            $aspecto_economico = json_decode($datos_ficha->aspecto_economico)[0] ?? [];

            /* VIVIENDA */
            $aspecto_vivienda = json_decode($datos_ficha->aspecto_vivienda)[0] ?? [];
            $equipamiento = json_decode($datos_ficha->equipamiento);

            /* ALIMENTACION */
            $alimentacion = json_decode($datos_ficha->alimentacion)[0] ?? [];
            $programas_alimentacion = json_decode($datos_ficha->programas_alimentacion)[0] ?? [];

            /* DISCAPACIDAD */
            $tiene_discapacidad = json_decode($datos_ficha->tiene_discapacidad)[0] ?? [];
            $programas_discapacidad = json_decode($datos_ficha->programas_discapacidad)[0] ?? [];
            $discapacidades = json_decode($datos_ficha->discapacidades) ?? [];

            /* SALUD */
            $ficha_salud = json_decode($datos_ficha->ficha_salud)[0] ?? [];
            $dolencias_salud = json_decode($datos_ficha->dolencias_salud) ?? [];
            $seguros_salud = json_decode($datos_ficha->seguros_salud)[0] ?? [];
            $dosis_vacunas = json_decode($datos_ficha->dosis_vacunas) ?? [];

            /* RECREACION, CULTURA Y OTROS */
            $recreacion = json_decode($datos_ficha->recreacion)[0] ?? [];

        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }

        /* FORMATEAR DATOS PARA IMPRESION DE LA FICHA */

        $datos = [
            'ficha_estudiante' => $ficha_estudiante,

            'direccion_domiciliaria' => [
                'tipo_via' => $datos_generales->cTipoViaNombre ?? $datos_generales->cTipoViaOtro ?? '',
                'nombre_via' => $datos_generales->cFichaDGDireccionNombreVia ?? '',
                'numero_puerta' => $datos_generales->cFichaDGDireccionNroPuerta ?? '',
                'block' => $datos_generales->cFichaDGDireccionBlock ?? '',
                'interior' => $datos_generales->cFichaDGDireccionInterior ?? '',
                'piso' => $datos_generales->iFichaDGDireccionPiso ?? '',
                'mz' => $datos_generales->cFichaDGDireccionManzana ?? '',
                'lote' => $datos_generales->cFichaDGDireccionLote ?? '',
                'km' => $datos_generales->cFichaDGDireccionKm ?? '',
                'departamento' => $datos_generales->cDptoNombre ?? '',
                'provincia' => $datos_generales->cPrvnNombre ?? '',
                'distrito' => $datos_generales->cDsttNombre ?? '',
                'referencia' => $datos_generales->cFichaDGDireccionReferencia ?? ''
            ],

            'persona' => [
                'codigo_alumno' => $datos_generales->cEstCodigo ?? '',
                'apellido_paterno' => $datos_generales->cEstPaterno ?? '',
                'apellido_materno' => $datos_generales->cEstMaterno ?? '',
                'nombres' => $datos_generales->cEstNombres ?? '',
                'tipo_documento' => $datos_generales->cTipoIdentSigla ?? '',
                'documento' => $datos_generales->cPersDocumento ?? '',
                'fecha_nacimiento' => date('d/m/Y', strtotime($datos_generales->dPersNacimiento ?? '')),
                // 'sexo' => $datos_generales->cPersSexo == 'M' ? 'Masculino' : 'Femenino',
                'sexo' => isset($datos_generales) && isset($datos_generales->cPersSexo)
                    ? ($datos_generales->cPersSexo == 'M' ? 'MASCULINO' : 'FEMENINO')
                    : 'No especificado',
                'estado_civil' => $datos_generales->cTipoEstCivilNombre ?? '',
                'vive_padre' => $datos_generales->bFamiliarPadreVive ?? '',
                'vive_madre' => $datos_generales->bFamiliarMadreVive ?? '',
                'num_hijos' => $datos_generales->iFichaDGNroHijos ?? '',
                'tiene_hijos' => $datos_generales->bFichaDGTieneHijos ?? '',
                'vive_con_padres' => $datos_generales->bFamiliarPadresVivenJuntos ?? '',
                'num_telefono' => $datos_generales->cPersTelefono ?? '',
            ],

            'nacimiento' => [
                'pais' => $datos_generales->cPaisNombre ?? '',
                'departamento' => $datos_generales->cUbigeoDpto ?? '',
                'provincia' => $datos_generales->cUbigeoProvincia ?? '',
                'distrito' => $datos_generales->cUbigeoDistrito ?? '',
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
                'fecha_nacimiento' => isset($familiar->dPersNacimiento) ? date('d/m/Y', strtotime($familiar->dPersNacimiento)) : '',
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
                'celular' => $familiar->cFamiliarNroCelular ?? '',
            ];
        }
        // dd($datos); // Verifica que contiene lo esperado

        $datos['aspecto_economico'] = [
            'rango_ingresos' => $aspecto_economico->cRangoSueldoDescripcion ?? '',
            'rango_ingresos_apoderado' => $aspecto_economico->cRangoSueldoDescripcionPersona ?? '',
            'depende_economicamente_de' => $aspecto_economico->cDepEcoDescripcion ?? '',
            'tipo_apoyo_economico' => $aspecto_economico->cTipoAEcoDescripcion ?? '',
            'actividad_ingreso' => $aspecto_economico->cIngresoEcoActividad ?? '',
            'apoderado_jefe_trabaja' => $aspecto_economico->bIngresoEcoTrabaja ?? '',
            'apoderado_depende_de' => $aspecto_economico->cIngresoEcoDependeDe ?? '',
            'horas_trabajo' => $aspecto_economico->iIngresoEcoTrabajoHoras ?? '',
            'jornada_trabajo' => $aspecto_economico->cJorTrabDescripcion ?? '',
        ];

        $datos['aspecto_vivienda'] = [
            'vivienda_es' => $aspecto_vivienda->cTipoOcupaVivDescripcion ?? '',
            'npisos' => $aspecto_vivienda->iViviendaCarNroPisos ?? '',
            'estado' => $aspecto_vivienda->cEstadoVivDescripcion ?? '',
            'mat_pred_pared' => $aspecto_vivienda->cMatPreDescripcion ?? '',
            'mat_piso' => $aspecto_vivienda->cMatPisoVivDescripcion ?? '',
            'mat_techo' => $aspecto_vivienda->cMatTecVivDescripcion ?? '',
            'tipo_vivienda' => $aspecto_vivienda->cTipoVivDescripcion ?? '',
            'nro_ambientes' => $aspecto_vivienda->iViviendaCarNroAmbientes ?? '',
            'nro_habitaciones' => $aspecto_vivienda->iViviendaCarNroHabitaciones ?? '',
            'tipo_servicio' => $aspecto_vivienda->cTipoSumADescripcion ?? '',
            'tipo_sshh' => $aspecto_vivienda->cTipoSsHhDescripcion ?? '',
            'tipo_alumbrado' => $aspecto_vivienda->cTipoAlumDescripcion ?? '',
        ];

        $datos['equipamiento'] = [];
        foreach ($equipamiento as $equipos) {
            $datos['equipamiento'][] = [
                'electrodm_hogar' => $equipos->cEleParaVivDescripcion ?? '',
            ];
        };

        $datos['alimentos_std'] = [
            'lugar_desayuno' => $alimentacion->lugarDesayuno ?? '',
            'lugar_almuerzo' => $alimentacion->lugarAlmuerzo ?? '',
            'lugar_cena' => $alimentacion->lugarCena ?? '',
            'tiene_dieta_especial' => $alimentacion->bDietaEspecial ?? '',
            'dieta_especial' => $alimentacion->cDietaEspecialObs ?? '',
            'tiene_intolerancia' => $alimentacion->bIntoleranciaAlim ?? '',
            'intolerancia_alimenticia' => $alimentacion->cIntoleranciaAlimObs ?? '',
            'toma_suplementos' => $alimentacion->bSumplementosAlim ?? '',
            'suplemenetos_alimenticios' => $alimentacion->cSumplementosAlimObs ?? '',
            'tiene_dificultades' => $alimentacion->bDificultadAlim ?? '',
            'dificultades_alimenticias' => $alimentacion->cDificultadAlimObs ?? '',
            'observaciones_alimenticias' => $alimentacion->cAlimObs ?? '',
            'programas_alimentacion' => $programas_alimentacion->cProgAlimNombre ?? '',
            'alergias_alimenticias' => $alimentacion->cFichaDGAlergiaAlimentos ?? '',

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
                'fecha_dosis' => isset($dosis->dtPanDFichaDosis) ? date('d/m/Y', strtotime($dosis->dtPanDFichaDosis)) : '',
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
            'lengua_materna' => $recreacion->cLenguaNombre ?? '',
            'etnia' => $recreacion->cEtniaNombre ?? '',
        ];

        //  dd($datos); // Verifica que contiene lo esperado
        $pdf = Pdf::loadView('bienestar.ficha_socioeconomica_pdf', $datos)->setPaper('A4');
        return $pdf->stream("ficha_socioeconomica_{$iFichaDGId}.pdf");
        // return view('pdfFicha.ficha', $datos);
    }
}
