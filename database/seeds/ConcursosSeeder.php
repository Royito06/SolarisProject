<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ConcursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('concursos')->insert([
            'nombre' => 'Concurso Regional de Física',
            
        ]);
        DB::table('concursos')->insert([
            'nombre' => 'Concurso Regional de Matemáticas',
            
        ]);
        DB::table('concursos')->insert([
            'nombre' => 'Preselectivo para la Olimpiada Nacional de Fisíca',
            
        ]);
        
    }
}
