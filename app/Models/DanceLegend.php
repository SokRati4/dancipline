<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanceLegend extends Model
{
    use HasFactory;

      protected $table = 'dance_legends';

  
      protected $fillable = [
          'partner1_name',
          'partner2_name',
          'best_results',
          'known_for',
          'bio',
          'videos',
      ];
  
      protected $dates = [
          'created_at',
          'updated_at',
      ];
  
      public $timestamps = true;
}