<?php

namespace App\Http\Controllers\bienestar;

use App\Http\Controllers\Controller;
use App\Services\ParseSqlErrorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FichaBienestarController extends Controller
{
    // Método que obtiene estudiantes por iIieeId y año
    public function indexEstudiantes($iIieeId, $anio)
        {
        $estudiantes = DB::select("
            SELECT DISTINCT
                fdg.iPersId,
                e.cEstPaterno,
                e.cEstMaterno,
                e.cEstNombres,
                m.iEstudianteId,
                g.cGradoAbreviacion,
                s.cSeccionNombre,
                gp.cPersDocumento,
                fdg.dtFichaDG,
                sd.cSedeNombre,
                ie.iIieeId,
                ie.cIieeCodigoModular,
                ie.cIieeNombre
            FROM obe.ficha_datos_grales AS fdg
            INNER JOIN acad.estudiantes e ON fdg.iPersId = e.iPersId 
            INNER JOIN acad.matricula m ON e.iEstudianteId = m.iEstudianteId
            INNER JOIN acad.nivel_grados ng ON m.iNivelGradoId = ng.iNivelGradoId
            INNER JOIN acad.grados g ON ng.iGradoId = g.iGradoId
            INNER JOIN acad.secciones s ON m.iSeccionId = s.iSeccionId
            INNER JOIN grl.personas gp ON fdg.iPersId = gp.iPersId 
            INNER JOIN acad.sedes sd ON m.iSedeId = sd.iSedeId
            INNER JOIN acad.institucion_educativas ie ON sd.iIieeId = ie.iIieeId
            WHERE sd.iIieeId = ? AND YEAR(fdg.dtFichaDG) = ?
        ", [$iIieeId, $anio]);

        return response()->json($estudiantes);
    }

    public function listarFichas(Request $request)
    {
        $parametros = [
            $request->iCredSesionId,
            $request->iFichaDGId,
            $request->iPersId,
            $request->cPersDocumento,
            $request->cPersNombresApellidos,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichas ?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function crearFicha(Request $request)
    {
        try {
            $data = DB::select('EXEC obe.Sp_SEL_fichaParametros');
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function borrarFicha(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
        ];

        try {
            DB::select('EXEC obe.Sp_DEL_ficha ?', $parametros);
            $response = ['validated' => true, 'message' => 'se elimino la ficha', 'data' => []];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }

    public function verFicha(Request $request)
    {
        $parametros = [
            $request->iFichaDGId,
            $request->iPersId,
            $request->iYAcadId,
        ];

        try {
            $data = DB::select('EXEC obe.Sp_SEL_ficha ?,?,?', $parametros);
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $error_message = ParseSqlErrorService::parse($e->getMessage());
            $response = ['validated' => false, 'message' => $error_message, 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
    }
}
