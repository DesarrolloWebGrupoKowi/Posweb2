<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubirSolicitudCancelacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Ejecutar tu procedimiento almacenado en SQL Server
            Log::info("Sp_Subida_SolicitudCancelacionTicket se va a ejecutar.");
            DB::statement("EXEC Sp_Subida_SolicitudCancelacionTicket");

            Log::info("Sp_Subida_SolicitudCancelacionTicket ejecutado correctamente.");
        } catch (\Throwable $e) {
            Log::error("Error al ejecutar Sp_Subida_SolicitudCancelacionTicket: " . $e->getMessage());
            throw $e; // Para que Laravel marque el job como fallido si aplica
        }
    }
}
