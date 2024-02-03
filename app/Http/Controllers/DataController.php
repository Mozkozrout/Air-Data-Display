<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Data;

class DataController extends Controller
{
    public function index(){
        $data = $this->fetchData();
        $data = $this->sortData($data);
        return view('dashboard', ['data' => $data]);
    }

    private function sortData($data){
        usort($data, function($a, $b) {
            return strcmp($b['date_created'], $a['date_created']);
          });
          $data = array_slice($data, 0, 20);
          return $data;
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
                    'access_token' => $token //<--- I would add it using "withToken" or using "withHeaders" but it kept adding it to the request like an array with one element
                ])
            ->get('{+endpoint}/{page}/{collection}');

        if($response->ok()){
            $data = json_decode($response->getBody(), true)['data'];
            $data = $this->updateDB($data);
            return $data;
        }

        else{
            $error = json_decode($response->getBody(), true);
            return 'Chyba: ' . $error['errors']['0']['message'] . ' Code: ' . $response->getStatusCode();
        }
    }

    private function updateDB($data){
        $modelData = [];
        foreach($data as $item){
            array_push($modelData,
            Data::firstOrCreate([
                'data_id' => $item['id']
            ],[
                'sort' => $item['sort'],
                'user_created' => $item['user_created'],
                'user_updated' => $item['user_updated'],
                'date_created' => $item['date_created'],
                'date_updated' => $item['date_updated'],
                'HEX' => $item['HEX'],
                'temperature' => $item['temperature'],
                'co2' => $item['co2'],
                'c2ho' => $item['c2ho'],
                'humidity' => $item['humidity'],
                'check' => $item['check'],
                'pm10' => $item['pm10'],
                'pm25' => $item['pm25'],
                'tvoc' => $item['tvoc'],
                'valid' => $item['valid'],
            ]));
        }

        return $modelData;
    }
}

