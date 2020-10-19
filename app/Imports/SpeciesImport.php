<?php

namespace App\Imports;

use App\Species;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SpeciesImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        //
        unset($rows[0]);

        $this->createData($rows);
    }

    public function createData($rows)
    {
        foreach ($rows as $row) {
            Species::create([
                'name' => $row[0],
                'fasta' => $row[1],
                'gff' => $row[2],
            ]);
        }
    }
}
