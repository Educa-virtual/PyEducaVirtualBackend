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
                'iPersId' => $persona->iPersId,
                'cPersDocumento' => $item['cPersDocumento'],
                'cPersPaterno' => $item['cPersPaterno'],
                'cPersMaterno' => isset($item['cPersMaterno']) ? $item['cPersMaterno'] : '',
                'cPersNombre' => $item['cPersNombre'],
                'cPersSexo' => isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                'dPersNacimiento' => isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                'iTipoEstCivId' => isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                'cPersFotografia' => NULL,
                'cPersRazonSocialNombre' => $item['cPersRazonSocialNombre'] ?? '',
                'cPersRazonSocialCorto' => '',
                'cPersRazonSocialSigla' => '',
                'cPersDomicilio' => $item['cPersDomicilio'],
                'iCredId' => isset($iCredId) ? $iCredId : null,
                'iNacionId' => isset($item['iNacionId']) ? $item['iNacionId'] : null,
                'iPaisId' => isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                'iDptoId' => isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                'iPrvnId' => isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                'iDsttId' => isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
            ];
            Persona::updPersonas((object) $parametros);
            $iPersId = $persona->iPersId;
        } else {
            $parametros = [
                'iTipoPersId' => isset($iTipoPersId) ? $iTipoPersId : null,
                'iTipoIdentId' => $item['iTipoIdentId'],
                'cPersDocumento' => $item['cPersDocumento'],
                'cPersPaterno' => $item['cPersPaterno'],
                'cPersMaterno' => isset($item['cPersMaterno']) ? $item['cPersMaterno'] : null,
                'cPersNombre' => $item['cPersNombre'],
                'cPersSexo' => isset($item['cPersSexo']) ? $item['cPersSexo'] : "M",
                'dPersNacimiento' => isset($item['dPersNacimiento']) ? $item['dPersNacimiento'] : null,
                'iTipoEstCivId' => isset($item['iTipoEstCivId']) ? $item['iTipoEstCivId'] : 1,
                'cPersFotografia' => NULL,
                'cPersRazonSocialNombre' => $item['cPersRazonSocialNombre'] ?? '',
                'cPersRazonSocialCorto' => '',
                'cPersRazonSocialSigla' => '',
                'cPersDomicilio' => $item['cPersDomicilio'],
                'iCredId' => isset($iCredId) ? $iCredId : null,
                'iNacionId' => isset($item['iNacionId']) ? $item['iNacionId'] : null,
                'iPaisId' => isset($item['iPaisId']) ? (trim($item['iPaisId']) ?: null) : null,
                'iDptoId' => isset($item['iDptoId']) ? (trim($item['iDptoId']) ?: null) : null,
                'iPrvnId' => isset($item['iPrvnId']) ? (trim($item['iPrvnId']) ?: null) : null,
                'iDsttId' => isset($item['iDsttId']) ? (trim($item['iDsttId']) ?: null) : null,
            ];
            $data = Persona::insPersonas((object) $parametros);
            $iPersId = !empty($data) ? $data[0]->iPersId : null;
        }
        return $iPersId;
    }
}
