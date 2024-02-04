<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Data;

class DataController extends Controller
{
    /**
     * Function that runs when going to homepage (viewing the data after logging in)
     */
    public function index(){
        $data = Data::all()->sortByDesc('date_created')->take(20); //<--- Loads data from database, sorts them by the date and uses only the 20 newest records
        return view('dashboard', ['data' => $data]);
    }

    /**
     * Function used for loading the data from API to the app database, i did this because if this was a real app, i wouldn't want my users to spam requests to the
     * API each time they'd refresh the page. They should only spam my database which is going to get periodically refreshed. Actually to take things even further,
     * I should probably implement some sort of a cache for this so i wouldn't strain my database too much. I should probably also limit the amount of requests too.
     */
    private function fetchData(){
        $token = Auth::user()->API_token; //<--- I stored the token to the database to the user table, but i realise it's not the best when i want to schedule this function

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
            $data = $this->updateDB($data); //<--- If the data are returned they get saved into the app database
            return $data;
        }

        else{
            $error = json_decode($response->getBody(), true);
            return 'Error: ' . $error['errors']['0']['message'] . ' Code: ' . $response->getStatusCode(); //<--- Otherwise error is shown
        }
    }

    /**
     * Function that stores the data into the database
     */
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

