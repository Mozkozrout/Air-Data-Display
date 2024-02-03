<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function index(){
        $data = $this->fetchData();
        $data = array_slice($data, 0, 20);
        return view('dashboard', ['data' => $data]);
    }

    private function fetchData(){
        $token = Auth::user()->API_token;

        $response = Http::withUrlParameters([
            'endpoint' => 'http://airmonitor.k42.app',
            'page' => 'items',
            'collection' => 'measurments'
        ])
            ->withOptions([
                'verify' => base_path('cacert.pem'),
                ])
                ->withQueryParameters([
                    'access_token' => $token //<--- I would add it using "withToken" or using "withHeaders" but it kept adding it like an array with one item
                ])
            ->get('{+endpoint}/{page}/{collection}');

        if($response->ok()){
            $data = json_decode($response->getBody(), true)['data'];
            usort($data, function($a, $b) {
                return strcmp($b['date_created'], $a['date_created']);
              });

            return $data;
        }

        else{
            $error = json_decode($response->getBody(), true);
            return 'Chyba: ' . $error['errors']['0']['message'] . ' Code: ' . $response->getStatusCode();
        }
    }
}

