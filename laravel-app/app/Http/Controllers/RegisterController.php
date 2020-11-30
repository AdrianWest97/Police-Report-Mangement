<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string'],
            'phone' => ['required','string'],
            'street' => ['required','string'],
            'cty' => ['required','string'],
            'parish' => ['required','string'],
            'trn' => ['required','numeric'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', "min:8", 'confirmed']
        ]);

       $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->address()->create([
            'parish' => $request->parish,
            'city' => $request->city,
            'street' => $request->street,
        ]);
    }
}