<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use Spatie\LaravelPdf\Facades\Pdf;

class BibliografiaController extends Controller
{
    protected $hashids;
    protected $iBiblioId;
    protected $iTipoBiblioId;
    protected $iSilaboId;


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
        if ($request->iBiblioId) {
            $iBiblioId = $this->hashids->decode($request->iBiblioId);
            $iBiblioId = count($iBiblioId) > 0 ? $iBiblioId[0] : $iBiblioId;
        }
        if ($request->iTipoBiblioId) {
            $iTipoBiblioId = $this->hashids->decode($request->iTipoBiblioId);
            $iTipoBiblioId = count($iTipoBiblioId) > 0 ? $iTipoBiblioId[0] : $iTipoBiblioId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }

       /* $parametros = [
            $request->opcion,
            $iBiblioId                      ?? NULL,
            $iTipoBiblioId                  ?? NULL,
            $iSilaboId                      ?? NULL,
            $request->cBiblioAutor          ?? NULL,
            $request->cBiblioTitulo         ?? NULL,
            $request->cBiblioAnioEdicion    ?? NULL,
            $request->cBiblioEditorial      ?? NULL,
            $request->cBiblioUrl            ?? NULL,
            $request->iEstado               ?? NULL,

            $request->iCredId

        ];*/

//Linea de código Optimizada-----------------------------
$parametros = [
    $validatedData['opcion'],
    $iBiblioId ?? NULL,
    $iTipoBiblioId ?? NULL,
    $iSilaboId ?? NULL,
    $validatedData['cBiblioAutor'] ?? NULL,
    $validatedData['cBiblioTitulo'] ?? NULL,
    $validatedData['cBiblioAnioEdicion'] ?? NULL,
    $validatedData['cBiblioEditorial'] ?? NULL,
    $validatedData['cBiblioUrl'] ?? NULL,
    $validatedData['iEstado'] ?? NULL,
    $validatedData['iCredId'],
];
//----------fin de coigo optimizado------------------------

        try {
            $query = DB::select(
                //Ejecusion del procedimiento almacenado en la BD
                "EXECUTE acad.Sp_INS_bibliografia ?,?,?,?,?,?,?,?,?,?",
                $parametros
            );
/*/Linea de codigo ORIGINAL---------------------------
            foreach ($query as $key => $value) {
                $value->iBiblioId = $this->hashids->encode($value->iBiblioId);
                $value->iTipoBiblioId = $this->hashids->encode($value->iTipoBiblioId);
                $value->iSilaboId = $this->hashids->encode($value->iSilaboId);
            }*/
//----------------------------------------------------
//Linea de codigo nueva Optimizada
        foreach ($query as $key => &$value) {
            $propertiesToEncode = ['iBiblioId', 'iTipoBiblioId', 'iSilaboId'];
            
            foreach ($propertiesToEncode as $property) {
                if (isset($value->$property)) {
                    $value->$property = $this->hashids->encode($value->$property);
                }
    }
}
//------------fin de codigo mejorado--------------------------------
            $response = [
                'validated' => true,
                'message' => 'se obtuvo la información',
                'data' => $query,
            ];

            $estado = 200;
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'opcion' => 'required',
            ],
            [
                'opcion.required' => 'Hubo un problema al obtener la acción',
            ]
        );
        if ($request->iBiblioId) {
            $iBiblioId = $this->hashids->decode($request->iBiblioId);
            $iBiblioId = count($iBiblioId) > 0 ? $iBiblioId[0] : $iBiblioId;
        }
        if ($request->iTipoBiblioId) {
            $iTipoBiblioId = $this->hashids->decode($request->iTipoBiblioId);
            $iTipoBiblioId = count($iTipoBiblioId) > 0 ? $iTipoBiblioId[0] : $iTipoBiblioId;
        }
        if ($request->iSilaboId) {
            $iSilaboId = $this->hashids->decode($request->iSilaboId);
            $iSilaboId = count($iSilaboId) > 0 ? $iSilaboId[0] : $iSilaboId;
        }

        /*
        $parametros = [
            $request->opcion,
            $iBiblioId                      ?? NULL,
            $iTipoBiblioId                  ?? NULL,
            $iSilaboId                      ?? NULL,
            $request->cBiblioAutor          ?? NULL,
            $request->cBiblioTitulo         ?? NULL,
            $request->cBiblioAnioEdicion    ?? NULL,
            $request->cBiblioEditorial      ?? NULL,
            $request->cBiblioUrl            ?? NULL,
            $request->iEstado               ?? NULL

            //$request->iCredId

        ];*/
//Linea de codigo nueva Optimizada--------------
        $parametros = [
            $request->opcion,
            $iBiblioId ?? null,
            $iTipoBiblioId ?? null,
            $iSilaboId ?? null,
            ...array_map(fn($key) => $request->$key ?? null, [
                'cBiblioAutor',
                'cBiblioTitulo',
                'cBiblioAnioEdicion',
                'cBiblioEditorial',
                'cBiblioUrl',
                'iEstado'
            ])
        ];

//----------------------------------------------
        try {
            $query = DB::select(
                "EXECUTE acad.Sp_INS_bibliografia ?,?,?,?,?,?,?,?,?,?",
                $parametros
            );
           
            if ($query[0]->iBiblioId > 0) {

                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $estado = 200;
            } else {
                $response = ['validated' => false, 'mensaje' => 'No se ha podido guardar la información.'];
                $estado = 500;
            }
        } catch (Exception $e) {
            $response = [
                'validated' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ];
            $estado = 500;
        }

        return new JsonResponse($response, $estado);
    }
}
