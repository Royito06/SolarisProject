<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValidacionDefinicionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('validacion_definiciones')->insert([
            'nombre' => 'Espera',
            'descripcion' => 'Etiqueta utilizada para representar cuando un usuario ha subido documentos pero estos aun no han sido validados, junto con su información.',
            
        ]);
        DB::table('validacion_definiciones')->insert([
            'nombre' => 'Rechazado',
            'descripcion' => 'Etiqueta utilizada para representar cuando a un usuario se le ha encontrado algún problema en cualquiera de los datos utilizados para su registro, lo cual no lo hace apto para la inscripción.',
            
        ]);
        DB::table('validacion_definiciones')->insert([
            'nombre' => 'Aprobado',
            'descripcion' => 'Etiqueta utilizada para representar cuando a un usuario se le han aceptado sus datos de registro, lo cual lo hacen apto para la inscripción.',
            
        ]);
    }
}
