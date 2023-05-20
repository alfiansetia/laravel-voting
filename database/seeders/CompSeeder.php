<?php

namespace Database\Seeders;

use App\Models\Comp;
use Illuminate\Database\Seeder;

class CompSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comp::create([
            'name'              => 'VOTING',
            'address'           => 'Jl Mbah pojok No 36',
        ]);
    }
}
