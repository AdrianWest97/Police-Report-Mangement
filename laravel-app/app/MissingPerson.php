<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissingPerson extends Model
{

    protected $fillable = [
        "fname",
        "lname",
        "age",
        "gender",
        "attributes",
        "last_seen_details",
    ];
        public function address(){
         return $this->morphOne(Address::class,'addressable');
    }

         public function image(){
         return $this->morphOne(Image::class,'imageable');
    }
}