<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {

//    $client = new GuzzleClient();
//    $reponse = $client->request('GET','http://api.steampowered.com/IPlayerService/GetSteamLevel/v1/?key=CE19708F198D1C59D03BA98664831BEF&steamid=76561198362786552' );
//    return $reponse;


//    $arr = [
//        'url'=>request()->url(),
//        'duration'=>'321',
//        'visitors'=>'21321',
//        'period'=>'2',
//        'time'=>
//            '1:50:01',
//        'scroll'=>'2130',
//        'httpref'=>'https://google.com',
//        'dislikes'=>'312',
//        'likes'=>'31'
//    ];

    $arr = [
        'url'=>request()->url(),
        'time'=> '1:50:01',
        'httpref'=>'https://google.com',
    ];

    $arr = json_encode($arr);
    return $arr;
});

Route::post('get-data',['uses'=>'VisitorController@getData','as'=>'getData']);
Route::post('update-data',['uses'=>'VisitorController@updateData','as'=>'updateData']);