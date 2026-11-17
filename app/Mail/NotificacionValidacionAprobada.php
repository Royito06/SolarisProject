<?php

namespace App\Mail;

use App\Asistente;
use App\Validaciones;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionValidacionAprobada extends Mailable
{
    use Queueable, SerializesModels;

    public $asistente;
    public $validacion;
    public $concursos;

    /**
     * Create a new message instance.
     *
     * @param  \App\Asistente  $asistente
     * @param  \App\Validaciones  $validacion
     * @param  string  $concursos
     * @return void
     */
    public function __construct(Asistente $asistente, Validaciones $validacion, $concursos = '')
    {
        $this->asistente = $asistente;
        $this->validacion = $validacion;
        $this->concursos = $concursos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = config('mail.from.address') ?: 'concurso.fismat@unison.mx';
        $fromName = config('mail.from.name') ?: 'Concurso Regional de Física y Matemáticas';

        return $this->from($fromAddress, $fromName)
                    ->subject('CRFyM - Confirmación de inscripción (Aprobado)')
                    ->view('vendor.notifications.email_aprobado', [
                        'asistente'          => $this->asistente,
                        'validacion'         => $this->validacion,
                        'nombre'             => $this->asistente->nombre,
                        'apellido_paterno'   => $this->asistente->apellido_paterno,
                        'apellido_materno'   => $this->asistente->apellido_materno,
                        'correo_electronico' => $this->asistente->correo_electronico,
                        'folio'              => $this->asistente->folio,
                        'estado'             => $this->validacion->estado,
                        'concursos'          => $this->concursos,
                    ]);
    }
}

