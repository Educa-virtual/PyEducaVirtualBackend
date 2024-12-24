<?php

namespace App\Http\Controllers\aula;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Dompdf\Options;

class AnuncioController extends Controller
{
    //private apiUrl = 'http://localhost:8000/api'; // Backend URL
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }
    public function guardarAnuncio(Request $request){
        
        $request->validate([
            'cForoDescripcion' => 'required|string',
            'iForoCatId' => 'required|integer',
        ]);
        $idDocCursoId = $request->idDocCursoId;
        if ($request->idDocCursoId) {
            $idDocCursoId = $this->hashids->decode($idDocCursoId);
            $idDocCursoId = count($idDocCursoId) > 0 ? $idDocCursoId[0] : $idDocCursoId;
        }
        
        $data = [
            $idDocCursoId,
            $request->iForoCatId,
            $request->cForoTitulo ?? NULL,
            $request->cForoDescripcion,
        ];
        
        try {
            $resp = DB::select('EXEC [aula].[SP_INS_insertarAnunciosPorCategoria] ?, ?, ?, ?', $data);

            DB::commit();
            if ($resp[0]->id > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }
        return new JsonResponse($response, $codeResponse);
        
    }
    public function obtenerAnunciosXDocente(Request $request){
        $request->validate([
            'iDocenteId' => 'required|string',
            'iForoCatId' => 'required|integer',
        ]);
        $iForoCatId = $request->iForoCatId;
        $iDocenteId = $request->iDocenteId;
        if($request->iDocenteId){
            $iDocenteId = $this->hashids->decode($iDocenteId);
            $iDocenteId = count($iDocenteId) > 0 ? $iDocenteId[0] : $iDocenteId;
        }
        $params = [
            $iForoCatId,
            $iDocenteId
        ];
        try {
            // Ejecutar el procedimiento almacenado
            $data = DB::select('EXEC [aula].[SP_SEL_obtenerAnunciosPorCategoria] ?,?', $params);
            // Preparar la respuesta
            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $estado = 200;

            return $response;
        } catch (\Exception $e) {
            // Manejo de excepción y respuesta de error
            $response = [
                'validated' => false,
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
                'data' => [],
            ];
            $estado = 500;
            return new JsonResponse($response, $estado);
        }
        return $iDocenteId;
    }

    
    
    
    
    
    
    
}
