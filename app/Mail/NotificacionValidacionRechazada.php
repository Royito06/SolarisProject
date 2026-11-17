<?php

namespace App\Mail;

use App\Asistente;
use App\Validaciones;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionValidacionRechazada extends Mailable
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
                    ->subject('CRFyM - Solicitud de inscripción con observaciones')
                    ->view('vendor.notifications.email_rechazado', [
                        'asistente'          => $this->asistente,
                        'validacion'         => $this->validacion,
                        'nombre'             => $this->asistente->nombre,
                        'apellido_paterno'   => $this->asistente->apellido_paterno,
                        'apellido_materno'   => $this->asistente->apellido_materno,
                        'correo_electronico' => $this->asistente->correo_electronico,
                        'estado'             => $this->validacion->estado,
                        'doc_rechazado'      => $this->validacion->doc_rechazado,
                        'comentario'         => $this->validacion->comentario,
                        'concursos'          => $this->concursos,
                    ]);
    }
}

