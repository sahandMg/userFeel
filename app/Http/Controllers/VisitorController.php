<?php

namespace App\Http\Controllers;

use App\Address;
use App\Feedback;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

class VisitorController extends Controller
{
    public function getData(Request $request){

//        $client = new GuzzleClient();
//        $response = $client->request('GET','http://localhost/userFeel/public/test');
        $response = $request;
//        $response = json_decode($response->getBody(),true);
//        $response = json_decode($response,true);

        if(!Address::where('url',$response['url'])->first()){
            $adr = new Address();
            $adr->url = $response['url'];
            $adr->save();
        }

        $adr = Address::where('url',$response['url'])->first();
        $visitor = new Visitor();
        $visitor->ip = $response['ip'];
//        $visitor->period = $response['period'];
        $visitor->time = Carbon::now();
        $visitor->httpref = $response['httpref'];
//        $visitor->scroll = $response['scroll'];
        $visitor->address_id = $adr->id;
        $visitor->save();

        if(!Feedback::where('address_id',$adr->id)->first()){

            $feedback = new Feedback();
            $feedback->likes = 0;
            $feedback->dislikes = 0;
            $feedback->address_id = $adr->id;
            $feedback->save();
        }


        return 'done';
    }


    public function updateData(Request $request){

//        $client = new GuzzleClient();
//        $resp = $client->request('GET','http://localhost/userFeel/public/test' );
        $resp = $request;
//        $resp = json_decode($resp->getBody(),true);
        $adr =  Address::where('url',$resp['url'])->first();
        $visitors = Address::where('url',$resp['url'])->first()->visitors;

        $adr->update(['visitornum'=>count($visitors)]);

        $minutes = 0;
        foreach ($visitors as $visitor){
            if($request->ip == $visitor->ip){
                $visitor->update([
                    'period'=>$resp['period'],
                    'scroll'=>$resp['scroll']
                ]);
            }
            $minutes = $minutes + $visitor->period;
        }
        $adr->update(['duration' => $minutes/count($visitors)]);



        $feeds = Address::where('url',$resp['url'])->first()->feedback;
        $feeds->dislikes = $feeds->dislikes + $resp['dislikes'];
        $feeds->likes = $feeds->likes + $resp['likes'];
        $feeds->save();

        return 'updated';
    }
}
