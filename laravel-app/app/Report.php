<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;


class Report extends Model
{
     protected $cascadeDeletes = ['address','witnesses'];

    protected $fillable =[
        'details',
        'date',
        'reference_number'
    ];

//location of crime
        public function address(){
            return $this->morphOne(Address::class,'addressable');
        }

        public function type(){
            return $this->belongsTo(ReportType::class,'report_type_id');
        }

        public function user()
        {
            return $this->belongsTo(User::class,'user_id');
        }

        public function witnesses(){
            return $this->hasMany(Witness::class,'report_id');
        }
}
