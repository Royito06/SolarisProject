<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AsistenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fecha = Carbon::now()->setTimezone('GMT-7')->format('d_M_Y , g:ia');

        $participantes = [
            [
                'nombre'             => 'Carlos Alberto',
                'apellido_paterno'   => 'Martínez',
                'apellido_materno'   => 'Soto',
                'curp'               => 'MASC050814HSRRTL01',
                'escuela_origen'     => 'CBTIS 132 - Hermosillo',
                'telefono'           => 6621234567,
                'fecha_nacimiento'   => '2005-08-14',
                'correo_electronico' => 'carlos.martinez@ejemplo.com',
                'contrasena'         => Hash::make('concursante123'),
                'email_verified_at'  => now(),
                'concursos'          => [1, 2],
            ],
            [
                'nombre'             => 'Valeria',
                'apellido_paterno'   => 'Gómez',
                'apellido_materno'   => 'Navarro',
                'curp'               => 'GONV060322MSRRTL02',
                'escuela_origen'     => 'COBACH Plantel Reforma',
                'telefono'           => 6629876543,
                'fecha_nacimiento'   => '2006-03-22',
                'correo_electronico' => 'valeria.gomez@ejemplo.com',
                'contrasena'         => Hash::make('concursante123'),
                'email_verified_at'  => now(),
                'concursos'          => [2],
            ],
            [
                'nombre'             => 'Luis Fernando',
                'apellido_paterno'   => 'López',
                'apellido_materno'   => 'Rentería',
                'curp'               => 'LORL051105HSRRTL03',
                'escuela_origen'     => 'Preparatoria Kino',
                'telefono'           => 6625551234,
                'fecha_nacimiento'   => '2005-11-05',
                'correo_electronico' => 'luis.lopez@ejemplo.com',
                'contrasena'         => Hash::make('concursante123'),
                'email_verified_at'  => now(),
                'concursos'          => [1, 3],
            ],
        ];

        foreach ($participantes as $p) {
            $concursos = $p['concursos'];
            unset($p['concursos']);
            $p['created_at'] = now();
            $p['updated_at'] = now();

            // Evitar duplicados por correo
            $existente = DB::table('asistentes')->where('correo_electronico', $p['correo_electronico'])->first();
            if ($existente) {
                $asistenteId = $existente->id;
            } else {
                $asistenteId = DB::table('asistentes')->insertGetId($p);
            }

            // Asociar concursos si no estan asociados
            foreach ($concursos as $cId) {
                $concursoExiste = DB::table('asistente_concurso')
                    ->where('id_asistente', $asistenteId)
                    ->where('id_concurso', $cId)
                    ->exists();

                if (!$concursoExiste) {
                    DB::table('asistente_concurso')->insert([
                        'id_asistente' => $asistenteId,
                        'id_concurso'  => $cId,
                    ]);
                }
            }

            // Verificar si ya tiene documentos
            $docExiste = DB::table('documentos')->where('id_asistente', $asistenteId)->first();
            if ($docExiste) {
                $docId = $docExiste->id;
            } else {
                $docId = DB::table('documentos')->insertGetId([
                    'id_asistente'       => $asistenteId,
                    'foto_asistente'     => 'fotos-de-asistentes/foto_ejemplo.jpg',
                    'acta_asistente'     => 'constancias-escuelas/constancia_ejemplo.jpg',
                    'constancia_escuela' => 'constancias-escuelas/constancia_ejemplo.jpg',
                    'fecha_creacion'     => $fecha,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // Verificar si ya tiene validacion en estado 'Espera'
            $valExiste = DB::table('validaciones')->where('id_documento', $docId)->where('estado', 'Espera')->exists();
            if (!$valExiste) {
                DB::table('validaciones')->insert([
                    'id_documento'     => $docId,
                    'id_revisor'       => null,
                    'estado'           => 'Espera',
                    'comentario'       => null,
                    'doc_aprobado'     => null,
                    'doc_rechazado'    => null,
                    'fecha_validacion' => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}
