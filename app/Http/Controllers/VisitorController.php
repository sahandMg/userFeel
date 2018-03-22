<?php

namespace App\Http\Controllers;

use App\Address;
use App\Feedback;
use App\Visitor;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

class VisitorController extends Controller
{
    public function getData(){

        $client = new GuzzleClient();
        $response = $client->request('GET','http://localhost/userFeel/public/test');
        $response = json_decode($response->getBody(),true);

        if(!Address::where('url',$response['url'])->first()){
            $adr = new Address();
            $adr->url = $response['url'];
            $adr->save();
        }

        $adr = Address::where('url',$response['url'])->first();
        $visitor = new Visitor();
        $visitor->ip = request()->ip();
        $visitor->period = $response['period'];
        $visitor->time = $response['time'];
        $visitor->httpref = $response['httpref'];
        $visitor->scroll = $response['scroll'];
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

        $client = new GuzzleClient();
        $resp = $client->request('GET','http://localhost/userFeel/public/test' );

        $resp = json_decode($resp->getBody(),true);
        $adr =  Address::where('url',$resp['url'])->first();
        $adr->update([
            'visitornum'=>$resp['visitors'],
            'duration'=>$resp['duration']

        ]);


        $visitors = Address::where('url',$resp['url'])->first()->visitors;

        foreach ($visitors as $visitor){

            $visitor->update([
                'period'=>$resp['period'],
                'time'=>$resp['time'],
                'scroll'=>$resp['scroll']
            ]);
        }

        $feeds = Address::where('url',$resp['url'])->first()->feedback;
        $feeds->dislikes = $feeds->dislikes + $resp['dislikes'];
        $feeds->likes = $feeds->likes + $resp['likes'];
        $feeds->save();

        return 'updated';
    }
}
