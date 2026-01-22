<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class YourImport implements ToCollection
{
    public $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $this->data[] = $row;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
