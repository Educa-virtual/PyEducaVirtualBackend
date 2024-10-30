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
        // if ($request->iMatrId) {
        //     $iMatrId = $this->hashids->decode($request->iMatrId);
        //     $iMatrId = count($iMatrId) > 0 ? $iMatrId[0] : $iMatrId;
        // }
        // if ($request->iEstudianteId) {
        //     $iEstudianteId = $this->hashids->decode($request->iEstudianteId);
        //     $iEstudianteId = count($iEstudianteId) > 0 ? $iEstudianteId[0] : $iEstudianteId;
        // }
        // if ($request->iSemAcadId) {
        //     $iSemAcadId = $this->hashids->decode($request->iSemAcadId);
        //     $iSemAcadId = count($iSemAcadId) > 0 ? $iSemAcadId[0] : $iSemAcadId;
        // }

        // if ($request->iYAcadId) {
        //     $iYAcadId = $this->hashids->decode($request->iYAcadId);
        //     $iYAcadId = count($iYAcadId) > 0 ? $iYAcadId[0] : $iYAcadId;
        // }

        // if ($request->iTipoMatrId) {
        //     $iTipoMatrId = $this->hashids->decode($request->iTipoMatrId);
        //     $iTipoMatrId = count($iTipoMatrId) > 0 ? $iTipoMatrId[0] : $iTipoMatrId;
        // }

        // if ($request->iCurrId) {
        //     $iCurrId = $this->hashids->decode($request->iCurrId);
        //     $iCurrId = count($iCurrId) > 0 ? $iCurrId[0] : $iCurrId;
        // }


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
        // return $parametros;

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_MATRICULA
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            // foreach ($data as $key => $value) {
            //     $value->iMatrId = $this->hashids->encode($value->iMatrId);
            //     $value->iSemAcadId = $this->hashids->encode($value->iSemAcadId);
            //     $value->iEstudianteId = $this->hashids->encode($value->iEstudianteId);
            //     $value->iYAcadId = $this->hashids->encode($value->iYAcadId);
            //     $value->iTipoMatrId = $this->hashids->encode($value->iTipoMatrId);
            //     $value->iCurrId = $this->hashids->encode($value->iCurrId);
            // }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
}
