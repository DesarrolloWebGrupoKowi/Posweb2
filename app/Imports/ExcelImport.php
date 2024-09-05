<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ExcelImport implements ToCollection
{
    protected $importedData = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->importedData[] = ['codigo' => $row[0], 'stock' => $row[1]];
        }
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}


