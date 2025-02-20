<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;

class MatriculaController extends Controller
{
    protected $hashids;
    protected $iMatrId;
    protected $iEstudianteId;
    protected $iSemAcadId;
    protected $iYAcadId;
    protected $iTipoMatrId;
    protected $iCurrId;


    public function __construct()
    {
        $this->hashids = new Hashids('PROYECTO VIRTUAL - DREMO', 50);
        
    }
//RUTA PARA COMUNICACION CON EL FRONT END LARAVEL - ANGULAR 14 FEB
    public function registrar(Request $request){
        return new JsonResponse("Recibido", 200);
    }

    public function list(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iMatrId           ?? NULL,
            $iEstudianteId ?? NULL,
            $iSemAcadId    ?? NULL,
            $iYAcadId  ?? NULL,
            $iTipoMatrId   ?? NULL,
            $iCurrId   ?? NULL,
            $request->dtMatrMigracion   ?? NULL,
            $request->dtMatrFecha   ?? NULL,
            $request->cMatrNumero   ?? NULL,
            $request->bMatrEsProforma   ?? NULL,
            $request->bMatrEsRegular    ?? NULL,
            $request->dtMatrFechaProforma   ?? NULL,
            $request->bMatrReservado    ?? NULL,
            $request->dtMatrReservado   ?? NULL,
            $request->bMatrReanudado    ?? NULL,
            $request->dtMatrReanudado   ?? NULL,
            $request->nMatrCosto    ?? NULL,
            $request->cMatrNroRecibo    ?? NULL,
            $request->bMatrPagado   ?? NULL,
            $request->nMatrTotalCreditos    ?? NULL,
            $request->cMatrObservaciones    ?? NULL,
            $request->iMatrEstado   ?? NULL,
            $request->iEstado   ?? NULL,
            $request->iSesionId ?? NULL,
            $request->dtCreado  ?? NULL,
            $request->dtActualizado ?? NULL,
            $request->iSedeId   ?? NULL,

        ];

        try {
            $data = DB::select('exec acad.Sp_SEL_matricula
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
