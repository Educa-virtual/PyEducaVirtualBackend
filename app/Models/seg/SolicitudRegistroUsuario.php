<?php

namespace App\Models\seg;

use App\Http\Requests\seg\SolicitarRegistroUsuarioRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitudRegistroUsuario extends Model
{
    public static function insSolicitudRegistroUsuario(SolicitarRegistroUsuarioRequest $request)
    {
        return DB::statement("INSERT INTO [seg].[solicitudes_creacion_usuarios]
           ([cDocumento]
           ,[cCodigoModular]
           ,[cCargo]
           ,[cCorreo]
           ,[cNombres]
           ,[cApellidos]
           ,[cComentarios]
           ,[dtFechaCreacion])
     VALUES
           (?,?,?,?,?,?,?, GETDATE())", [$request->cDocumento, $request->cCodigoModular, $request->cCargo, $request->cCorreo, $request->cNombres, $request->cApellidos, $request->cComentarios]);
    }

    public static function selExisteSolicitudRegistro($cDocumento)
    {
        $resultado = DB::selectOne("SELECT * FROM [seg].[solicitudes_creacion_usuarios] WHERE cDocumento=?
        AND DATEDIFF(day,dtFechaCreacion,GETDATE())<10", [$cDocumento]);
        return $resultado;
    }

    public static function selListaSolicitudesRegistro($parametros)
    {
        $dataSolicitudes = DB::select("EXEC [seg].[SP_SEL_solicitudesRegistroUsuarios] @soloTotal=?, @offset=?,  @limit=?, @fechaCreacionDesde=?,
        @fechaCreacionHasta=?, @atendidas=?", $parametros);
        return $dataSolicitudes;
    }
}
