<?php

namespace App\Models\eval;

use Illuminate\Database\Eloquent\Model;

class BancoPreguntas extends Model
{

    // saca las preguntas sin cabecera y las coloca al mismo nivel
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
