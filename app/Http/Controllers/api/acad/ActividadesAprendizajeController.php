<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadesAprendizajeController extends Controller
{
    public function list(Request $request){
        $iSilaboActAprendId = $request->iSilaboActAprendId ? $request->iSilaboActAprendId : 0;
        $seleccion = $request->seleccion;

        $opcion = [
            0   =>  "",
            1   =>  "WHERE iSilaboActAprendId = ".$iSilaboActAprendId,
        ];

        $sel_query = DB::select("SELECT
        iSilaboActAprendId,
        iSilaboId,
        iIndLogorCapId,
        cSilaboActAprendNumero,
        cSilaboActAprendNombre,
        cSilaboActAprendElementos,
        dtSilaboActAprend,
        iHorarioId
        FROM acad.silabo_actividad_aprendizajes ".$opcion[1]);

        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $sel_query,
            ];

            $estado = 200;

        } catch(Exception $e){
            $response = [
                'validated' => true, 
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response,$estado);
    }
    public function save(Request $request){

        $iSilaboId                  = $request->iSilaboId;
        $iIndLogorCapId             = $request->iIndLogorCapId;
        $cSilaboActAprendNumero     = $request->cSilaboActAprendNumero;
        $cSilaboActAprendNombre     = $request->cSilaboActAprendNombre;
        $cSilaboActAprendElementos  = $request->cSilaboActAprendElementos;
        $dtSilaboActAprend          = $request->dtSilaboActAprend;
        $iHorarioId                 = $request->iHorarioId;

        $sel_silabo = DB::select("SELECT iSilaboId FROM acad.silabos WHERE iSilaboId = ?",[$iSilaboId]);
        $sel_indicador = DB::select("SELECT iIndLogorCapId FROM acad.iIndLogorCapId WHERE iIndLogorCapId = ?",[$iIndLogorCapId]);

        if($sel_silabo && $sel_indicador){

            $ins_query = DB::insert("INSERT INTO acad.silabo_actividad_aprendizajes
            (
            iSilaboId,
            iIndLogorCapId,
            cSilaboActAprendNumero,
            cSilaboActAprendNombre,
            cSilaboActAprendElementos,
            dtSilaboActAprend,
            iHorarioId
            )
            VALUES
            (?,?,?,?,?,?,?)"
            ,[
                $iSilaboId,
                $iIndLogorCapId,
                $cSilaboActAprendNumero,
                $cSilaboActAprendNombre,
                $cSilaboActAprendElementos,
                $dtSilaboActAprend,
                $iHorarioId
            ]);

            try{
                $response = [
                    'validated' => true, 
                    'message' => 'se obtuvo la información',
                    'data' => $ins_query,
                ];

                $estado = 200;

            }catch(Exception $e){
                $response = [
                    'validated' => true, 
                    'message' => $e->getMessage(),
                    'data' => [],
                ];
                $estado = 500;
            }
            
            return new JsonResponse($response,$estado);
        }
    }
    public function update(Request $request){
        
        $iSilaboActAprendId         = $request->iSilaboActAprendId;
        $iSilaboId                  = $request->iSilaboId;
        $iIndLogorCapId             = $request->iIndLogorCapId;
        $cSilaboActAprendNumero     = $request->cSilaboActAprendNumero;
        $cSilaboActAprendNombre     = $request->cSilaboActAprendNombre;
        $cSilaboActAprendElementos  = $request->cSilaboActAprendElementos;
        $dtSilaboActAprend          = $request->dtSilaboActAprend;
        $iHorarioId                 = $request->iHorarioId;

        $sel_silabo = DB::select("SELECT iSilaboId FROM acad.silabos WHERE iSilaboId = ?",[$iSilaboId]);
        $sel_indicador = DB::select("SELECT iIndLogorCapId FROM acad.iIndLogorCapId WHERE iIndLogorCapId = ?",[$iIndLogorCapId]);

        if($sel_silabo && $sel_indicador){

            $ins_query = DB::update("UPDATE acad.silabo_actividad_aprendizajes SET
            (
            iSilaboId=?
            iIndLogorCapId=?
            cSilaboActAprendNumero=?
            cSilaboActAprendNombre=?
            cSilaboActAprendElementos=?
            dtSilaboActAprend=?
            iHorarioId=?
            )
            WHERE iSilaboActAprendId = ?"
            ,[
                $iSilaboActAprendId,
                $iSilaboId,
                $iIndLogorCapId,
                $cSilaboActAprendNumero,
                $cSilaboActAprendNombre,
                $cSilaboActAprendElementos,
                $dtSilaboActAprend,
                $iHorarioId
            ]);

            try{
                $response = [
                    'validated' => true, 
                    'message' => 'se obtuvo la información',
                    'data' => $ins_query,
                ];

                $estado = 200;

            }catch(Exception $e){
                $response = [
                    'validated' => true, 
                    'message' => $e->getMessage(),
                    'data' => [],
                ];
                $estado = 500;
            }
            
            return new JsonResponse($response,$estado);
        }
    }
    public function delete(){
        
    }
}
