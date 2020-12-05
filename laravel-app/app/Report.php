<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;
use Illuminate\Notifications\Notifiable;


class Report extends Model
{

    use Notifiable;

    protected $cascadeDeletes = ['address','witnesses'];

    protected $fillable =[
        'details',
        'date',
        'reference_number',
        'additional',
        'anonymous'
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

        public function responses(){
            return $this->hasMany(Response::class,'report_id');
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