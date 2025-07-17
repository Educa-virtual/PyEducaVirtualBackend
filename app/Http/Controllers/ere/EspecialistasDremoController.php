<?php

namespace App\Http\Controllers\ere;

use App\Http\Controllers\Controller;
use App\Repositories\PreguntasRepository;
use App\Services\ere\AreasService;
use App\Services\ParseSqlErrorService;
use Exception;
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
        foreach ($data as $fila) {
            $fila->iDocenteId = $this->hashids->encode($fila->iDocenteId);
        }
        return response()->json(['status' => 'Success', 'message' => 'Datos obtenidos.', 'data' => $data], Response::HTTP_OK);
    }

    public function obtenerAreasPorEspecialista($docenteId)
    {
        $docenteIdDescifrado = $this->hashids->decode($docenteId);
        if (empty($docenteIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $data = DB::select('EXEC acad.SP_SEL_cursoEspecialistaDremoXDocenteId @iDocenteId=?', [$docenteIdDescifrado[0]]);
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
        return response()->json(['status' => 'Success', 'message' => 'Se ha eliminado el área'], Response::HTTP_OK);
    }
}
