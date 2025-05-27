<?php

namespace App\Helpers;

use Hashids\Hashids;
use Illuminate\Http\Request;

class VerifyHash
{
    private static $hashids;

    public static function initialize()
    {
        self::$hashids = new Hashids(config('hashids.salt'), config('hashids.min_length'));
    }
    // codificar los id de los registros a enviar al frontend
    public static function encode($value)
    {
        VerifyHash::VerifyHashInitialize();
        return array_map([self::class, 'encodeFields'], $value);
    }

    // decodificar los id de los registros a enviar al backend
    public static function decodes($hash)
    {
        VerifyHash::VerifyHashInitialize();

        if (is_null($hash)) {
            return null;
        }
        if (is_numeric($hash)) {
            return $hash;
        }
        $decoded = self::$hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }

    public static function encodeField($hash)
    {   
        VerifyHash::VerifyHashInitialize();

        $decoded = self::$hashids->encode($hash);
        return $decoded ? $decoded[0] : null;
    }

    public static function validateRequest(Request $request, $fieldsToDecode)
    {   
        VerifyHash::VerifyHashInitialize();

        foreach ($fieldsToDecode as $field) {
            $request[$field] = VerifyHash::decodes($request->$field);
        }
        return $request;
    }

    public static function encodeFields($item, $fieldsToEncode)
    {   
        VerifyHash::VerifyHashInitialize();

        foreach ($fieldsToEncode as $field) {
            if (isset($item->$field)) {
                $item->$field = self::$hashids->encode($item->$field);
            }
        }
        return $item;
    }

    public static function encodeRequest($data, $fieldsToDecode)
    {   
        VerifyHash::VerifyHashInitialize();

        return array_map(function ($item) use ($fieldsToDecode) {
            return VerifyHash::encodeFields($item, $fieldsToDecode);
        }, $data);
    }

    public static function VerifyHashInitialize()
    {
        if (is_null(self::$hashids)) {
            self::initialize(); // Inicializar si no está inicializado
        }
    }

    // codificar los id de los registros a enviar al frontend
    public static function encodexId($valor){
        $hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $hashing = $hashids->encode($valor);
        return $hashing;
        // return array_map([self::class, 'encodeFields'], $value);
    }

    public static function decodesxId($hash){
       
        $hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        $decoded = $hashids->decode($hash);
        return $decoded ? $decoded[0] : null;
    }
}
