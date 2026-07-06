<?php

namespace App\Http\Controllers\grl;

use App\Enums\Perfil;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\grl\ActualizarYearRequest;
use App\Http\Requests\grl\GuardarYearRequest;
use App\Models\grl\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class YearController extends Controller
{
  public function listarYears(Request $request)
  {
    try {
      $data = Year::selYears($request);
      return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function verYear(Request $request)
  {
    try {
      $data = Year::selYear($request);
      return FormatearMensajeHelper::ok('Se obtuvo la información', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function guardarYear(GuardarYearRequest $request)
  {
    try {
      Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
      $data = Year::insYear($request);
      return FormatearMensajeHelper::ok('Se guardó la información', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function actualizarYear(ActualizarYearRequest $request)
  {
    try {
      Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
      $data = Year::updYear($request);
      return FormatearMensajeHelper::ok('Se actualizó la información', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function borrarYear(Request $request)
  {
    try {
      Gate::authorize('tiene-perfil', [[Perfil::ADMINISTRADOR_DREMO]]);
      $data = Year::delYear($request);
      return FormatearMensajeHelper::ok('Se eliminó la información', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }
}
