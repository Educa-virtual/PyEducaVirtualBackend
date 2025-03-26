<?php

namespace App\Http\Controllers\Ere;

use App\Http\Controllers\Controller;
use App\Repositories\ere\NivelLogrosRepository;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class NivelLogrosController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerNivelLogros()
    {
        $data = NivelLogrosRepository::obtenerNivelLogros();
        return response()->json(['status' => 'Success', 'message' => 'Se obtuvo la información', 'data' => $data], Response::HTTP_OK);
    }

    public function obtenerNivelLogrosPorCurso($evaluacionId, $cursoId)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $iCursosNivelGradIdDescifrado = $this->hashids->decode($cursoId);
        if (empty($evaluacionIdDescifrado) || empty($iCursosNivelGradIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }
        $data = NivelLogrosRepository::obtenerNivelLogrosPorCurso($iCursosNivelGradIdDescifrado[0], $evaluacionIdDescifrado[0]);
        return response()->json(['status' => 'Success', 'message' => 'Se obtuvo la información', 'data' => $data], Response::HTTP_OK);
    }

    public function registrarNivelLogroPorCurso($evaluacionId, $cursoId, Request $request)
    {
        $evaluacionIdDescifrado = $this->hashids->decode($evaluacionId);
        $iCursosNivelGradIdDescifrado = $this->hashids->decode($cursoId);
        if (empty($evaluacionIdDescifrado) || empty($iCursosNivelGradIdDescifrado)) {
            return response()->json(['status' => 'Error', 'message' => 'El ID enviado no se pudo descifrar.'], Response::HTTP_BAD_REQUEST);
        }

        $nivelesRegistrar = [];
        foreach ($request->formulario as $fila) {
            if (!empty($fila['iDesde']) && !empty($fila['iHasta']) && !empty($fila['iNivelLogroId'])) {

                if ($fila['iDesde'] > $fila['iHasta']) {
                    return response()->json(['status' => 'Error', 'message' => 'El valor de "Desde" no puede ser mayor al valor de "Hasta". Por favor cambie los valores ingresados antes de continuar'], Response::HTTP_BAD_REQUEST);
                }
                array_push($nivelesRegistrar, ['iDesde' => $fila['iDesde'], 'iHasta' => $fila['iHasta'], 'iNivelLogroId' => $fila['iNivelLogroId']]);
            }
        }
        $niveles = array_column($nivelesRegistrar, 'iNivelLogroId');
        if (count($niveles) !== count(array_unique($niveles))) {
            return response()->json(['status' => 'Error', 'message' => 'Existen valores repetidos en la columna Resultado. Por favor cambie los valores seleccionados antes de continuar'], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            NivelLogrosRepository::eliminarNivelLogroPorCurso($iCursosNivelGradIdDescifrado[0], $evaluacionIdDescifrado[0]);
            NivelLogrosRepository::registrarNivelLogroPorCurso($nivelesRegistrar, $iCursosNivelGradIdDescifrado[0], $evaluacionIdDescifrado[0]);
            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Se ha registrado la configuracion'], Response::HTTP_OK);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => 'Error: ' . $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
