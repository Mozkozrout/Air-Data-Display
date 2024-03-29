@extends('layout')
@section('content')

@if (is_string($data))

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card border-danger shadow">
                    <div class="card-body text-danger">
                            <h5 class="text-center">{{ $data }}</h5>
                            </br>
                            <div class="d-grid mx-auto">
                                <button class="btn btn-outline-danger" href="{{ route('home') }}">Retry</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else

<div class="container-fluid">
        <div class="row justify-content-center">
            <div class="mb-5">
                <div class="card shadow">
                <h3 class="card-header text-center">Most Recent</h3>
                    <div class="card-body">

                        <div class="container text-right">
                            <div class="row p-1 border-bottom">
                                <div class="col">
                                    Temperature: {{ $data->first()['temperature'] }}
                                </div>
                                <div class="col">
                                    Humidity: {{ $data->first()['humidity'] }}
                                </div>
                            </div>
                            <div class="row p-1 border-bottom">
                                <div class="col">
                                    CO2: {{ $data->first()['co2'] }}
                                </div>
                                <div class="col">
                                    PM25: {{ $data->first()['pm25'] }}
                                </div>
                                <div class="col">
                                    PM10: {{ $data->first()['pm10'] }}
                                </div>
                                <div class="col">
                                    CH20: {{ $data->first()['c2ho'] }}
                                </div>
                                <div class="col">
                                    TVOC: {{ $data->first()['tvoc'] }}
                                </div>
                            </div>
                            <p class="text-secondary">
                                <caption>Most Recent Measurements</caption>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="mb-5">
                <div class="card shadow">
                <h3 class="card-header text-center">Measurements</h3>
                    <div class="card-body">
                    <div class="table-responsive table-sm">
                            <table class="table">

                                <caption>Air Measurements</caption>

                                <thead>
                                    <tr>
                                        <th scope="col">Time</th>
                                        <th scope="col">Temp.</th>
                                        <th scope="col">PM10</th>
                                        <th scope="col">PM25</th>
                                        <th scope="col">CH20</th>
                                        <th scope="col">CO2</th>
                                        <th scope="col">Hum.</th>
                                        <th scope="col">TVOC</th>
                                        <th scope="col">HEX</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ date("F j, Y, g:i a",strtotime($item['date_created'])); }}</td>
                                            <td>{{ $item['temperature'] }}</td>
                                            <td>{{ $item['pm10'] }}</td>
                                            <td>{{ $item['pm25'] }}</td>
                                            <td>{{ $item['c2ho'] }}</td>
                                            <td>{{ $item['co2'] }}</td>
                                            <td>{{ $item['humidity'] }}</td>
                                            <td>{{ $item['tvoc'] }}</td>
                                            <td>{{ $item['HEX']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- I have added this button for testing purposes, it manually calls the DB refresh function, i use it as my hosting doesn't support custom cromjob
    <div class="d-grid mx-auto mb-5 col-md-3">
        <button class="btn btn-outline-primary" onclick="window.location='{{ route("data.refresh") }}'">Refresh Data</button>
    </div>
    -->

@endif
@endsection
