<?php

namespace App\Http\Controllers;

use App\Notifications\ReportUpdate;
use Illuminate\Http\Request;
use App\Report;
use App\ReportType;
use App\Witness;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\User;
use App\Address;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{



    public function store(Request $request){
           $data = $this->validate($request,[
            "type" => ['required','string'],
            "parish" => ['required','string'],
            "city" => ['required','string'],
            "street" => ['required','string'],
            "details" => ['required','string'],
            "additional"=>['nullable'],
            "hasWitness"=>['nullable','boolean'],
            "date"=>['required','date'],
            "witnesses"=>['nullable']
          ]);

         $witnesses = null;
         if($data['hasWitness'] && $data['witnesses'] != null){
              $witnesses = $this->toArray($data['witnesses'])[0];
         }
          $report = new Report;
          $report->report_type_id = ReportType::where('type',$data['type'])->first()->id;
          $report->details = $data['details'];
          $report->additional = $data['additional'] ;
          $report->hasWitness = $data['hasWitness'];
          $report->date = $data['date'];
          $report->user_id = auth()->user()->id;
          //add reference number
             $ref_num = $this->unique_id(6);
             while(Report::where('reference_number',$ref_num)->count() > 0){
                  $ref_num = $this->unique_id(6);
             }
            $report->reference_number = $ref_num;
          if($report->save()){
              //add location
              $report->address()->create([
                'city'=>$data['city'],
                'parish'=>$data['parish'],
                'street'=>$data['street'],
              ]);
              if($data['hasWitness'] ){
                $report->witnesses()->create([
                     "name"=>$witnesses['name'],
                     "phone"=>$witnesses['phone'],
                ]);
              }
              //email reference number
              $msg = "Your report has been submitted for review";
              try{
              $this->emailReferenceNumber($ref_num,$msg,"pending");
             return response(['reference_number'=>$ref_num]);
              }catch(Exception $err){
              return response(['reference_number'=>$ref_num]);
              }
          }
    }

    //generate unique reference number
   public function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

public function emailReferenceNumber($ref,$message,$status){
    $data = [
     "to" =>auth()->user()->email,
     "reference_number"=>strtoupper($ref),
     "message"=>$message,
     "status" => $status
    ];
    $user = User::find(auth()->id());
   $user->notify(new ReportUpdate($data));
}

  public function update(Request $request, $id){
           $data = $this->validate($request,[
            "type" => ['required','string'],
            "parish" => ['required','string'],
            "city" => ['required','string'],
            "street" => ['required','string'],
            "details" => ['required','string'],
            "additional"=>['nullable'],
            "hasWitness"=>['nullable','boolean'],
            "date"=>['required','date'],
            "witnesses"=>['nullable']
          ]);

          $report = Report::find($id);
          $report->report_type_id = ReportType::where('type',$data['type'])->first()->id;
          $report->details = $data['details'];
          $report->additional = $data['additional'];
          $report->hasWitness = sizeof($data['witnesses']) > 0 ? true : false;
          $report->date = $data['date'];
          if($report->save()){
              //add location
              $report->address()->update([
                'city'=>$data['city'],
                'parish'=>$data['parish'],
                'street'=>$data['street'],
              ]);
              if($data['hasWitness'] && sizeof($data['witnesses'])!=0){
                $report->witnesses()->updateOrCreate([
                    ['report_id'],
                     "name"=>$data['witnesses'][0]['name'],
                     "phone"=>$data['witnesses'][0]['phone'],
                ]);

              }
          }
          return response(['success'=>true]);
    }

    public function types(){
        return response(['types'=>ReportType::all()]);
    }

    public function all(){
        return response(['reports'=>auth()->user()->reports]);
    }

    public function report($id){
        return response(['report'=>Report::where('id',$id)->with(['address','type','witnesses'])->first()]);
    }

        public function toArray($data){
        $json = preg_replace('/^parseresponse\((.*)\);/', '$1', $data);
        return json_decode($json,true);
    }

    public function destroy(Request $request, $id){
        $report = Report::find($id);
        return response(['deleted'=>$report->delete()]);
    }

    public function getEdit($id){
        $report = Report::where('id',$id)->with(['address','type','witnesses'])->first();
        return response(['report'=>$report]);
    }

    public function deleteWitness($id){
        $deleted = Witness::destroy($id);

        return response(['deleted'=>$deleted]);
    }


    public function reportByTypeChart(){
         $data = ReportType::select('type')->withCount('reports')->get();
        return response(['data'=>$data]);

    }

        public function chartByParish(){
         $data = Report::where('status',0)->with('address')->get()->pluck('address.parish')->toArray();
        $parishes = [
        "Hanover",
        "St. Elizabeth",
        "St. James",
        "Trelawny",
        "Westmoreland",
        "Clarendon",
        "Manchester",
        "St. Ann",
        "St. Catherine",
        "St. Mary",
        "Kingston",
        "Portland",
        "St. Andrew",
        "St. Thomas"
     ];
     $match = array_unique(array_count_values(array_merge($parishes, $data)));
               return response(['data'=>$match]);
    }

    public function ReportStatus($ref){
        return response(['reports' => DB::table('notifications')->get()->filter(function($data) use ($ref){
           return json_decode($data->data)->reference_number == strtolower($ref);
        })]);
    }


    public function parishStatistic($parish){
        $report = ReportType::with('reports',function($q){
            $q->whereHas('address',function($q){
                $q->where('parish','Hanover');
            });
        })->get();
        return response($report);
    }
}