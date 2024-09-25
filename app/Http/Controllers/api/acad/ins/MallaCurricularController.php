<?php

namespace App\Http\Controllers\api\acad\ins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MallaCurricularController extends Controller
{
    public function registrar(){
        $ins_query = DB::insert("INSERT INTO acad.curriculas
        iModalServId
      ,iCurrNotaMinima
      ,iCurrTotalCreditos
      ,iCurrNroHoras
      ,cCurrPerfilEgresado
      ,cCurrMencion
      ,nCurrPesoProcedimiento
      ,cCurrPesoConceptual
      ,cCurrPesoActitudinal
      ,bCurrEsLaVigente
      ,cCurrRsl
      ,dtCurrRsl
      ,iEstado
        ");
    }
}
