<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SilabosController extends Controller
{
    protected $hashids;
    protected $iSilaboId;
    protected $iSemAcadId;
    protected $iYAcadId;
    protected $idDocCursoId;

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
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }

        if ($request->iSemAcadId) {
            $iSemAcadId = $this->hashids->decode($request->iSemAcadId);
            $iSemAcadId = count($iSemAcadId) > 0 ? $iSemAcadId[0] : $iSemAcadId;
        }

        if ($request->iYAcadId) {
            $iYAcadId = $this->hashids->decode($request->iYAcadId);
            $iYAcadId = count($iYAcadId) > 0 ? $iYAcadId[0] : $iYAcadId;
        }

        if ($request->idDocCursoId) {
            $idDocCursoId = $this->hashids->decode($request->idDocCursoId);
            $idDocCursoId = count($idDocCursoId) > 0 ? $idDocCursoId[0] : $idDocCursoId;
        }

        $parametros = [
            $request->opcion,
            $request->valorBusqueda ?? '-',

            $iSilaboId                          ?? NULL,
            $iSemAcadId                         ?? NULL,
            $iYAcadId                           ?? NULL,
            $idDocCursoId                       ?? NULL,
            $request->dtSilabo                  ?? NULL,
            $request->cSilaboDescripcionCurso   ?? NULL,
            $request->cSilaboCapacidad          ?? NULL,

            $request->iCredId

        ];

        try {
            $data = DB::select('exec acad.Sp_ACAD_CRUD_SILABOS
                ?,?,?,?,?,?,?,?,?,?', $parametros);

            foreach ($data as $key => $value) {
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
                $value->iSemAcadId = $this->hashids->encode($value->iSemAcadId);
                $value->iYAcadId = $this->hashids->encode($value->iYAcadId);
                $value->idDocCursoId = $this->hashids->encode($value->idDocCursoId);
            }

            $response = ['validated' => true, 'message' => 'se obtuvo la información', 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
            $codeResponse = 500;
        }

        return new JsonResponse($response, $codeResponse);
    }
    public function report(Request $request)
    {
        $request['iSilaboId'] = is_null($request->iSilaboId)
            ? null
            : (is_numeric($request->iSilaboId)
                ? $request->iSilaboId
                : ($this->hashids->decode($request->iSilaboId)[0] ?? null));

        $parametros = [
            "CONSULTAR_SILABO",
            '-',
            $request->iSilaboId,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL,
            NULL

        ];

        $query = DB::select(
            "EXECUTE acad.Sp_ACAD_CRUD_SILABOS ?,?,?,?,?,?,?,?,?,?",
            $parametros
        );

        $html = view('silabus_reporte', ["query" => $query[0]])->render();

        $pdf = Browsershot::html($html)->pdf();

         return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'Silabo.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="archivo.pdf"',
        ]);

    }
}
