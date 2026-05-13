<?php

namespace App\Services\grl;

use App\Helpers\VerifyHash;
use App\Models\grl\Persona;
use App\Models\seg\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PersonasService
{
    public static function actualizarDatosPersonales($iPersId, Request $request)
    {
        $request->validate([
            'cPersCorreo' => 'nullable|email',
        ]);
        return Persona::updDatosPersonales($iPersId, $request);
    }

    public static function actualizarFotoPerfil($iPersId, Request $request)
    {
        self::eliminarFotoPerfil($iPersId);
        $archivo = $request->file('foto');
        $rutaDirectorio = self::obtenerRutaFotoPerfil($iPersId);
        $nombreArchivo = $archivo->getClientOriginalName();
        if (!Storage::disk('public')->exists($rutaDirectorio)) {
            Storage::disk('public')->makeDirectory($rutaDirectorio);
        }
        $archivo->move(Storage::disk('public')->path($rutaDirectorio), $nombreArchivo);
        Persona::updFotoPerfil($iPersId, $nombreArchivo);
        $url = 'storage/' . $rutaDirectorio . '/' . $nombreArchivo;
        return $url;
    }

    public static function eliminarFotoPerfil($iPersId)
    {
        $rutaDirectorio = self::obtenerRutaFotoPerfil($iPersId);
        $archivos = Storage::disk('public')->files($rutaDirectorio);
        foreach ($archivos as $archivo) {
            Storage::disk('public')->delete($archivo);
        }
    }

    public static function obtenerRutaFotoPerfil($iPersId)
    {
        $idHashed = VerifyHash::encodexId($iPersId);
        return "usuarios/foto-perfil/$idHashed";
    }

    public static function obtenerPersonaPorDocumento($cPersDocumento) {
        return Persona::selPersonaPorDocumento($cPersDocumento);
    }

    public static function actualizarPersonaConDataApi($dataServicio, $dataBD)
    {
        $iPersId = null;
        
        $persona = (object) $dataBD;
        $item = (object) $dataServicio;
        $iTipoPersId = ((int) $item->iTipoIdentId == 2) ? 2 : 1;

        // Si servicio devuelve vacío, usar datos de BD
        $parametros = [
            'iTipoPersId' => $iTipoPersId ?? NULL,
            'cPersDocumento' => $item->cPersDocumento ?? $persona->cPersDocumento,
            'cPersPaterno' => $item->cPersPaterno ?? $persona->cPersPaterno,
            'cPersMaterno' => $item->cPersMaterno ?? $persona->cPersMaterno ?? NULL,
            'cPersNombre' => $item->cPersNombre ?? $persona->cPersNombre,
            'cPersSexo' => $item->cPersSexo ?? $persona->cPersSexo ?? 'M',
            'dPersNacimiento' => $item->dPersNacimiento ?? $persona->dPersNacimiento ?? NULL,
            'iTipoEstCivId' => $item->iTipoEstCivId ?? $persona->iTipoEstCivId ?? 1,
            'cPersFotografia' => $persona->cPersFotografia ?? NULL,
            'cPersRazonSocialNombre' => $item->cPersRazonSocialNombre ?? $persona->cPersRazonSocialNombre ?? NULL,
            'cPersRazonSocialCorto' => $persona->cPersRazonSocialCorto ?? NULL,
            'cPersRazonSocialSigla' => $persona->cPersRazonSocialSigla ?? NULL,
            'cPersDomicilio' => $item->cPersDomicilio ?? $persona->cPersDomicilio ?? NULL,
            'iCredSesionId'=> $iCredId ?? NULL,
            'iPersRepresentanteLegalId'=> $persona->iPersRepresentanteLegalId ?? NULL,
            'iNacionId' => $item->iNacionId ?? $persona->iNacionId ?? NULL,
            'iPaisId' => $item->iPaisId ?? $persona->iPaisId ?? NULL,
            'iDptoId' => $item->iDptoId ?? $persona->iDptoId ?? NULL,
            'iPrvnId' => $item->iPrvnId ?? $persona->iPrvnId ?? NULL,
            'iDsttId' => $item->iDsttId ?? $persona->iDsttId ?? NULL,
            'cPersTelefono' => $item->cPersTelefono ?? $persona->cPersTelefono ?? NULL,
            'cPersCorreo' => $item->cPersCorreo ?? $persona->cPersCorreo ?? NULL,
        ];
        if ($persona) {
            // Actualizar persona
            $parametros['iPersId'] = $persona->iPersId;
            Usuario::updPersonas((object) $parametros);
            $iPersId = $persona->iPersId;
        } else {
            // Insertar persona
            $data = Usuario::insPersonas((object) $parametros);
            $iPersId = !empty($data) ? $data->iPersId : null;
        }
        return [
            'iPersId' => $iPersId,
            'parametros' => $parametros,
        ];
    }
}
