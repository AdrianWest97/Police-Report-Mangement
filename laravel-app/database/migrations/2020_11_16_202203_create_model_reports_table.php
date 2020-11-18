<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText("details");
            $table->longText("additional")->nullable();
            $table->integer("report_type_id");
            $table->string("date");
            $table->integer("status")->default(0); //0 pending //2 under review //approved
            $table->boolean("hasWitness")->default(false);
            $table->string("reference_number");
            $table->integer("user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_reports');
    }
}
