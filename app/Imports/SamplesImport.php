<?php

namespace App\Imports;

use App\Samples;
use App\Applications;
use App\Species;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SamplesImport implements ToCollection
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
                'applications_id' => Applications::where('name', $row[1])->value('id'),
                'species_id' => Species::where('name', $row[2])->value('id'),
                'projects_id' => $projectID,
                'pairends' => $row[3],
                'filename1' => $row[4],
                'filename2' => $row[5]
            ]);
        }
    }
}
