<?php

namespace App\Http\Controllers\acad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\acad\BuzonSugerenciasService;
use Exception;

class BuzonDirectorController extends Controller
{
    public function indexSugerencias(Request $request)
    {
        $sugerencias = BuzonSugerenciasService::obtenerSugerenciasDirector($request);
        
        return view('director.sugerencias', compact('sugerencias'));
    }

    public function showSugerencia($id)
    {
        return view('director.sugerencia-detalle', compact('sugerencia'));
    }
    
    public function responderSugerencia(Request $request, $id)
    {
        $request->validate([
            'respuesta' => 'required|string|max:1000',
            'estado' => 'required|in:aprobada,rechazada,en_revision'
        ]);

        return redirect()->route('director.sugerencias.show', $id)
            ->with('success', 'Respuesta enviada correctamente');
    }

    public function descargarArchivo($id, $archivo)
    {
        try {
            $data = BuzonSugerenciasService::descargarArchivo($id, $archivo);
            
            return response($data['contenido'])
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $data['nombreArchivo'] . '"');
                
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}


