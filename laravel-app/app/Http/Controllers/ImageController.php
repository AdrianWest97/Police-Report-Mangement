<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getImage($image){
        return asset('/images/'.$image);
    }
}