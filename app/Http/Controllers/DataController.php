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
        try{
            $data = Data::all()->sortByDesc('date_created')->take(20);//<--- Loads data from database, sorts them by the date and uses only the 20 newest records
         } catch(Exception $ex){
            $data = $this->fetchData();//<--- if data loading failes, app refreshes them
         }
        return view('dashboard', ['data' => $data]);
    }

    /**
     * Function used for loading the data from API to the app database, i did this because if this was a real app, i wouldn't want my users to spam requests to the
     * API each time they'd refresh the page. They should only spam my database which is going to get periodically refreshed. Actually to take things even further,
     * I should probably implement some sort of a cache for this so i wouldn't strain my database too much. I should probably also limit the amount of requests too.
     *
     * Originally i intended to call this function every time the user wants to view the data, that is why it is written the way it is written. However now, when i
     * reworked this app to use database and to utilise this function to refresh the data in database on schedule I realise that the need to be logged in to access the
     * token and also the fact that this function returns the data or error message is pretty cumbersome. However It can still be called manually or when loading the data
     * from database fails.
     */
    public function fetchData(){
        try{
            $token = Auth::user()->API_token; //<--- I stored the token to the database to the user table, but i realise it's not the best when i want to schedule this function
        }catch(Exception $ex){
            $token = '37BnlLu_FSDxEscl5oLZ6AAMPl7wjo64'; //<--- yeah it is not exactly safe and its a bit dumb considering i put this into the DB to make it safer
        }

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
            //return $data;
            return redirect()->intended('/')->withSUccess('Data Refreshed');
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
        return $modelData; //<--- returns array of Data objects
    }
}

