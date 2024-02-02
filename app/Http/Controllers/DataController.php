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
        $token = '37BnlLu_FSDxEscl5oLZ6AAMPl7wjo64';

        $http = new \GuzzleHttp\Client([
            'verify' => base_path('cacert.pem'),
        ]);

        var_dump($response = Http::setClient($http)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Cache-Control' => 'no-cache',
                'Accept' => 'application/vnd.api+json',
                'Content-type' => 'application/vnd.api+json',
            ])
            ->get('http://airmonitor.k42.app/items/measurments'));

        if($response->ok()){
            return json_decode($response->getBody(), true);
        }

        else{
            $error = json_decode($response->getBody(), true);
            return 'Chyba: ' . $error['errors']['0']['message'] . ' Code: ' . $response->getStatusCode();
        }
    }
}

