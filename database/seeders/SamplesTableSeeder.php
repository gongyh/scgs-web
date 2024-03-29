<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SamplesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Samples::class, 10)->create();
    }
}
