<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'response',
        'status',
    ];
           public function report(){
            return $this->belongsTo(Report::class,'report_id');
        }
}