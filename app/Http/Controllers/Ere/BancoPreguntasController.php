<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\ApiController;
use App\Repositories\AlternativaPreguntaRespository;
use DateTime;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BancoPreguntasController extends ApiController
{
    protected  $alternativaPreguntaRespository;

    public function __construct(AlternativaPreguntaRespository $alternativaPreguntaRespository)
    {
        $this->alternativaPreguntaRespository = $alternativaPreguntaRespository;
    }

    public function guardarActualizarPreguntaConAlternativas(Request $request)
    {
        $fechaActual = new DateTime();
        $fechaActual->setTime(0, 0, 0);
        $hora = $request->iHoras;
        $minutos = $request->iMinutos;
        $segundos = $request->iSegundos;
        $fechaActual->setTime($hora, $minutos, $segundos);
        $fechaConHora = $fechaActual->format('d-m-Y H:i:s');

        $params = [
            (int) $request->iPreguntaId,
            (int)$request->iCursoId,
            (int)$request->iTipoPregId,
            $request->cPregunta,
            $request->cPreguntaTextoAyuda,
            (int)$request->iPreguntaNivel,
            (int)$request->iPreguntaPeso,
            $fechaConHora,
            $request->bPreguntaEstado,
            $request->cPreguntaClave,
        ];

        DB::beginTransaction();
        $resp = null;
        try {
            $resp = DB::select('exec ere.Sp_INS_UPD_pregunta 
                @_iPreguntaId = ?
                , @_iCursoId = ?
                , @_iTipoPregId = ?
                , @_cPregunta = ?
                , @_cPreguntaTextoAyuda = ?
                , @_iPreguntaNivel  = ?
                , @_iPreguntaPeso = ?
                , @_dtPreguntaTiempo = ?
                , @_bPreguntaEstado = ?
                , @_cPreguntaClave = ?
            ', $params);
            $resp = $resp[0];
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e, 'Error al guardar los datos');
        }

        try {

            foreach ($request->datosAlternativas as $item) {
                $paramsAlternativa = [
                    $item['isLocal'] ?? false ? 0 : (int) $item['iAlternativaId'],
                    (int) $resp->id,
                    $item['cAlternativaDescripcion'],
                    $item['cAlternativaLetra'],
                    $item['bAlternativaCorrecta'] ? 1 : 0,
                    $item['cAlternativaExplicacion']
                ];
                $this->alternativaPreguntaRespository->guardarActualizarAlternativa($paramsAlternativa);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = $this->returnError($e, 'Error al guardar los datos');
            return $this->errorResponse($e, $message);
        }

        DB::commit();
        return $this->successResponse($resp, $resp->mensaje);
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

        $params = [
            $request->iCursoId,
            $request->busqueda ?? '',
            $request->iTipoPregId,
            $request->bPreguntaEstado
        ];


        try {
            $preguntas = DB::select('exec ere.Sp_SEL_banco_preguntas @_iCursoId = ?,
             @_busqueda = ?, @_iTipoPregId = ?, @_bPreguntaEstado = ?
            ', $params);

            return $this->successResponse(
                $preguntas,
                'Datos obtenidos correctamente'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e, 'Error al obtener los datos');
        }
    }


    public function eliminarBancoPreguntasById(Request $request, $id)
    {
        $params = [
            $id
        ];

        try {

            $resp = DB::select('exec ere.Sp_DEL_pregunta @_iPreguntaId = ?', $params);

            if (count($resp) === 0) {
                return $this->errorResponse($resp, 'Error al eliminar la pregunta.');
            }

            $resp = $resp[0];

            return $this->successResponse($resp, $resp->mensaje);
        } catch (Exception $e) {
            $message = $this->returnError($e, 'Error al eliminar la pregunta');
            return $this->errorResponse($e, $message);
        }
    }
}
