<?php

namespace App\Http\Controllers;

use App\Mail\ReportUpdate;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\User;


class AdminController extends Controller
{
    public function all(){
        $reports = Report::with(['address','user','type'])
        ->orderBy('created_at','asc')
        ->get();
        return response(['reports'=>$reports]);
    }


    public function updateStatus(Request $request){
        $data = $this->validate($request,[
            'id'=>'required','integer',
            'status'=>'required','integer',
            'message'=>'required','string'
        ]);

        //find report
        $report = Report::find($data['id']);

        $report->responses()->create([
            'response'=>$data['message'],
            'status'=>$data['status'],
        ]);

        $report->status = $data['status'];
        $report->save();
        return response(['success'=>$this->emailUpdate($data['message'],$report->reference_number)]);
    }


    public function emailUpdate($message,$ref){
    $data = [
     "to" =>auth()->user()->email,
     "message"=>$message,
     "ref"=>$ref
    ];
     //mail handler
     return Mail::send(new ReportUpdate($data));
}

public function cardData(){
    //total users
    $users = User::where('is_admin',0)->count();
    $total_reports = Report::count();
    $pending_reports = Report::where('status','!=',2)->count();

    $data = [
        'users' => $users,
        'total_reports' => $total_reports,
        'pending' => $pending_reports
    ];

    return response($data);
}

public function active_users(){
    $user = User::where('is_admin',0)->with(['address'])->get();
    return response(['users'=>$user]);
}

}