<?php

namespace App\Models\doc;

use App\Helpers\VerifyHash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Portafolio extends Model
{
    public static function obtenerPortafolios(Request $request){
    
        $iCuadernoId    = $request->iCuadernoId;
        $iIieeId        = $request->iIieeId;
        $iPortafolioId  = $request->iPortafolioId;
        
        $datos = [
            $iPortafolioId,
            $iCuadernoId,
            $iIieeId,
        ];

        $solicitud = str_repeat('?,',count($datos)-1).'?';
        $procedimiento = "EXEC doc.Sp_SEL_portafolios ".$solicitud;
         
        $data = DB::select($procedimiento,$datos);
        return $data;
    }
    public static function guardarItinerario(Request $request){
        $iPortafolioId          = $request->iPortafolioId;
        $iDocenteId             = VerifyHash::decodes($request->iDocenteId);
        $iYAcadId               = $request->iYAcadId;
        $iSedeId                = $request->iSedeId;
        $cPortafolioItinerario  = $request->cPortafolioItinerario;
        $iSilaboId              = $request->iSilaboId;
        
        $datos = [
            $iPortafolioId ?? NULL,
            $iDocenteId,
            $iYAcadId,
            $iSedeId,
            $cPortafolioItinerario ?? NULL,
            $iSilaboId,
        ];

        $solicitud = str_repeat('?,',count($datos)-1).'?';
        $procedimiento = "EXEC doc.Sp_INS_itinerario ".$solicitud;
         
        $data = DB::select($procedimiento,$datos);
        return $data;
        
    }
    public static function guardarCuadernoCampo(Request $request){
        $iCuadernoId          = $request->iCuadernoId;
        $iSilaboId            = $request->iSilaboId;
        $cCuadernoUrl         = $request->cCuadernoUrl;
        
        $datos = [
            $iCuadernoId ?? NULL,
            $iSilaboId,
            $cCuadernoUrl,
        ];
        
        $solicitud = str_repeat('?,',count($datos)-1).'?';
        $procedimiento = "EXEC doc.Sp_INS_cuaderno_campo ".$solicitud;
         
        $data = DB::select($procedimiento,$datos);
        return $data;
        
    }
}
