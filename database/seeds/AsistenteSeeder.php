<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AsistenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('asistentes')->insert([
            'nombre' => 'Prueba',
            'apellido_paterno' => 'Paterno',
            'apellido_materno' => 'Materno',
            'correo_electronico' => 'prueba@gmail.com',
            'contrasena' => Hash::make('prueba'),
            
        ]);
    }
}
