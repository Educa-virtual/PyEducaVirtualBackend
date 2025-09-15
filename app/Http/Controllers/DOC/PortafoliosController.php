<?php

namespace App\Http\Controllers\doc;

use App\Helpers\VerifyHash;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class PortafoliosController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    public function obtenerPortafolios(Request $request)
    {   
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $iYAcadId = $request->iYAcadId;
        $iCredId = $request->iCredId;
        $iIieeId = $request->iIieeId;

        try {
            $data = DB::select("
                SELECT 
                    p.cPortafolioFichaPermanencia
                    ,p.cPortafolioPerfilEgreso
                    ,p.cPortafolioPlanEstudios
                    ,p.cPortafolioItinerario
                    ,p.cPortafolioProgramaCurricular
                    ,p.cPortafolioFichasDidacticas
                    ,p.cPortafolioSesionesAprendizaje
                    ,p.iPortafolioId
                FROM doc.portafolios AS p 
                WHERE p.iDocenteId = '" . $iDocenteId . "' AND p.iYAcadId = '" . $iYAcadId . "'
            ");

            $reglamento = DB::select("
                SELECT 
                ie.cIieeUrlReglamentoInterno
                FROM acad.institucion_educativas AS ie
                WHERE ie.iIieeId = '" . $iIieeId . "'
            ");

            $response = ['validated' => true, 'mensaje' => 'Se octuvo la información exitosamente.', 'data' => $data, 'reglamento' => $reglamento];
            $codeResponse = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }

    public function guardarItinerario(Request $request)
    {
        $request['iDocenteId'] = is_null($request->iDocenteId)
            ? null
            : (is_numeric($request->iDocenteId)
                ? $request->iDocenteId
                : ($this->hashids->decode($request->iDocenteId)[0] ?? null));

        $request['iYAcadId'] = is_null($request->iYAcadId)
            ? null
            : (is_numeric($request->iYAcadId)
                ? $request->iYAcadId
                : ($this->hashids->decode($request->iYAcadId)[0] ?? null));

        try {
            $data = DB::select("
                SELECT 
                    p.cPortafolioFichaPermanencia
                    ,p.cPortafolioPerfilEgreso
                    ,p.cPortafolioPlanEstudios
                    ,p.cPortafolioItinerario
                    ,p.cPortafolioProgramaCurricular
                    ,p.cPortafolioFichasDidacticas
                    ,p.cPortafolioSesionesAprendizaje
                    ,p.iPortafolioId
                    FROM doc.portafolios AS p 
                    WHERE p.iDocenteId = '" . $request->iDocenteId . "' AND p.iYAcadId = '" . $request->iYAcadId . "'
            ");

            if (count($data) > 0) {
                $query = DB::update("
                    UPDATE doc.portafolios 
                    SET cPortafolioItinerario = '" . $request->cPortafolioItinerario . "'
                    WHERE iPortafolioId = '" . $data[0]->iPortafolioId . "'
                ");
            } else {
                $query = DB::update("
                    INSERT INTO doc.portafolios (iDocenteId,iYAcadId,cPortafolioItinerario)
                    VALUES ('" . $request->iDocenteId . "','" . $request->iYAcadId . "','" . $request->cPortafolioItinerario . "')
                ");
            }
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.', 'query' => $query];
            $codeResponse = 200;
        } catch (Exception $e) {
            $response = ['validated' => false, 'message' => substr($e->errorInfo[2] ?? '', 54), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
