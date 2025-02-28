<?php

namespace App\Http\Controllers;

use App\Helpers\CollectionStrategy;
use App\Helpers\ResponseHandler;
use Exception;
use Illuminate\Http\Request;

class FileController extends AbstractDatabaseOperation
{
  protected function getProcedureName(): string
  {
    return 'acad.SP_SEL_ObtenerDocenteSedeMasivo';
  }

  protected function getParams(): array
  {
    return ['iYAcadId', 'json', 'iSedeId'];
  }

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
      $query = self::handleRequest($request, new CollectionStrategy());

      return ResponseHandler::success($query, 'Archivo validado correctamente.');
    } catch (Exception $e) {
      return ResponseHandler::error('Error al validar el archivo.', 500, $e->getMessage());
    }
  }
}
