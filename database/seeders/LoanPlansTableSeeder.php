<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('loan_plans')->insert([
        ['id'=>1,'weeks'=>52,'interest_rate'=>8],
        ['id'=>2,'weeks'=>24,'interest_rate'=>5],
        ['id'=>3,'weeks'=>27,'interest_rate'=>4],
        ]);
    }
}
