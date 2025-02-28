<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;

class HorarioController extends Controller
{
    
    // no tocar
    public function listarHorarioIes(Request $request)
    {
       $iSedeId=  $request->iSedeId;
       $iTurnoId= $request->iTurnoId;
      

        $query = DB::select(
            "   SELECT hi.cHorarioIeNombre, hi.iHorarioIeId 
			   	, (SELECT  
							hd.dtHorarioHFin, hd.dtHorarioHInicio, hd.iBloqueHorarioId,hd.iHorarioIeDetId,hd.iHorarioIeId,
							blo.cTipoBloqueNombre, bl.cBloqueHorarioNombre
            FROM acad.horarios_ie_detalles AS hd 
						LEFT JOIN acad.bloques_horario as bl ON hd.iBloqueHorarioId = bl.iBloqueHorarioId  
						left JOIN acad.tipos_bloque as blo on bl.iTipoBloqueId = blo.iTipoBloqueId
            WHERE hd.iHorarioIeId = hi.iHorarioIeId  
            FOR JSON PATH) AS det_horario    
            

            from acad.horarios_ie as hi
             where hi.iSedeId= $iSedeId and hi.iTurnoId=$iTurnoId"
                      
        );

        try {
        $response = [
            'validated' => true,
            'message' => 'se obtuvo la información',
            'data' => $query,
        ];

        $estado = 201;
        } catch (Exception $e) {
        $response = [
            'validated' => false,
            'message' => $e->getMessage(),
            'data' => [],
        ];
        $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    
}



