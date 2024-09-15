<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    protected $table = 'tutorials';

    // Pola, które mogą być masowo przypisywane
    protected $fillable = [
        'title',
        'description',
        'url',
        'duration',
        'level',
        'created_at',
        'updated_at'
    ];

    // Pola, które powinny być traktowane jako daty
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}