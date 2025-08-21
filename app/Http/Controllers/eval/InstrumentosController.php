<?php

namespace App\Http\Controllers\eval;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\eval\InstrumentoEvaluacion;
use Exception;
use Illuminate\Http\Request;

class InstrumentosController extends Controller
{
    public function guardarInstrumentos(Request $request){
        try {
            // Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = InstrumentoEvaluacion::guardarInstrumentos($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
        
    }
    public function editarInstrumentos(Request $request){

        try {
            // Gate::authorize('tiene-perfil', [[Perfil::DIRECTOR_IE]]);
            $data = InstrumentoEvaluacion::editarInstrumentos($request);
            return FormatearMensajeHelper::ok('Datos obtenidos', $data);
        } catch (Exception $ex) {
            return FormatearMensajeHelper::error($ex);
        }
        
    }
}
