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
    public function run()
    {
        DB::table('revisores')->insert([
            'nombre' => 'Asuseno',
            'apellido_paterno' => 'Asuseno',
            'apellido_materno' => 'Asuseno',
            'correo' => 'a@a.com',
            'contrasena' => Hash::make('secret'),
            
        ]);
        
    }
}
