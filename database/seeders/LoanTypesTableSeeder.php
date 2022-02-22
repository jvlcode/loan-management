<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LoanTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('loan_types')->insert([
            ['id'=>1,'name'=>'Small Business' ,'description'=>'Small Business Loans'],
            ['id'=>2,'name'=>'Mortgages','description'=>'Mortgages'],
            ['id'=>3,'name'=>'Personal Loans','description'=>'Personal Loans'],
        ]);
    }
}
