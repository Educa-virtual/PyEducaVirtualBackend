<?php
namespace App\Helpers;

use Hashids\Hashids;

class VerifyHash
{
    // codificar los id de los registros a enviar al frontend
    public static function encode($valor){
        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $hashing = $hashids->encode($valor);
        return $hashing;
        // return array_map([self::class, 'encodeFields'], $value);
    }

    // decodificar los id de los registros a enviar al backend
    public static function decodes($hash){

        $hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
        $decoded = $hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }

}
