<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BancoPreguntasController extends ApiController
{

    public function guardarPreguntaConAlternativas(Request $request)
    {
        $datosPreguntaJson = json_encode($request->datosPregunta);
        $datosAlternativasJson = json_encode($request->datosAlternativas);
        $params = [
            'ere',
            'preguntas',
            $datosPreguntaJson,
            'alternativas',
            $datosAlternativasJson,
            'iPreguntaId'
        ];

        try {

            $resp = DB::statement(
                'EXEC grl.SP_INS_EnTablaMaestroDetalleDesdeJSON 
                    @Esquema = ?,
                    @TablaMaestra = ?,
                    @DatosJSONMaestro = ?,
                    @TablaDetalle = ?,
                    @DatosJSONDetalles = ?,
                    @campoFK = ?
                ',
                $params
            );

            return $this->successResponse(
                $resp,
                'Datos guardados correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al guardar los datos');
        }
    }
    public function actualizarMatrizPreguntas(Request $request)
    {

        $preguntas = $request->preguntas;

        if (!is_array($preguntas)) {
            return $this->errorResponse(null, 'Datos mal formateados');
        }

        try {
            foreach ($preguntas as $pregunta) {
                $pregunta['datosJson']['bPreguntaEstado'] = 1;
                $datosJson = json_encode($pregunta['datosJson']);

                $condiciones = [
                    [
                        'COLUMN_NAME' => "iPreguntaId",
                        'VALUE' => $pregunta['iPreguntaId']
                    ]
                ];
                $condicionesJson = json_encode($condiciones);

                $params = [
                    'ere',
                    'preguntas',
                    $datosJson,
                    $condicionesJson
                ];
                $resp = DB::statement(
                    'EXEC grl.SP_UPD_EnTablaConJSON
                        @Esquema = ?,
                        @Tabla = ?,
                        @DatosJSON = ?,
                        @CondicionesJSON = ?
                    ',
                    $params
                );
            }

            return $this->successResponse(
                $resp,
                'Datos guardados correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al actualizar los datos');
        }
    }

    public function obtenerBancoPreguntas(Request $request)
    {

        $campos = 'iPreguntaId,iDesempenoId,cPregunta,cPreguntaTextoAyuda,iPreguntaNivel,iPreguntaPeso,dtPreguntaTiempo,bPreguntaEstado,cPreguntaClave';

        $where = '1=1 ';

        $iCompentenciaId = (int) $request->iCompentenciaId ?? 0;
        $bPreguntaEstado = (int) $request->bPreguntaEstado ?? 0;


        if ($iCompentenciaId !== 0) {
            $where .= " AND iCompentenciaId = {$iCompentenciaId}";
        }

        if ($bPreguntaEstado !== -1) {
            $where .= " AND bPreguntaEstado = {$bPreguntaEstado}";
        }

        $params = [
            $request->iCursoId,
            $request->iTipoPregId,
            $request->iDesempenioId,
            $request->bPreguntaEstado
        ];

        try {
            $preguntas = DB::select('exec ere.Sp_SEL_banco_preguntas @_iCursoId = ?,
                @_iTipoPregId = ?, @_iDesempenoId = ?, @_bPreguntaEstado = ?
            ', $params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }
}
