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

class EspecialistasUgelController extends Controller
{
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerEspecialistas()
    {
        $data = DB::select('EXEC [acad].SP_SEL_DocentesXiPerfilId ?', [3]);
        foreach ($data as $fila) {
            $fila->iDocenteId = $this->hashids->encode($fila->iDocenteId);
        }
        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $data], Response::HTTP_OK);
    }

    public function obtenerAreasPorEspecialista($ugelId, $docenteId)
    {
        $ugelIdDescifrado = $this->hashids->decode($ugelId);
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($ugelIdDescifrado) || empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $data = DB::select(
            'EXEC [acad].SP_SEL_cursoEspecialistaUgelXDocenteIdXiUgelId @iDocenteId=?, @iUgelId=?',
            [$docenteIdDescifrado[0], $ugelIdDescifrado[0]]
        );
        if (empty($data)) {
            return response()->json(['status' => 'Success', 'message' => 'No hay datos para los parámetros enviados.'], Response::HTTP_NOT_FOUND);
        } else {
            return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $data], Response::HTTP_OK);
        }
    }

    public function asignarAreaEspecialista($ugelId, $docenteId, Request $request)
    {
        $ugelIdDescifrado = $this->hashids->decode($ugelId);
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($ugelIdDescifrado) || empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        DB::statement(
            "EXEC acad.SP_INS_cursoEspecialistaUgel @iDocenteId=?, @iUgelId=?, @iCursosNivelGradId=?",
            [$docenteIdDescifrado[0], $ugelIdDescifrado[0], $request->iCursosNivelGradId]
        );
        return response()->json(['status' => 'Success', 'message' => 'Área asignada correctamente'], Response::HTTP_CREATED);
    }

    public function eliminarAreaEspecialista($ugelId, $docenteId, Request $request)
    {
        $ugelIdDescifrado = $this->hashids->decode($ugelId);
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($ugelIdDescifrado) || empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        DB::statement(
            "EXEC acad.SP_DEL_cursoEspecialistaUgel @iDocenteId=?, @iUgelId=?, @iCursosNivelGradId=?",
            [$docenteIdDescifrado[0], $ugelIdDescifrado[0], $request->iCursosNivelGradId]
        );
        return response()->json(['status' => 'Success', 'message' => 'Se ha eliminado el área'], Response::HTTP_OK);
    }
}
