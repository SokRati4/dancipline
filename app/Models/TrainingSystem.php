<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSystem extends Model
{
    use HasFactory;

    /**
 * Get the archived system stats for the training system.
 */
     // Nazwa tabeli w bazie danych
     protected $table = 'training_systems';

     // Pola, które mogą być masowo przypisywane
     protected $fillable = [
         'user_id',
         'name',
         'description',
         'start_date',
         'end_date',
         'system_type',
         'completion_percentage',
         'dance_style',
         'created_at',
         'updated_at'
     ];
 
     // Pola, które powinny być traktowane jako daty
     protected $dates = [
         'start_date',
         'end_date',
         'created_at',
         'updated_at'
     ];
 
    public function archivedSystemStats()
    {
        return $this->hasMany(ArchivedSystemStat::class);
    }
    public function currentSystemStats()
    {
        return $this->hasMany(CurrentSystemStat::class, 'training_system_id');
    }
    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class, 'system_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}