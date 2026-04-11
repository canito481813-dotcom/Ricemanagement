<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'year',
        'license_plate',
        'daily_rate',
        'status',
        'notes',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
