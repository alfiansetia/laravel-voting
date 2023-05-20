<?php

namespace Database\Seeders;

use App\Models\Calon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Calon::create([
            'name'      => 'Calon1',
            'gender'    => 'male',
            'partai'    => 'Padi',
            'address'   => 'Ngetrep',
        ]);

        Calon::create([
            'name'      => 'Calon2',
            'gender'    => 'female',
            'partai'    => 'Ketela',
            'address'   => 'Kacangan',
        ]);

        Calon::create([
            'name'      => 'Calon3',
            'gender'    => 'male',
            'partai'    => 'Padi',
            'address'   => 'Ngumbul',
        ]);

        Calon::create([
            'name'      => 'Calon4',
            'gender'    => 'female',
            'partai'    => 'Ketela',
            'address'   => 'Manggir',
        ]);
    }
}
