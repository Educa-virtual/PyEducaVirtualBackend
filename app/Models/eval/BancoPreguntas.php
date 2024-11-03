<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;

class BancoPreguntas extends Model
{

    public function procesarPreguntas($preguntasDB)
    {
        $preguntas = [];
        foreach ($preguntasDB as $item) {
            $item->preguntas = json_decode($item->preguntas, true);
            if ($item->idEncabPregId == -1) {
                if (is_array($item->preguntas)) {
                    $preguntas = array_merge($preguntas, $item->preguntas);
                }
            } else {
                array_push($preguntas, $item);
            }
        }
        return $preguntas;
    }
}
