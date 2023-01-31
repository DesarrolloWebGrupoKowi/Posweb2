<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelacionTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Aprobación de Solicitud de Cancelación de Ticket';
    public $solicitudCancelacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitudCancelacion)
    {
        $this->solicitudCancelacion = $solicitudCancelacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.CancelacionTicket');
    }
}
