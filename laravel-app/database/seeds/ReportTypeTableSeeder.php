<?php

use App\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportType::truncate();
        ReportType::create([
            "type"=>"Crime Inccident",
        ]);
          ReportType::create([
         "type"=>"Vehicle Accident",
        ]);
         ReportType::create([
              "type"=>"Covid-19 Breach",
        ]);
        ReportType::create([
              "type"=>"Other",
        ]);
    }
}