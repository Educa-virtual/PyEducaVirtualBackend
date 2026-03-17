<?php

namespace App\Models\com;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Comunicado extends Model
{
    public static function selComunicados($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iTipoUsuario,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC com.Sp_SEL_comunicados2 $placeholders", $parametros);
    }

    public static function selComunicadoParametros($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_SEL_comunicadoParametros $placeholders", $parametros);
    }

    public static function selComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
            $request->iTipoUsuario,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_SEL_comunicado $placeholders", $parametros);
    }

    public static function insComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->iTipoComId,
            $request->iPrioridadId,
            $request->cComunicadoTitulo,
            $request->cComunicadoDescripcion,
            $request->dtComunicadoEmision,
            $request->dtComunicadoHasta,
            $request->jsonGrupo,
            $request->cComunicadoAdjunto,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_INS_comunicado $placeholders", $parametros);
    }

    public static function updComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
            $request->iTipoComId,
            $request->iPrioridadId,
            $request->cComunicadoTitulo,
            $request->cComunicadoDescripcion,
            $request->dtComunicadoEmision,
            $request->dtComunicadoHasta,
            $request->jsonGrupo,
            $request->cComunicadoAdjunto,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_UPD_comunicado $placeholders", $parametros);
    }

    public static function delComunicado($request) {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_DEL_comunicado $placeholders", $parametros);
    }

    public static function selGrupoCantidad($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->jsonGrupo,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_SEL_grupoCantidad $placeholders", $parametros);
    }

    public static function selBuscarPersona($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iYAcadId,
            $request->jsonDatos,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.Sp_SEL_buscarPersona $placeholders", $parametros);
    }

    public static function subirDocumento($request)
    {
        if (!$request->hasFile('archivo')) {
            throw new Exception("No se envió ningún archivo.");
        }
        $nombreOriginal = pathinfo($request->nombreArchivo, PATHINFO_FILENAME);
        $nombreArchivo = Str::slug($nombreOriginal);
        $documento = $request->file("archivo");
        $extension = $documento->getClientOriginalExtension();
        $nombreRuta = $request->nombreRuta;
        $ruta = Storage::disk('local')->put($nombreRuta, $documento);

        $archivoGnerado = [
            'nombreArchivoGuardado' => $nombreArchivo,
            'rutaArchivoGuardado' => $ruta,
            'extension' => $extension,
        ];

        return $archivoGnerado;
    }
    public static function insRecepcionarComunicado($request)
    {
     
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iComunicadoId,
        ];
      //  return $parametros;

        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC com.SEL_INS_RecepcionComunicados $placeholders", $parametros);
    }
}
