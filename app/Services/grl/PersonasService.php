<?php

namespace App\Services\grl;

use App\Helpers\VerifyHash;
use App\Models\grl\Persona;
use App\Models\seg\Usuario;
use Illuminate\Http\Request;
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

    public static function actualizarPersonaConDataApi($data)
    {
        $iPersId = null;

        $item = $data; //$request->data;
        $iTipoPersId = ((int)$item['iTipoIdentId'] == 2) ? 2 : 1;
        $persona = self::obtenerPersonaPorDocumento($item['cPersDocumento']);
        if ($persona) {
            //Actualizar persona
            $parametros = [
                $persona->iPersId,
                $item['cPersDocumento'],
                $item['cPersPaterno'],
                isset($item['cPersMaterno']) ? $item['cPersMaterno'] : '',
                $item['cPersNombre'],
                isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                NULL,
                $item['cPersRazonSocialNombre'] ?? '',
                '',
                '',
                $item['cPersDomicilio'],
                isset($iCredId) ? $iCredId : null,
                null,
                isset($item['iNacionId']) ? $item['iNacionId'] : null,
                isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
                //isset($iTipoPersId) ? $iTipoPersId : null,
                //$item['iTipoIdentId']
            ];
            Usuario::updPersonas($parametros);
            $iPersId = $persona->iPersId;
        } else {
            $parametros = [
                isset($iTipoPersId) ? $iTipoPersId : null,
                $item['iTipoIdentId'],
                $item['cPersDocumento'],
                $item['cPersPaterno'],
                isset($item['cPersMaterno']) ? $item['cPersMaterno'] : null,
                $item['cPersNombre'],
                isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                NULL,
                $item['cPersRazonSocialNombre'] ?? '',
                '',
                '',
                $item['cPersDomicilio'],
                isset($iCredId) ? $iCredId : null,
                isset($item['iNacionId']) ? $item['iNacionId'] : null,
                isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
            ];
            $data = Usuario::insPersonas($parametros);
            $iPersId = !empty($data) ? $data[0]->iPersId : null;
        }
        return $iPersId;
    }
}
