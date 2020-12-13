<?php

namespace App\Http\Controllers;

use App\MissingPerson;
use App\Notifications\ReportUpdate;
use App\Report;
use Illuminate\Http\Request;
use App\User;
use Exception;

class AdminController extends Controller
{
    public function all(){
        $reports = Report::with(['address','user','type','witnesses'])
        ->orderBy('created_at','desc')
        ->get();
        return response(['reports'=>$reports]);
    }


    public function updateStatus(Request $request){
        $data = $this->validate($request,[
            'id'=>'required','integer',
            'status'=>'required','integer',
            'message'=>'required','string',
            'userId'=>'required'
        ]);
        //find report
        $report = Report::find($data['id']);

        $report->responses()->create([
            'response'=>$data['message'],
            'status'=>$data['status'],
        ]);
       $report->status = $data['status'];
       $report->save();
       //email authenticated user
        $user = User::find($report->user->id);
        $this->UpdateEmail($user,$data['message'],$report->reference_number,strtolower($report->status($data['status'])));
        return response(['success'=>true]);
        }

        public function updateMissingPersonStatus(Request $request){
        $data = $this->validate($request,[
            'id'=>'required','integer',
            'status'=>'required','integer',
            'message'=>'required','string',
            "headline"=>'nullable','string'

        ]);
        //find report
      $report = MissingPerson::find($data['id']);
       $report->status = $data['status'];
       $report->headline = $data['headline'] ?  $data['headline']  : "";
       $report->save();
    //    //find user
       $user = User::find($report->user_id);
       var_dump($user);
       $this->UpdateEmail($user,$data['message'],$report->reference_number,strtolower($report->status($data['status'])));
        return response(['success'=>true]);
        }


    public function UpdateEmail($user,$message,$ref,$status){
    $data = [
     "message"=>$message,
     "reference_number"=>$ref,
     "status" => $status,
     "to" => $user->email,
    ];
     //update report history
     $user->notify(new ReportUpdate($data));
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
    $user = User::Where('is_active',1)
    ->Where('is_admin',0)
    ->with(['address'])->get();
    return response(['users'=>$user]);
}

}
