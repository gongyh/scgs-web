<?php

namespace App\Imports;

use App\Samples;
use App\Applications;
use App\Species;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SamplesImport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            0 => new FirstSheetImport(),
        ];
    }
}


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
        $projectID = request('projectID');
        foreach ($rows as $row) {
            Samples::create([
                'sampleLabel' => $row[0],
                'library_id' => $row[1],
                'library_strategy' => $row[2],
                'library_source' => $row[3],
                'library_selection' => $row[4],
                'pairends' => $row[5] == 'paired' ? true : false,
                'platform' => $row[6],
                'instrument_model' => $row[7],
                'design_description' => $row[8],
                'filetype' => $row[9],
                'applications_id' => Applications::where('name', $row[10])->value('id'),
                'species_id' => Species::where('name', $row[11])->value('id'),
                'projects_id' => $projectID,
                'filename1' => $row[12],
                'filename2' => $row[13]
            ]);
        }
    }
}
