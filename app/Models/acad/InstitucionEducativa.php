<?php

namespace App\Models\acad;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\acad\SubirArchivoRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstitucionEducativa
{
    public static function selInstitucionEducativa($iIieeId)
    {
        return DB::selectOne('SELECT * FROM acad.institucion_educativas WHERE iIieeId=?', [$iIieeId]);
    }

    public static function selInstitucionEducativaPorCodigoModular($codigoModular)
    {
        return DB::selectOne('SELECT * FROM acad.institucion_educativas WHERE cIieeCodigoModular=?', [$codigoModular]);
    }

    public static function selInstitucionEducativaPorSede($iSedeId)
    {
        return DB::selectOne('SELECT ie.* FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId=ie.iIieeId
        WHERE sede.iSedeId=?', [$iSedeId]);
    }

    public static function selInstitucionEducativaNivel($iIieeId)
    {
        return DB::selectOne('SELECT ie.*, nt.cNivelTipoNombre, au.cUgelNombre
        FROM acad.institucion_educativas AS ie
        INNER JOIN acad.sedes AS sede ON sede.iIieeId = ie.iIieeId
        INNER JOIN acad.nivel_tipos AS nt ON nt.iNivelTipoId = ie.iNivelTipoId
        INNER JOIN acad.ugeles AS au ON au.iUgelId = ie.iUgelId
        WHERE ie.iIieeId=?', [$iIieeId]);
    }

    public static function subirImagen(Request $request)
    {
        $iYAcadId = $request->iYAcadId;
        $iIieeId = $request->iIieeId;
        $iSedeId = $request->iSedeId;
        $iPersId = $request->iPersId;
        $iCredEntPerfId = $request->iCredEntPerfId;
        $archivo = $request->file('escudo');
        $ruta = 'logoInstitucional/'.$iYAcadId.'/'.$iIieeId.'/'.$iSedeId.'/'.$iPersId.'/'.$iCredEntPerfId;

        $imagen = Storage::disk('public')->put($ruta, $archivo);
        $parametros = [
            $iCredEntPerfId, $iYAcadId, $imagen, null,
        ];

        return DB::selectOne('exec acad.Sp_UPD_institucion_logo_reglamento ?,?,?,?', $parametros);
    }

    public static function subirReglamento(SubirArchivoRequest $request)
    {

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
            $iCredEntPerfId, $iYAcadId, null, $comprimido,
        ];

        DB::selectOne('exec acad.Sp_UPD_institucion_logo_reglamento ?,?,?,?', $parametros);

        return $folder;

    }
}
