<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecepcionProductoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Nueva recepción realizada';
    public $recepcion;
    public $nomTienda;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($recepcion, $nomTienda)
    {
        $this->recepcion = $recepcion;
        $this->nomTienda = $nomTienda;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.Recepcion');
    }
}
