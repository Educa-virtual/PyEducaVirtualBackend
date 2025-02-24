<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Repositories\PreguntasRepository;
use App\Services\Ere\AreasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Illuminate\Http\Response;

class EspecialistasDremoController extends Controller
{
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerEspecialistas()
    {
        $data = DB::select('EXEC [acad].SP_SEL_DocentesXiPerfilId ?', [2]);
        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $data], Response::HTTP_OK);
    }

    public function obtenerAreasPorEspecialista($docenteId)
    {
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $data = DB::select('EXEC acad.SP_SEL_CursoEspecialista @iDocenteId=?', [$docenteIdDescifrado[0]]);
        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $data], Response::HTTP_OK);
    }

    public function asignarAreaEspecialista($docenteId, Request $request)
    {
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        DB::statement("EXEC acad.SP_INS_cursoEspecialistaDremo ?,?", [$docenteIdDescifrado[0], $request->iCursosNivelGradId]);
        return response()->json(['status' => 'Success', 'message' => 'Área asignada correctamente'], Response::HTTP_CREATED);
    }

    public function eliminarAreaEspecialista($docenteId, Request $request)
    {
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        DB::statement("EXEC acad.SP_DEL_cursoEspecialistaDremo ?,?", [$docenteIdDescifrado[0], $request->iCursosNivelGradId]);
        return response()->json(['status' => 'Success', 'message' => 'Se ha eliminado el área'], Response::HTTP_NO_CONTENT);
    }

    public function obtenerAreasPorEvaluacionyEspecialista($evaluacionId, $docenteId)
    {
        $evaluacionIdDescifrado =  $this->hashids->decode($evaluacionId);
        $docenteIdDescifrado =  $this->hashids->decode($docenteId);
        if (empty($evaluacionIdDescifrado) || empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $resultados = DB::select('EXEC [ere].SP_SEL_EvaluacionesEspecialistas @evaluacionId=?, @docenteId=?', [$evaluacionIdDescifrado[0], $docenteIdDescifrado[0]]);

        if (empty($resultados)) {
            return response()->json(['status' => 'Error', 'message' => 'No se encontraron datos para los cursos asociados.'], Response::HTTP_NOT_FOUND);
        }

        foreach ($resultados as $fila) {
            $params = [
                'iEvaluacionId' => $evaluacionIdDescifrado[0],
                'iCursosNivelGradId' => $fila->iCursosNivelGradId,
                'busqueda' => '',
                'iTipoPregId' => 0,
                'bPreguntaEstado' => 1,
                'ids' => NULL
            ];
            $preguntasDB = PreguntasRepository::obtenerBancoPreguntasByParams($params);
            $fila->iEvaluacionid = $evaluacionIdDescifrado[0];
            $fila->iCursosNivelGradId = $this->hashids->encode($fila->iCursosNivelGradId);
            $fila->bTieneArchivo = AreasService::tieneArchivoPdfSubido($fila->iEvaluacionid, $fila->iCursosNivelGradId);
            $fila->iCantidadPreguntas = PreguntasRepository::contarPreguntasEre($preguntasDB);
        }
        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $resultados], Response::HTTP_OK);
    }
}
