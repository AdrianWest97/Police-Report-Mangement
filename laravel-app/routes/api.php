<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


//no auth required
Route::group(['middleware' => ['api']], function () {
Route::get('/status/{ref}','ReportController@ReportStatus');
Route::get('/missing/all','MissingPersonController@allMissing');
Route::get('/parish_statistic/{parish}','ReportController@parishStatistic');
Route::get('/reportTypes','ReportController@types');
Route::prefix('report')->group(function(){
Route::post('/create','ReportController@store');
});

//missing person
Route::prefix('missing')->group(function(){
    Route::post('/create/{mode}/{id?}','MissingPersonController@store');
});


//charts
Route::get('/chart-by-parish', 'ReportController@chartByParish');
Route::get('/chart-by-type', 'ReportController@reportByTypeChart');


});


Route::get('/role',function(){
$user = User::where('is_admin',1)->first();
$user->assignRole('administrator');
});

Route::group(['middleware' => ['auth:api']], function () {

Route::prefix('report')->group(function(){
Route::get('/all','ReportController@all');
Route::get('/report/{id}','ReportController@report');
Route::delete('/delete/{id}','ReportController@destroy');
Route::get('/edit/{id}','ReportController@getEdit');
Route::put('/update/{id}','ReportController@update');
Route::delete('/delete-witness/{id}','ReportController@deleteWitness');
});

Route::get('/user',function(Request $request){
return $request->user()->load('address');
});


//admin
Route::prefix('admin')->group(function(){
    Route::get('/all-reports', 'AdminController@all')->name("all.reports");
        Route::put('/update-status', 'AdminController@updateStatus');
        Route::put('/update-missing-status', 'AdminController@updateMissingPersonStatus');
        Route::get('/card-data','AdminController@cardData');
        Route::get('/active-users','AdminController@active_users');
});



//missing person
Route::prefix('missing')->group(function(){
    Route::delete('/delete/{id}','MissingPersonController@destroy');
});

Route::get('/load_image/{image}','ImageController@getImage');
Route::post('/logout', 'LoginController@logout');
});

Route::post('/login', 'LoginController@login');
Route::post('/register', 'RegisterController@register');