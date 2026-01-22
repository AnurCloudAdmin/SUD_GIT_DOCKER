<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ImageNumbersImport implements ToCollection
{
    public $numbers = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Assuming first column has application number
            $this->numbers[] = trim($row[0]);
        }
    }
}

