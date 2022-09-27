<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;
use File;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/Data/Estados.json");
        $estados = json_decode($json);

        foreach ($estados as $key => $valor) {
            Estado::create([
                    "NomEstado" => $valor->NomEstado,
                    "Status" => $valor->Status
            ]);
        }
    }
}
