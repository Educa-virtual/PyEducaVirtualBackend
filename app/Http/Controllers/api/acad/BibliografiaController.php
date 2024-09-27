<?php

namespace App\Http\Controllers\api\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BibliografiaController extends Controller
{
    public function crud(Request $request){
        $ocpion = $request->ocpion;
        $iTipoBiblioId      = $request->iTipoBiblioId;
        $iSilaboId          = $request->iSilaboId;
        $cBiblioAutor       = $request->cBiblioAutor;
        $cBiblioTitulo      = $request->cBiblioTitulo;
        $cBiblioAnioEdicion = $request->cBiblioAnioEdicion;
        $cBiblioEditorial   = $request->cBiblioEditorial;
        $cBiblioUrl         = $request->cBiblioUrl;
        $iEstado            = $request->iEstado;

        $query = DB::select("EXECUTE acad.Sp_INS_bibliografia ?,?,?,?,?,?,?,?,?",
            [
            $ocpion            ,
            $iTipoBiblioId     ,
            $iSilaboId         ,
            $cBiblioAutor      ,
            $cBiblioTitulo     ,
            $cBiblioAnioEdicion,
            $cBiblioEditorial  ,
            $cBiblioUrl        ,
            $iEstado
            ]
        );

        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $query,
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
    public function save(Request $request){
        
        $iTipoBiblioId      = $request->iTipoBiblioId;
        $iSilaboId          = $request->iSilaboId;
        $cBiblioAutor       = $request->cBiblioAutor;
        $cBiblioTitulo      = $request->cBiblioTitulo;
        $cBiblioAnioEdicion = $request->cBiblioAnioEdicion;
        $cBiblioEditorial   = $request->cBiblioEditorial;
        $cBiblioUrl         = $request->cBiblioUrl;
        $iEstado            = $request->iEstado;
        $iSesionId          = $request->iSesionId;
        $dtCreado           = $request->dtCreado;
        $dtActualizado      = $request->dtActualizado;

        $sel_query = DB::select("SELECT iTipoBiblioId,cTipoBiblioNombre FROM acad.tipo_bibliografias WHERE iTipoBiblioId=?",[$iTipoBiblioId]);

        if(!$sel_query){

            $ins_query = DB::insert("INSERT INTO acad.bilbiografia (
            iTipoBiblioId,
            iSilaboId,
            cBiblioAutor,
            cBiblioTitulo,
            cBiblioAnioEdicion,
            cBiblioEditorial,
            cBiblioUrl,
            iEstado,
            iSesionId,
            dtCreado,
            dtActualizado
            ) VALUES
            (?,?,?,?,?,?,?,?,?,?,?)
            ",[ $iTipoBiblioId,
                $iSilaboId,
                $cBiblioAutor,
                $cBiblioTitulo,
                $cBiblioAnioEdicion,
                $cBiblioEditorial,
                $cBiblioUrl,
                $iEstado,
                $iSesionId,
                $dtCreado,
                $dtActualizado
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
        
        $iTipoBiblioId      = $request->iTipoBiblioId;
        $iSilaboId          = $request->iSilaboId;
        $cBiblioAutor       = $request->cBiblioAutor;
        $cBiblioTitulo      = $request->cBiblioTitulo;
        $cBiblioAnioEdicion = $request->cBiblioAnioEdicion;
        $cBiblioEditorial   = $request->cBiblioEditorial;
        $cBiblioUrl         = $request->cBiblioUrl;
        $iEstado            = $request->iEstado;
        $iSesionId          = $request->iSesionId;
        $dtCreado           = $request->dtCreado;
        $dtActualizado      = $request->dtActualizado;
        $iBiblioId          = $request->iBiblioId;

        $sel_query = DB::select("SELECT iTipoBiblioId,cTipoBiblioNombre FROM acad.tipo_bibliografias WHERE iTipoBiblioId=?",[$iTipoBiblioId]);

        if(!$sel_query){

            $upd_query = DB::update("UPDATE acad.bilbiografia SET 
            iTipoBiblioId       = ?,
            iSilaboId           = ?,
            cBiblioAutor        = ?,
            cBiblioTitulo       = ?,
            cBiblioAnioEdicion  = ?,
            cBiblioEditorial    = ?,
            cBiblioUrl          = ?,
            iEstado             = ?,
            iSesionId           = ?,
            dtCreado            = ?,
            dtActualizado       = ?
            WHERE iBiblioId     = ?",
            [   $iTipoBiblioId,
                $iSilaboId,
                $cBiblioAutor,
                $cBiblioTitulo,
                $cBiblioAnioEdicion,
                $cBiblioEditorial,
                $cBiblioUrl,
                $iEstado,
                $iSesionId,
                $dtCreado,
                $dtActualizado,
                $iBiblioId
            ]);

            try{
                $response = [
                    'validated' => true, 
                    'message' => 'se obtuvo la información',
                    'data' => $upd_query,
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
    public function delete(Request $request){
        
        $iBiblioId = $request->iBiblioId;
        $iEstado = $request->iEstado;
        $upd_query = DB::update("UPDATE acad.bilbiografia SET iEstado = ? WHERE iBiblioId = ?",[$iEstado,$iBiblioId]);
    
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $upd_query,
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
    public function list(Request $request){
        
        $iBiblioId = $request->iBiblioId ? $request->iBiblioId : 0;
        $iEstado = $request->iEstado;
        $seleccion = $request->seleccion;

        $opcion=[
            0   =>  "WHERE iEstado =".$iEstado,
            1   =>  "WHERE iEstado =".$iEstado." AND iBiblioId = ".$iBiblioId,
        ];

        $sel_query = DB::select("select
            iTipoBiblioId,
            iSilaboId,
            cBiblioAutor,
            cBiblioTitulo,
            cBiblioAnioEdicion,
            cBiblioEditorial,
            cBiblioUrl,
            iEstado
            FROM acad.bilbiografia".$opcion[$seleccion]);
    
        try{
            $response = [
                'validated' => true, 
                'message' => 'se obtuvo la información',
                'data' => $sel_query,
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
