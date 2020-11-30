<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_people', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("fname");
            $table->string("lname");
            $table->string("age")->nullable();
            $table->string("gender");
            $table->string("attributes")->nullable(); //Age, weight, height, hair color, eye color
            $table->longText("last_seen_details");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missing_people');
    }
}