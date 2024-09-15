<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

        // Nazwa tabeli w bazie danych
        protected $table = 'quotes';

        // Pola, które mogą być masowo przypisywane
        protected $fillable = [
            'quote',
            'author',
            'created_at',
        ];
    
        // Pola, które powinny być traktowane jako daty
        protected $dates = [
            'created_at',
        ];
    
        // Brak znaczników czasu w tabeli
        public $timestamps = false;
}