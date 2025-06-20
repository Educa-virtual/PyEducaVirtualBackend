<?php

namespace App\Http\Controllers\bienestar;

use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Models\bienestar\RecordatorioFechas;
use Exception;
use Illuminate\Http\Request;

class RecordarioFechasController extends Controller
{
    public function verFechasEspeciales(Request $request)
    {
        try {
            $data = RecordatorioFechas::selCumpleanios($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verRecordatorioPeriodos(Request $request)
    {
        try {
            $data = RecordatorioFechas::selRecordatorioPeriodos($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function verConfRecordatorio(Request $request)
    {
        try {
            $data = RecordatorioFechas::selCumpleaniosConfiguracion($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }

    public function actualizarConfRecordatorio(Request $request)
    {
        try {
            $data = RecordatorioFechas::updCumpleaniosConfiguracion($request);
            return FormatearMensajeHelper::ok('se obtuvo la información', $data);
        } catch (Exception $e) {
            return FormatearMensajeHelper::error($e);
        }
    }
}
