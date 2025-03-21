<?php

namespace App\Services\Ere;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class ExtraerBase64
{
    public function __invoke($error_message)
    {
        return $this->extraer($error_message);
    }

    /*
        Extrar archivos codificados en base64 de un texto y guardar en storage

        Input: texto con o sin archivos codificadas en base64
        Output: mismo texto, archivos codificados en base64 reemplazadas por url
    */
    public static function extraer($texto, $carpeta = '')
    {
        if (strpos($texto, 'base64')) {
            $urls = array();
            preg_match('#data:image/[^;]+;base64,[A-Za-z0-9+/=]+#', $texto, $imagenesBase64);

            foreach ($imagenesBase64 as $key => $imagenB64) {
                $extension = explode('/', explode(':', substr($imagenB64, 0, strpos($imagenB64, ';')))[1])[1];
                $replace = substr($imagenB64, 0, strpos($imagenB64, ',') + 1);
                $imagen = str_replace($replace, '', $imagenB64);
                $imagen = str_replace(' ', '+', $imagen);
                $nombre =  Uuid::uuid4() . '.' . $extension; //Hash::make(date('YmdHis')) . '.' . $extension;
                Storage::disk('public')->put($carpeta . '/' . $nombre, base64_decode($imagen));
                $urls[$key] = asset('storage/' . $carpeta . '/' . $nombre);
            }
            $texto = str_replace($imagenesBase64, $urls, $texto);
        }
        return $texto;
    }
}
