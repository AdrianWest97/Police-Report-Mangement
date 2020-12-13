<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\MissingPerson;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReportUpdate;
use Image;
use App\User;
use Illuminate\Support\Facades\Auth;
class MissingPersonController extends Controller
{

    public function store(Request $request, $mode, $id=null){

        $data = $this->validate($request,[
            'name' => ['required','string'],
            'gender' => ['required','string'],
            'age' => ['nullable','string'],
            'parish' => ['required','string'],
            'city' => ['required','string'],
            'street' => ['required','string'],
            'last_seen_details' =>['required','string'],
            'last_seen' =>['required','string'],
            'status' =>['nullable','integer'],
        ]);

       // create report
          $user = null;
         if(!auth('api')->check()){
            //assign guest user
          $user = User::where('email','guest@prms.com')->first();
         }else{
            //user is authenticated
             $user = auth('api')->user();

         }

        $ref_num = $this->unique_id(6);
        $report = $mode == 'add' ?  new MissingPerson : MissingPerson::find($id);
        $report->name = $data['name'];
        $report->gender = $data['gender'];
        $report->age = $data['age'] ?  $data['age'] : null;
        $report->last_seen_date = $data['last_seen'];
        $report->last_seen_details = $data['last_seen_details'];
        $report->reference_number = $mode == 'add' ? $ref_num : $report->reference_number;
        $report->status = $data['status'];
        $report->user_id = $user->id;

        $report->save();
        // //address

        $report->address()->updateOrCreate(
            ["addressable_id" => $report->id],
            [
            "city" => $data['city'],
            "parish" => $data['parish'],
            "street" => $data['street'],

        ]);
         //save images
             if($request->hasFile('image')){
                 $this->saveImage($report,$request->file('image'));
             }
        //      //email user if authenticated and mode is add
        $this->emailReferenceNumber($report->reference_number,"Your has been submitted","pending",$user);
         return MissingPerson::where('id',$report->id)->with(['address','image'])->first();
    }


        public function saveImage($report, $file){
              $destination = "public/images/";
              $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                 $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
                 $filename = preg_replace("/\s+/", '-', $filename);
                  $extension = $file->getClientOriginalExtension();
                  $fileNameToStore = $filename.'_'.time().'.'.$extension;
                        // Resize image
                        $resize = Image::make($file)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode('jpg');
                        // Create hash value
                        $hash = md5($resize->__toString());
                        // Prepare qualified image name
                        $image = $hash."jpg";
                        // Put image to storage
                        $save = Storage::put("{$destination}{$fileNameToStore}", $resize->__toString());
                    if($save){

                        $report->image()->updateOrCreate(
                            ['imageable_id'=> $report->id],
                            [
                            'path' =>  "/images/".$fileNameToStore
                        ]);
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


    public function destroy($id){
        return MissingPerson::destroy($id);
    }
    public function allMissing(){
          return MissingPerson::with(['address','image'])->orderBy('created_at','desc')->get();
    }

}
