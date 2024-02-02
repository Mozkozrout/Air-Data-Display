<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    public function index(){
        $data = $this->fetchData();
        return view('dashboard', ['data' => $data]);
    }

    private function fetchData(){
        $token = "37BnlLu_FSDxEscl5oLZ6AAMPl7wjo64";

        $response = Http::withUrlParameters([
            'endpoint' => 'http://airmonitor.k42.app',
            'page' => 'items',
            'collection' => 'measurments'
        ])
            ->withOptions([
                'verify' => base_path('cacert.pem'),
                ])
                ->withQueryParameters([
                    'access_token' => $token //<--- I would add it like "withToken" or using "withHeaders" but it kept adding it like an array with one item
                ])
            ->get('{+endpoint}/{page}/{collection}');

        if($response->ok()){
            dd(json_decode($response->getBody(), true));
            return json_decode($response->getBody(), true)['data'];
        }

        else{
            $error = json_decode($response->getBody(), true);
            return 'Chyba: ' . $error['errors']['0']['message'] . ' Code: ' . $response->getStatusCode();
        }
    }
}

