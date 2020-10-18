<?php

namespace App\Imports;

use App\Species;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class FirstSheetImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
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
