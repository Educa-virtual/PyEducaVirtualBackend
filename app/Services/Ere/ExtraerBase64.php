<?php

namespace App\Services\Ere;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use WebPConvert\WebPConvert;

class ExtraerBase64
{
    /*
        Extrar archivos codificados en base64 de un texto y guardar en storage

        Input: texto con o sin archivos codificadas en base64
        Output: mismo texto, archivos codificados en base64 reemplazadas por url
    */
    private static function limpiarCarpeta($ruta)
    {
        // Borra todos los archivos
        Storage::disk('public')->deleteDirectory($ruta);
        Storage::disk('public')->makeDirectory($ruta);
    }

    private static function convertirImagenWebp($ruta, $uuid, $extension)
    {
        $carpetaPublic = Storage::disk('public')->path($ruta);
        $source = $carpetaPublic .  $uuid . '.' . $extension;
        $destination = $carpetaPublic .  $uuid . '.webp';
        WebPConvert::convert($source, $destination, []);
        unlink($source);
        return asset('storage/' . $ruta . $uuid . '.webp');
    }

    public static function extraer($texto, $id, $tipo)
    {
        $ruta = 'ere/preguntas/' .$tipo.'/'. $id . '/';
        if (strpos($texto, 'base64')) {
            $urls = array();
            preg_match_all('#data:image/[^;]+;base64,[A-Za-z0-9+/=]+#', $texto, $imagenesBase64);
            $imagenesBase64 = $imagenesBase64[0]; // Extract the matched base64 strings
            $contador = 0;
            foreach ($imagenesBase64 as $key => $imagenB64) {
                $contador++;
                $uuid = Uuid::uuid4();
                $extension = explode('/', explode(':', substr($imagenB64, 0, strpos($imagenB64, ';')))[1])[1];
                $nombreImagen =  $uuid . '.' . $extension;
                $replace = substr($imagenB64, 0, strpos($imagenB64, ',') + 1);
                $imagen = str_replace($replace, '', $imagenB64);
                $imagen = str_replace(' ', '+', $imagen);
                $filePath = $ruta . $nombreImagen;
                Storage::disk('public')->put($filePath, base64_decode($imagen));
                if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $urls[$key] = self::convertirImagenWebp($ruta, $uuid, $extension);
                } else {
                    $urls[$key] = asset('storage/' . $filePath);
                }
            }
            $texto = str_replace($imagenesBase64, $urls, $texto);
        }
        return $texto;
    }
}
