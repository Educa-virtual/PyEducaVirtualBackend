<?php

namespace App\Http\Controllers\ere;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\ere\EvaluacionExclusion;
use Exception;
use Illuminate\Http\Request;

class EvaluacionExclusionesController extends Controller
{
    public function verExclusiones(Request $request)
    {
        try {
            $data = EvaluacionExclusion::selEvaluacionExclusiones($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function guardarExclusion(Request $request)
    {
        try {
            $data = EvaluacionExclusion::insEvaluacionExclusion($request);
            return FormatearMensajeHelper::ok('Se guardó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarExclusion(Request $request)
    {
        try {
            $data = EvaluacionExclusion::updEvaluacionExclusion($request);
            return FormatearMensajeHelper::ok('Se actualizó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verExclusion(Request $request)
    {
        try {
            $data = EvaluacionExclusion::selEvaluacionExclusion($request);
            return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function eliminarExclusion(Request $request)
    {
        try {
            $data = EvaluacionExclusion::delEvaluacionExclusion($request);
            return FormatearMensajeHelper::ok('Se eliminó la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
