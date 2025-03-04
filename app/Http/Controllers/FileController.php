<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use Exception;
use Illuminate\Http\Request;

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
    $path = $request->input('fileName');



    return response()->download(storage_path("templates/import/bulk-data/$path"), basename($path), [
      'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ]);
  }

  public function validatedFile(Request $request)
  {
    try {
      $query = (new ApiController(new CollectionStrategy()))->execProcedure($request, 'acad.SP_SEL_ObtenerDocenteSedeMasivo', [
        'iYAcadId',
        'json',
        'iSedeId',
      ]);

      return ResponseHandler::success($query, 'Archivo validado correctamente.');
    } catch (Exception $e) {
      return ResponseHandler::error('Error al validar el archivo.', 500, $e->getMessage());
    }
  }
}
