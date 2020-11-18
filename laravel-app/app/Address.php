<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        "parish",
        "street",
        "city"
     ];


     //morph to many models (polymorphism)
    public function addressable(){
        return $this->morphTo();
    }
}