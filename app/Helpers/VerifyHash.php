<?php 
namespace App\Helpers;

use Hashids\Hashids;

class VerifyHash
{
    protected static $hashids;

    public function __construct(){
        self::$hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }

    // codificar los id de los registros a enviar al frontend
    public static function encode($value){
        return array_map([self::class, 'encodeFields'], $value);
    }
    
    // decodificar los id de los registros a enviar al backend
    public static function decode($value){
        if (is_null($value)) {
            return null;
        }
        return is_numeric($value) ? $value : (self::$hashids->decode($value)[0] ?? null);
    }
    
}