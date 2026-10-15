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
        /*PRIMERA RONDA 2024*/
        /*
        DB::table('revisores')->insert([
            'nombre' => 'LUIS PATRICIO',
            'apellido_paterno' => 'RAMIREZ',
            'apellido_materno' => 'RODRIGUEZ',
            'correo' => 'luis.ramirez@unison.mx',
            'contrasena' => Hash::make('Z2r3m1RL'),            
        ]);

        DB::table('revisores')->insert([
            'nombre' => 'MARIA DEL ROCIO',
            'apellido_paterno' => 'GOMEZ',
            'apellido_materno' => 'COLIN',
            'correo' => 'rocio.gomez@unison.mx',
            'contrasena' => Hash::make('Z2m4GR1M'),
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
            'contrasena' => Hash::make('Z25qz1VA'),
        ]);
        */
        
        /* segunda ronda*/
        DB::table('revisores')->insert([
            'nombre' => 'BEATRIZ GUADALUPE',
            'apellido_paterno' => 'ZARAGOZA',
            'apellido_materno' => 'PALACIOS',
            'correo' => 'beatriz.zaragoza@unison.mx',
            'rol'=>'Revisor',
            'created_at'=>now(),
            'contrasena' => Hash::make('Az4g1r1ZGB'),
        ]);
    }
}
