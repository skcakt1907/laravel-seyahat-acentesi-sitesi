<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'type',
        'package',
        'activity_name',
        'hotel_name',
        'adult_count',
        'adult_names',
        'child_count',
        'child_names',
        'notes',
        'arrival_date',
        'arrival_time',
        'arrival_flight',
        'departure_date',
        'departure_time',
        'departure_flight',
    ];
}

