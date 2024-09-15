<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    // Tabela powiązana z modelem
    protected $table = 'competitions';

    // Pola, które mogą być masowo przypisywane
    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'location',
        'type',
        'category',
    ];

    // Definicja relacji z modelem User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}