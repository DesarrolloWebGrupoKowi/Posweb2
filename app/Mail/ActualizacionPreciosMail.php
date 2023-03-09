<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActualizacionPreciosMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Nueva Actualización de Precios';
    public $preciosActualizados;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($preciosActualizados)
    {
        $this->preciosActualizados = $preciosActualizados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.ActualizacionPreciosMail');
    }
}
