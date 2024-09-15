<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingNote extends Model
{
    use HasFactory;

    protected $table = 'training_notes';

    // Pola, które mogą być masowo przypisywane
    protected $fillable = [
        'session_id',
        'user_id', // Dodane pole user_id
        'content',
    ];
    // Relacja z modelem TrainingSession
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    // Relacja z modelem User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Metoda do obsługi załączników (opcjonalnie)
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'note_id');
    }
}
