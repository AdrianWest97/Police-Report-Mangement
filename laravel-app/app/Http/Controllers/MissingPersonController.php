<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use App\MissingPerson;
use Illuminate\Support\Facades\Storage;
use Image;

class MissingPersonController extends Controller
{

    public function store(Request $request, $mode, $id=null){
        $data = $this->validate($request,[
            'fname' => ['required','string'],
            'lname' => ['required','string'],
            'gender' => ['required','string'],
            'age' => ['nullable','string'],
            'parish' => ['required','string'],
            'city' => ['required','string'],
            'street' => ['required','string'],
            'attributes' => ['nullable','string'], //key value
            'last_seen_details' =>['required','string']
        ]);

        //create report

        $report = $mode == 'add' ?  new MissingPerson : MissingPerson::find($id);
        $report->fname = $data['fname'];
        $report->lname = $data['lname'];
        $report->gender = $data['gender'];
        $report->age = $data['age'] ?  $data['age'] : null;
        $report->attributes = $data['attributes'] ?  $data['attributes'] : null;
        $report->last_seen_details = $data['last_seen_details'];
        $report->save();
        //address

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


    public function destroy($id){
        return MissingPerson::destroy($id);
    }
    public function allMissing(){
          return MissingPerson::with(['address','image'])->get();
    }

}
