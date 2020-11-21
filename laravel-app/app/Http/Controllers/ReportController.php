<?php

namespace App\Http\Controllers;

use App\Mail\SendReference;
use Illuminate\Http\Request;
use App\Report;
use App\ReportType;
use App\Witness;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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
          $report = new Report;
          $report->report_type_id = ReportType::where('type',$data['type'])->first()->id;
          $report->details = $data['details'];
          $report->additional = $data['additional'];
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
              if($data['witnesses']){
                $report->witnesses()->create([
                     "name"=>$data['witnesses'][0]['name'],
                     "phone"=>$data['witnesses'][0]['phone'],
                ]);
              }

              //email reference number
              $this->emailReferenceNumber($ref_num);
          }
          return response(['reference_number'=>$ref_num]);
    }

    //generate unique reference number
   public function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

public function emailReferenceNumber($ref){
    $data = [
     "to" =>auth()->user()->email,
     "reference_number"=>strtoupper($ref)
    ];
     Mail::send(new SendReference($data)); //mail handler
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

}
