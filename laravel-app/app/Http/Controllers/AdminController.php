<?php

namespace App\Http\Controllers;
use App\Notifications\ReportUpdate;
use App\Mail\ReportUpdate as sendMail;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\User;
use Exception;

class AdminController extends Controller
{
    public function all(){
        $reports = Report::where('status',0)
        ->orWhere('status',1)
        ->with(['address','user','type'])
        ->orderBy('created_at','asc')
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
        $user = User::find($report->user->id)->first();

        //send report notififaction
        // $report->notify(new ReportUpdate(
        //    [
        //        "reference_number"=>$report->reference_number,
        //        "message"=>$data['status'],
        //        "status"=>$data['status']
        //        ]
        // ));

        try{
        $this->UpdateEmail($user,$data['message'],$report->reference_number,strtolower($report->status($data['status'])));
         return response(['success'=>true]);
        }catch(Exception $ex){
            return response(['success'=>false]);
        }
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
    $user = User::where('is_admin',0)->with(['address'])->get();
    return response(['users'=>$user]);
}

}