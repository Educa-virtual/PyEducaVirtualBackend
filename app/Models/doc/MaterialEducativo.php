<?php

namespace App\Models\doc;

use App\Helpers\VerifyHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MaterialEducativo extends Model
{
    public static function obtenerMaterial(Request $request){
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $parametros = [
            $request->iCursosNivelGradId,
            $iDocenteId,
        ];
        
        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_SEL_materialEducativoDocentes '.$enviar;
        $data = DB::select($procedimiento, $parametros);
        return $data;
    }
    public static function guardarMaterial(Request $request){
        $iDocenteId = VerifyHash::decodes($request->iDocenteId);
        $idDocCursoId = intval($request->idDocCursoId);
        $iCredEntPerfId = intval($request->iCredEntPerfId);
        $iCursosNivelGradId = intval($request->iCursosNivelGradId);
        $cMatEducativoTitulo = $request->cMatEducativoTitulo;
        $cMatEducativoDescripcion = $request->cMatEducativoDescripcion;
        $cMatEducativoUrl = $request->cMatEducativoUrl;
        $iMatEducativoId = intval($request->iMatEducativoId) ?? NULL;
        $cIieeCodigoModular = $request->cIieeCodigoModular;
        $iSedeId = $request->iSedeId;
        $year = $request->year;
        $iPersId = $request->iPersId;

        $carpeta = 'materialEducativo';
        $verificar = count($cMatEducativoUrl ?? []);
        
        $folder = [];
        if ($verificar > 0) {
            ksort($cMatEducativoUrl, SORT_NUMERIC);
            foreach ($cMatEducativoUrl as $index => $file) {
           
                if(is_string($file)){

                    $folder[$index] = json_decode($file);

                }else{

                    $enlace = $year.'/'.$cIieeCodigoModular.'/'.$iSedeId.'/'.$iPersId.'/'.$iCredEntPerfId.'/'.$carpeta;  
                    $generado = Storage::disk('public')->put($enlace,$file);
                    $ruta = 'storage/'.$enlace.'/'.basename($generado);

                    $nombre = $file->getClientOriginalName();
                    $folder[$index] = [
                        "type" => 1,
                        "name" => $nombre,
                        "ruta" => $ruta,
                        "peso" => number_format(($file->getSize() / 1024),2).' KB',
                    ];

                }
                
            }
        }
        
        $conjunto = $folder ? json_encode($folder, JSON_UNESCAPED_UNICODE) : null;
        
        $parametros = [
            $iMatEducativoId ?? NULL,
            $iDocenteId,
            $idDocCursoId,
            $iCredEntPerfId,
            $iCursosNivelGradId,
            $cMatEducativoTitulo ?? NULL,
            $cMatEducativoDescripcion ?? NULL,
            $conjunto,
        ];
        
        $enviar = str_repeat('?,',count($parametros)-1).'?';
        $procedimiento = 'exec doc.Sp_INS_materialEducativoDocentes '.$enviar;
        $data = DB::selectOne($procedimiento, $parametros);
        return $data;
    }

    public static function eliminarMaterial(Request $request){
        
        $iMatEducativoId = $request->iMatEducativoId ?? NULL;
        $data = DB::update("UPDATE doc.material_educativos SET iEstado = 0 WHERE iMatEducativoId = ?", [$iMatEducativoId]);
        return $data;
    }

    public static function subirArchivo(Request $request){
        $validar = Validator::make(
            $request->all(),
            [
                'file' => 'required|mimes:pdf,jpeg,png'
            ],
            [
                'file.required' => 'Es necesario que cargue un archivo',
                'file.mimes' => 'El archivo debe ser formato PDF, JPEG o PNG; seleccione otro archivo.',
            ]
        );
        return $validar;
    }
}
