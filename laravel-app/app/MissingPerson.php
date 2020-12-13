<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;


class MissingPerson extends Model
{

    use CascadesDeletes;
        protected $cascadeDeletes = ['address','image'];

    protected $fillable = [
        "name",
        "age",
        "gender",
        "last_seen",
        "last_seen_details",
        "reference_number",
        "status",
        "user_id",
        "headline"
    ];
        public function address(){
         return $this->morphOne(Address::class,'addressable');
    }

         public function image(){
         return $this->morphOne(Image::class,'imageable');
    }

          public function status($status){
            if($status == 0){
                return "PENDING";
            }else if($status == 1){
                return "REVIEWING";
            }else if($status == 2){
                return "APPROVED";
            }
        }
}