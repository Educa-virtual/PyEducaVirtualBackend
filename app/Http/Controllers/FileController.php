<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;
use App\Helpers\CollectionStrategy;
use App\Http\Controllers\ApiController;

class FileController extends Controller
{

  public function uploadFile(Request $request)
  {
    $file = $request->file('file');
    $path = $file->store('uploads');
    return response()->json(['path' => $path]);
  }

  public function downloadFile(Request $request)
  {
    $path = $request->input('template');

    return response()->download(storage_path("templates/import/bulk-data/$path.xlsx"), basename($path), [
      'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]);
  }

  public function importarEstudiantesMatriculasExcelPlatform(Request $request)
  {

    try {
      $query = (new ApiController(new CollectionStrategy()))->execProcedure(
        $request,
        "sp_importar_estudiantes_matriculas_excel",
        [
          'iSedeId' => $request->iSedeId,
          'iSemAcadId' => null,
          'iYAcadId' => $request->iYAcadId,
          'iCredId' => $request->iCredId,
          'tipo' => $request->tipo,
          'json' => $request->json
        ],
        [
          'd'
        ]
      );

      return ResponseHandler::success($query, 'Auditoría de accesos obtenida correctamente.');
    } catch (\Exception $e) {
      return ResponseHandler::error('Error al obtener la auditoría de accesos.', 500, $e->getMessage());
      //throw $th;
    }
  }
}
