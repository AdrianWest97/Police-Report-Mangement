<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTableUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("trn",9)->nullable()->unique();
            $table->dateTime("dob")->nullable();
            $table->string("phoneno",11)->nullable()->unique();
            $table->string("gender")->nullable();
            $table->boolean("is_admin")->default(false);
            $table->boolean("is_active")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}