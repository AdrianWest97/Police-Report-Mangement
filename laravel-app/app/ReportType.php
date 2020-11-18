<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    protected $fillable = [
        "type"
    ];

    public function reports(){
        return $this->hasMany(Report::class,'report_type_id');
    }
}