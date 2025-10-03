<?php

namespace App\Models\doc;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialEducativo extends Model
{
    public static function obtenerMaterial(Request $request){
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $parametros = [
            $request->opcion,
            $request->idDocCursoId,
            $iDocenteId,
        ];
        
        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_SEL_materialEducativoDocentes '.$enviar;
        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function guardarMaterial(Request $request){
        $opcion = $request->opcion;
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $idDocCursoId = intval($request->idDocCursoId);
        $cMatEducativoTitulo = $request->cMatEducativoTitulo;
        $cMatEducativoDescripcion = $request->cMatEducativoDescripcion;
        $cMatEducativoUrl = $request->cMatEducativoUrl;

        $parametros = [
            $opcion,
            $idDocCursoId,
            $iDocenteId,
            $cMatEducativoTitulo,
            $cMatEducativoDescripcion,
            $cMatEducativoUrl ?? NULL,
        ];
        
        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_INS_materialEducativoDocentes '.$enviar;
        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function actualizarMaterial(Request $request){
        $parametros = [
            $request->opcion,
            $request->iMatEducativoId              ?? NULL,
            $request->cMatEducativoTitulo          ?? NULL,
            $request->cMatEducativoDescripcion     ?? NULL,
            $request->cMatEducativoUrl             ?? NULL,
        ];

        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $tabla = 'exec doc.Sp_UPD_materialEducativoDocentes '.$enviar;
        $data = DB::select($tabla, $parametros);
        return $data;
    }
    public static function eliminarMaterial(Request $request){
        $parametros = [
            $request->opcion,
            $request->iMatEducativoId
        ];

        $data = DB::select('exec doc.Sp_DEL_materialEducativoDocentes ?,?', $parametros);
        return $data;
    }
}
