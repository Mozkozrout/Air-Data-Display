<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data_id',
        'sort',
        'user_created',
        'user_updated',
        'date_created',
        'date_updated',
        'HEX',
        'temperature',
        'co2',
        'c2ho',
        'humidity',
        'check',
        'pm10',
        'pm25',
        'tvoc',
        'valid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];
}
