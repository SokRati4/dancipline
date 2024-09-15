<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentSystemStat extends Model
{
    use HasFactory;

     // Tabela powiązana z modelem
     protected $table = 'current_system_stats';

     // Pola, które mogą być masowo przypisywane
     protected $fillable = [
         'user_id',
         'training_system_id',
         'completed_sessions',
         'total_sessions',
         'completed_sessions_percentage',
         'total_training_hours',
         'completed_training_hours',
         'style_la_percentage',
         'style_st_percentage',
         'style_smooth_percentage',
         'training_type_stamina_percentage',
         'training_type_self_percentage',
         'training_type_individual_percentage',
         'average_intensity',
         'training_type_group_percentage',
     ];
     public $timestamps = false;
 
     // Definicja relacji z modelem User
     public function user()
     {
        return $this->belongsTo(User::class, 'user_id');
     }
 
     // Definicja relacji z modelem TrainingSystem
     public function trainingSystem()
     {
        return $this->belongsTo(TrainingSystem::class, 'training_system_id');
     }
}