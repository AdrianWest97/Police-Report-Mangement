<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    protected $fillable=[
         "name",
         "phone",
         "email"
    ];
}
