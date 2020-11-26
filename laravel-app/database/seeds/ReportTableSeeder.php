<?php

use Illuminate\Database\Seeder;
use App\Report;
use App\ReportType;
use App\User;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Report::truncate();
    $faker = Faker\Factory::create();
    $parishes = [
        "Hanover",
        "St. Elizabeth",
        "St. James",
        "Trelawny",
        "Westmoreland",
        "Clarendon",
        "Manchester",
        "St. Ann",
        "St. Catherine",
        "St. Mary",
        "Kingston",
        "Portland",
        "St. Andrew",
        "St. Thomas"
     ];

       $users = User::where('is_admin',0)->get();
       foreach($users as $user){
        $report = Report::create([
            "details"=>$faker->realText($maxNbChars = 200, $indexSize = 2),
            "additional"=>$faker->realText($maxNbChars = 200, $indexSize = 2),
            "date" => $faker->dateTimeThisMonth($max = 'now', $timezone = null),
            "hasWitness" => false,
            "report_type_id" => ReportType::inRandomOrder()->first()->id,
            "reference_number" => $this->unique_id(6),
            "user_id" => $user->id
        ]);

        $report->address()->create([
            'city'=> $faker->city,
            'street'=>$faker->streetAddress,
            'parish'=>$parishes[array_rand($parishes)]
        ]);
       }

    }

public function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}
}