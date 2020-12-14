<?php

namespace App\Http\Controllers;

use App\Notifications\ReportUpdate;
use Illuminate\Http\Request;
use App\Report;
use App\ReportType;
use App\Witness;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            "witnesses"=>['nullable'],
          ]);

         $witnesses = null;
         if($data['hasWitness'] && $data['witnesses'] != null){
              $witnesses = $this->toArray($data['witnesses']);
         }

         //check auth
         $user = null;
         if(!auth('api')->check()){
            //assign guest user
          $user = User::where('email','guest@prms.com')->first();
         }else{
             //user is authenticated
             $user = auth('api')->user();
         }


          $report = new Report;
          $report->report_type_id = ReportType::where('type',$data['type'])->first()->id;
          $report->details = $data['details'];
          $report->additional = $data['additional'] ;
          $report->hasWitness = !empty($witnesses);
          $report->date = $data['date'];


          $report->user_id = $user->id;
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
              if(!empty($witnesses)){
                foreach($witnesses as $witness){
                $report->witnesses()->create([
                     "name"=>$witness['name'],
                     "phone"=>$witness['phone'],
                ]);
              }
            }
              //email reference number if user is authenticated
              $msg = "Your report has been submitted for review";
              $this->emailReferenceNumber($ref_num,$msg,"pending",$user);
             return response(['reference_number'=>$ref_num]);
            }
    }

    //generate unique reference number
   public function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

public function emailReferenceNumber($ref,$message,$status,$user){
    $data = [
     "to" =>$user->email,
     "reference_number"=>strtoupper($ref),
     "message"=>$message,
     "status" => $status
    ];
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


          $witnesses = null;
         if($data['hasWitness'] && $data['witnesses'] != null){
              $witnesses = $this->toArray($data['witnesses']);
         }

          $report = Report::find($id);
          $report->report_type_id = ReportType::where('type',$data['type'])->first()->id;
          $report->details = $data['details'];
          $report->additional = $data['additional'];
          $report->hasWitness = !empty($witnesses);
          $report->date = $data['date'];
          if($report->save()){
              //add location
              $report->address()->update([
                'city'=>$data['city'],
                'parish'=>$data['parish'],
                'street'=>$data['street'],
              ]);

                     if(!empty($witnesses)){
                foreach($witnesses as $witness){
                $report->witnesses()->create([
                     "name"=>$witness['name'],
                     "phone"=>$witness['phone'],
                ]);
              }
            }


          }
          return response(['success'=>true]);
    }

    public function types(){
        return response(['types'=>ReportType::all()]);
    }

    public function all(){
        return response(['reports'=>auth('api')->user()->reports->loadMissing('address','witnesses')]);
    }

    public function report($id){
        return Report::where('id',$id)->with(['address','type','witnesses'])->first();
    }

        public function toArray($data){
        $json = preg_replace('/^parseresponse\((.*)\);/', '$1', $data);
        return json_decode($json,true);
    }

    public function destroy(Request $request, $id){
        return Report::find($id)->delete();
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
         $data = Report::with('address')->get()->pluck('address.parish')->toArray();
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
        $details = DB::table('notifications')
        ->orderBy('created_at','asc')
        ->get()
        ->filter(function($data) use ($ref){
           return strcasecmp(json_decode($data->data)->reference_number,$ref) == 0;
        });

        return response(['reports' => $details]);
    }


    public function parishStatistic($parish){
               $report = ReportType::with(['reports',function($q){
                $q->orderBy('created_at', 'asc');
               }])->get();

        return response($report);
    }
}