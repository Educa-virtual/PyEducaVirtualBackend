<?php

namespace App\Http\Controllers\grl;

use App\Http\Controllers\Controller;
use App\Models\grl\FeriadoNacional;
use App\Helpers\FormatearMensajeHelper;
use App\Http\Requests\grl\ListarFeriadosNacionalesRequest;
use App\Http\Requests\grl\GuardarFeriadoNacionalRequest;
use App\Http\Requests\grl\GuardarFeriadoNacionalMasivoRequest;
use App\Http\Requests\grl\ActualizarFeriadoNacionalRequest;
use App\Http\Requests\grl\AplicarFeriadosNacionalesRequest;
use App\Http\Requests\grl\BorrarFeriadoNacionalRequest;

class FeriadosNacionalesController extends Controller
{
  public function listarFeriadosNacionales(ListarFeriadosNacionalesRequest $request)
  {
    try {
      $data = FeriadoNacional::selFeriadosNacionales($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function guardarFeriadoNacional(GuardarFeriadoNacionalRequest $request)
  {
    try {
      $data = FeriadoNacional::insFeriadoNacional($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function guardarFeriadoNacionalMasivo(GuardarFeriadoNacionalMasivoRequest $request)
  {
    try {
      $data = FeriadoNacional::insFeriadoNacionalMasivo($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function actualizarFeriadoNacional(ActualizarFeriadoNacionalRequest $request)
  {
    try {
      $data = FeriadoNacional::updFeriadoNacional($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function aplicarFeriadosNacionales(AplicarFeriadosNacionalesRequest $request)
  {
    try {
      $data = FeriadoNacional::updFeriadoNacional($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }

  public function borrarFeriadoNacional(BorrarFeriadoNacionalRequest $request)
  {
    try {
      $data = FeriadoNacional::delFeriadoNacional($request);
      return FormatearMensajeHelper::ok('Se obtuvo los datos', $data);
    } catch (\Exception $e) {
      return FormatearMensajeHelper::error($e);
    }
  }
}
