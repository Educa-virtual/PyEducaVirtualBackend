<?php
namespace App\Http\Controllers\bienestar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FichaPdfController extends Controller
{
    public function mostrarFichaPdf()
    {
        $datos = [
            'direccion_domiciliaria' => [ 
                'tipo_via' => 'Tipo de Vía: (3) Calle',
                'nombre_via' => 'Los Tulipane',
                'numero_puerta' => '1161',
                'block' => '-',
                'interior' => '-',
                'piso' => '3',
                'mz' => 'J',
                'lote' => '28',
                'km' => '-',
                'departamento' => 'Moquegua',
                'provincia' => 'Ilo',
                'distrito' => 'Ilo',
                'referencia' => 'Ciudad Jardín'
            ],

            'direccion_procedencia' => [ 
                'tipo_via' => 'Tipo de Vía: (4) avenida',
                'nombre_via' => 'Los Girasoles',
                'numero_puerta' => '110',
                'block' => '-',
                'interior' => '-',
                'piso' => '9',
                'mz' => 'L',
                'lote' => '30',
                'km' => '-',
                'departamento' => 'Moquegua',
                'provincia' => 'Ilo',
                'distrito' => 'Ilo',
                'referencia' => 'Ciudad Nueva'
            ],

            'estudiante' => [
                'apellido_paterno' => 'Salas',
                'apellido_materno' => 'Rodriguez',
                'nombres' => 'Milagros Rosmery',
                'dni' => '71875085',
                'fecha_nacimiento' => '06-02-2005',
                'sexo' => 'Femenino',
                'estado_civil' => 'Soltera',
                'num_hijos' => '0'
            ],

            'nacimiento' => [
                'pais' => 'Perú',
                'departamento' => 'Moquegua',
                'provincia' => 'Mariscal Nieto',
                'distrito' => 'Moquegua'
            ],

            'ieducativa' => [
                'tipoIE' => 'Perú',
                'nombreIE' => 'Moquegua',
            ],

            'direccion_padre' => [
                'tipo_via' => 'Tipo de Vía: (7) pasaje',
                'nombre_via' => 'Los Alamos',
                'numero_puerta' => '19',
                'block' => '-',
                'interior' => '-',
                'piso' => '-',
                'mz' => 'M',
                'lote' => '27',
                'km' => '-',
                'departamento' => 'Moquegua',
                'provincia' => 'Ilo',
                'distrito' => 'Ilo',
                'referencia' => 'La Pampa'
            ],

            'direccion_madre' => [
                'tipo_via' => 'Tipo de Vía: (7) pasaje',
                'nombre_via' => 'Los Alamos',
                'numero_puerta' => '19',
                'block' => '-',
                'interior' => '-',
                'piso' => '-',
                'mz' => 'M',
                'lote' => '27',
                'km' => '-',
                'departamento' => 'Moquegua',
                'provincia' => 'Ilo',
                'distrito' => 'Ilo',
                'referencia' => 'La Pampa'
            ],
            'est_civil_padres' => [
                'estado_civil' => 'Casados',

            ],

        ];

        return view('pdfFicha.ficha', $datos);

    }
}
