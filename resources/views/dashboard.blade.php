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
                                <button class="btn btn-outline-danger" href="{{ route('home') }}">Znovu</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else

    @foreach ($data as $item)
        <p>id: {{ $item['id'] }}</p>
        <p>sort: {{ $item['sort'] }}</p>
        <p>user_created: {{ $item['user_created'] }}</p>
        <p>date_created: {{ $item['date_created'] }}</p>
        <p>user_updated: {{ $item['user_updated'] }}</p>
        <p>date_updated: {{ $item['date_updated'] }}</p>
        <p>HEX: {{ $item['HEX']}}</p>
        <p>temperature: {{ $item['temperature'] }}</p>
        <p>co2: {{ $item['co2'] }}</p>
        <p>c2ho: {{ $item['c2ho'] }}</p>
        <p>humidity: {{ $item['humidity'] }}</p>
        <p>check: {{ $item['check'] }}</p>
        <p>pm10: {{ $item['pm10'] }}</p>
        <p>pm25: {{ $item['pm25'] }}</p>
        <p>tvoc: {{ $item['tvoc'] }}</p>
        <p>valid: {{ $item['valid'] }}</p>
        </br>
    @endforeach
@endif
@endsection
