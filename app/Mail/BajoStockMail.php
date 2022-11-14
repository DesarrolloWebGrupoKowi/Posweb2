<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BajoStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $articulosBajoInventario;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $articulosBajoInventario)
    {
        $this->subject = $subject;
        $this->articulosBajoInventario = $articulosBajoInventario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Email.BajoStock');
    }
}
