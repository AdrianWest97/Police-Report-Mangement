<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => ['auth:api']], function () {

Route::post('/create','ReportController@store');
Route::get('/all','ReportController@all');
Route::get('/report/{id}','ReportController@report');
Route::delete('/delete/{id}','ReportController@destroy');
Route::get('/edit/{id}','ReportController@getEdit');
Route::put('/update/{id}','ReportController@update');
Route::delete('/delete-witness/{id}','ReportController@deleteWitness');
Route::get('/chart-by-type', 'ReportController@reportByTypeChart');
Route::get('/chart-by-parish', 'ReportController@chartByParish');

Route::get('/user',function(Request $request){
return $request->user();
});


//admin
Route::prefix('admin')->group(function(){
    Route::get('/all-reports', 'AdminController@all')->name("all.reports");
        Route::put('/update-status', 'AdminController@updateStatus');
        Route::get('/card-data','AdminController@cardData');
        Route::get('/active-users','AdminController@active_users');
});

Route::get('/reportTypes','ReportController@types');
Route::post('/logout', 'LoginController@logout');
});

Route::post('/login', 'LoginController@login');
Route::post('/register', 'RegisterController@register');