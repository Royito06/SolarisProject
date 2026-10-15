<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RevisoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        /*Habilitar: Primera ronda 2025*/
        /*
        DB::table('revisores')->insert([
            'nombre' => 'Jose Italo',
            'apellido_paterno' => 'Sanchez',
            'apellido_materno' => 'Bermudez',
            'correo' => 'a219208499@unison.mx',
            'contrasena' => Hash::make('JS@nch2z'),            
        ]);

        DB::table('revisores')->insert([
            'nombre' => 'MARIA DEL ROCIO',
            'apellido_paterno' => 'GOMEZ',
            'apellido_materno' => 'COLIN',
            'correo' => 'rocio.gomez@unison.mx',
            'contrasena' => Hash::make('MG4m2zC!'),
        ]);
        
        DB::table('revisores')->insert([
            'nombre' => 'HECTOR ALFREDO',
            'apellido_paterno' => 'HERNANDEZ',
            'apellido_materno' => 'HERNANDEZ',
            'correo' => 'hector.hernandez@unison.mx',
            'contrasena' => Hash::make('Z2dn1nr2H'),
        ]);

        DB::table('revisores')->insert([
            'nombre' => 'PAOLA TONANZY',
            'apellido_paterno' => 'GARCIA',
            'apellido_materno' => 'MENDIVIL',
            'correo' => 'tonanzy.garcia@unison.mx',
            'contrasena' => Hash::make('A3cr1GTP'),
        ]);

        DB::table('revisores')->insert([
            'nombre' => 'ADRIAN',
            'apellido_paterno' => 'VAZQUEZ',
            'apellido_materno' => 'OSORIO',
            'correo' => 'adrian.vazquez@unison.mx',
            'contrasena' => Hash::make('Ch3ng1d4s'),
        ]);
        
        DB::table('revisores')->insert([
            'nombre' => 'Jazmin Sarahi',
            'apellido_paterno' => 'Flores',
            'apellido_materno' => 'Gomez',
            'correo' => 'a213207976@unison.mx',
            'contrasena' => Hash::make('JFL4r2s!'),
        ]);
	
	DB::table('revisores')->insert([
            'nombre' => 'Beatriz Gudadalupe',
            'apellido_paterno' => 'Zaragoza',
            'apellido_materno' => 'Palacios',
            'correo' => 'beatriz.zaragoza@unison.mx',
            'contrasena' => Hash::make('Z@r1g4z!'),
        ]);
        */

        /*Habilitar: Segunda ronda 2026*/
    DB::table('revisores')->insert([
            'nombre' => 'JESUS MIGUEL',
            'apellido_paterno' => 'VARELA',
            'apellido_materno' => 'LOPEZ',
            'correo' => 'a224216851@unison.mx',
            'contrasena' => Hash::make('MJ1L2r1V'),
        ]);
/*    DB::table('revisores')->insert([
            'nombre' => 'ALEJANDRA',
            'apellido_paterno' => 'FONSECA',
            'apellido_materno' => 'MORALES',
            'correo' => 'alejandra.fonseca@unison.mx',
            'contrasena' => Hash::make('Ac2sn4F'),
        ]);
*/  
    }
}
