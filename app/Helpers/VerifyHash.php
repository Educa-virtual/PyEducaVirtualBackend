<?php 
namespace App\Helpers;

use Hashids\Hashids;

class VerifyHash
{
    // codificar los id de los registros a enviar al frontend
    public static function encode($valor){
        $hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $hashing = $hashids->encode($valor);
        return $hashing;
        // return array_map([self::class, 'encodeFields'], $value);
    }
    
    // decodificar los id de los registros a enviar al backend
    public static function decodes($hash){
       
        $hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $decoded = $hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }
    
}