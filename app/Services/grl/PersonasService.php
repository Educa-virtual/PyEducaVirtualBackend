<?php

namespace App\Services\grl;

use App\Models\grl\Persona;
use Illuminate\Http\Request;

class PersonasService
{
    public static function actualizarDatosPersonales($iPersId, Request $request) {
        $request->validate([
            'cPersCorreo' => 'nullable|email',
        ]);
        return Persona::updDatosPersonales($iPersId, $request);
    }
}
