<?php

namespace App\Models\acad;

use App\Http\Requests\acad\SubirArchivoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstitucionEducativa
{
    public static function selInstitucionesEducativasParametros($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_institucionesEducativasParametros $placeholders", $parametros);
    }

    public static function selInstitucionesEducativas($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            $request->iNivelTipoId ?? NULL,
            $request->iTipoSectorId ?? NULL,
            $request->iDsttId ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iUgelId ?? NULL,
            $request->iZonaId ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::select("EXEC acad.Sp_SEL_institucionesEducativas $placeholders", $parametros);
    }

    public static function insInstitucionEducativa($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iDsttId ?? NULL,
            $request->iUgelId ?? NULL,
            $request->iNivelTipoId ?? NULL,
            $request->iTipoSectorId ?? NULL,
            $request->cIieeCodigoModular ?? NULL,
            $request->cIieeNombre ?? NULL,
            $request->cIieeDireccion ?? NULL,
            $request->iZonaId ?? NULL,
            $request->cIieeRUC ?? NULL,
            $request->cIieeDirector ?? NULL,
            $request->cIieeTelefono ?? NULL,
            $request->cIieeEmail ?? NULL,
            $request->cIieeLogo ?? NULL,
            $request->cIieeRslCreacion ?? NULL,
            $request->dtIieeRslCreacion ?? NULL,
            $request->iEstado ?? NULL,
            $request->cIieeNlat ?? NULL,
            $request->cIieeNlog ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_INS_institucionEducativa $placeholders", $parametros);
    }

    public static function updInstitucionEducativa($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId') ?? NULL,
            $request->iIieeId ?? NULL,
            $request->iDsttId ?? NULL,
            $request->iUgelId ?? NULL,
            $request->iNivelTipoId ?? NULL,
            $request->iTipoSectorId ?? NULL,
            $request->cIieeCodigoModular ?? NULL,
            $request->cIieeNombre ?? NULL,
            $request->cIieeDireccion ?? NULL,
            $request->iZonaId ?? NULL,
            $request->cIieeRUC ?? NULL,
            $request->cIieeDirector ?? NULL,
            $request->cIieeTelefono ?? NULL,
            $request->cIieeEmail ?? NULL,
            $request->cIieeLogo ?? NULL,
            $request->cIieeRslCreacion ?? NULL,
            $request->dtIieeRslCreacion ?? NULL,
            $request->iEstado ?? NULL,
            $request->cIieeNlat ?? NULL,
            $request->cIieeNlog ?? NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_UPD_institucionEducativa $placeholders", $parametros);
    }

    public static function selInstitucionEducativa($request)
    {
        $parametros = [
            $request->header('iCredEntPerfId'),
            NULL,
            NULL,
            NULL,
            $request->iIieeId ?? NULL,
            NULL,
            NULL,
        ];
        $placeholders = implode(',', array_fill(0, count($parametros), '?'));
        return DB::selectOne("EXEC acad.Sp_SEL_institucionesEducativas $placeholders", $parametros);
    }

    public static function selInstitucionEducativaPorCodigoModular($codigoModular) {
        return DB::selectOne("SELECT * FROM acad.institucion_educativas WHERE cIieeCodigoModular=?", [$codigoModular]);
    }

    public static function selInstitucionEducativaPorSede($iSedeId) {
        return DB::selectOne("SELECT ie.* FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId=ie.iIieeId
        WHERE sede.iSedeId=?", [$iSedeId]);
    }
    public static function selInstitucionEducativaNivel($iIieeId) {
        return DB::selectOne("SELECT ie.*, nt.cNivelTipoNombre, au.cUgelNombre
        FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId = ie.iIieeId
        INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = ie.iNivelTipoId
        INNER JOIN acad.ugeles AS au ON au.iUgelId = ie.iUgelId
        WHERE ie.iIieeId=?", [$iIieeId]);
    }
    public static function subirImagen($iCredEntPerfId, $iYAcadId, $imagen) {
        $convertirBase = base64_encode(file_get_contents($imagen));
        $extension = $imagen->extension();
        $img = "data:image/".$extension.";base64,".$convertirBase;

        $parametros = [
            $iCredEntPerfId
            ,$iYAcadId
            ,$img
            ,NULL
        ];
    
        return DB::selectOne("exec acad.Sp_UPD_institucion_logo_reglamento ?,?,?,?", $parametros);
    }
    public static function subirReglamento(SubirArchivoRequest $request) {
   
            $archivo = $request->file('documento');
            $nombreOriginal = $archivo->getClientOriginalName();
            $dremoYear = $request->dremoYear;
            $cIieeCodigoModular = $request->cIieeCodigoModular;
            $iPersId = $request->iPersId;

            $iYAcadId = $request->iYAcadId;
            $iCredEntPerfId = $request->iCredEntPerfId;

            $ruta = $dremoYear.'/'.$cIieeCodigoModular.'/'.'reglamento'.'/'.$iPersId;

            $enlace = Storage::disk('public')->putFile($ruta, $archivo);
        
            $folder = [
                'nombre' => $nombreOriginal,
                'enlace' => $enlace,
            ];

            $comprimido = json_encode($folder);

            $parametros = [
                $iCredEntPerfId
                ,$iYAcadId
                ,NULL
                ,$comprimido
            ];

            DB::selectOne("exec acad.Sp_UPD_institucion_logo_reglamento ?,?,?,?", $parametros);

            return $folder;

    }
}
