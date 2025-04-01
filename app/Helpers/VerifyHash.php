<?php 
namespace App\Helpers;

use Hashids\Hashids;
use Illuminate\Http\Request;
class VerifyHash
{
    // codificar los id de los registros a enviar al frontend
    public static function encode($value){
        return array_map([self::class, 'encodeFields'], $value);
    }
    
    // decodificar los id de los registros a enviar al backend
    public static function decodes($hash){
       
        $hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $decoded = $hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }

    public static function validateRequest(Request $request, $fieldsToDecode){
        foreach ($fieldsToDecode as $field) {
            $request[$field] = VerifyHash::decode($request->$field);
        }
        return $request;
    }
    
}