<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainingSession extends Model
{
    use HasFactory;

    // Nazwa tabeli w bazie danych
    protected $table = 'training_sessions';

    // Pola, które mogą być masowo przypisywane
    protected $fillable = [
        'user_id',
        'system_id',
        'note_id',
        'type',
        'start_datetime',
        'end_datetime',
        'duration_hours',
        'intensity',
        'style',
        'dances_planned',
        'dances_performed',
        'five_dances',
        'with_partner',
        'started',
        'start_confirmed_at',
        'completed',
        'end_confirmed_at',
        'created_at',
        'updated_at'
    ];

    // Pola, które powinny być traktowane jako daty
    protected $dates = [
        'start_datetime',
        'end_datetime',
        'start_confirmed_at',
        'end_confirmed_at',
        'created_at',
        'updated_at'
    ];



    // Relacja z modelem User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacja z modelem TrainingSystem
    public function trainingSystem()
    {
        return $this->belongsTo(TrainingSystem::class, 'system_id');
    }
    // Relacja z modelem TrainingNote
    public function trainingNote()
    {
        return $this->hasOne(TrainingNote::class, 'id', 'note_id');
    }
}