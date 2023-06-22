<?php

namespace App\Imports;

use App\Models\People;
use Maatwebsite\Excel\Concerns\ToModel;

class PeopleImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new People([
            'first_name'=>$row[0],
            'last_name1'=>$row[1],
            'last_name2'=>$row[2],
            // 'birth_date'=>$row[11],
            'cell_phone'=>$row[3],

            'street'=>$row[4],
            'home'=>$row[5],
            'zone'=>$row[6],

            'streetB'=>$row[7],
            'homeB'=>$row[8],
            'zoneB'=>$row[9],

            'ci'=>$row[10],
            // 'birth_date'=>$row[11],


        ]);
    }
}
