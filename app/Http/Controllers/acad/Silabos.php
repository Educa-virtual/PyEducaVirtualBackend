<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Hashids\Hashids;
use Spatie\LaravelPdf\Facades\Pdf;

class Silabos extends Controller
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

        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }

        $parametros = [
            $request->opcion ?? "CONSULTAR_SILABO",
            $request->valorBusqueda ?? '-',
            $iSilaboId                          ?? NULL,
            $iSemAcadId                         ?? NULL,
            $iYAcadId                           ?? NULL,
            $idDocCursoId                       ?? NULL,
            $request->dtSilabo                  ?? NULL,
            $request->cSilaboDescripcionCurso   ?? NULL,
            $request->cSilaboCapacidad          ?? NULL,
            $request->iCredId ?? NULL

        ];


        $query = DB::select(
            "EXECUTE acad.Sp_ACAD_CRUD_SILABOS ?,?,?,?,?,?,?,?,?,?",
            $parametros
        );

        $pdf = Pdf::view('silabus_reporte', ["query" => $query[0]])
            ->format('a4')
            ->name('silabus.pdf');

        $content = base64_encode($pdf->stream());
        $response = ['validated' => true, 'content' => $content, 'file' => 'RptIngMetasIndObj', 'mensaje' => 'Información obtenido exitosamente'];
        return new JsonResponse($response);
        // return Pdf::view('silabus_reporte', ["query" => $query[0]])
        //     ->format('a4')
        //     ->name('silabus.pdf');

        //return view("silabus_reporte",["query"=>$query[0]]);
    }
}
