<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanceLegend extends Model
{
    use HasFactory;

      // Nazwa tabeli w bazie danych
      protected $table = 'dance_legends';

  
      // Pola, które mogą być masowo przypisywane
      protected $fillable = [
          'partner1_name',
          'partner2_name',
          'best_results',
          'known_for',
          'bio',
          'videos',
      ];
  
      // Pola, które powinny być traktowane jako daty
      protected $dates = [
          'created_at',
          'updated_at',
      ];
  
      // Brak znaczników czasu w tabeli
      public $timestamps = true;
}