<?php

namespace App\Http\Controllers;

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
        
        try {
          # code...
        } catch (\Throwable $e) {
          # code...
        }

        return response()->download(storage_path("templates/import/bulk-data/$path"), basename($path), [
          'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      ]);
      
    }
}
