<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingNote extends Model
{
    use HasFactory;

    protected $table = 'training_notes';

    protected $fillable = [
        'session_id',
        'user_id', 
        'content',
    ];
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'note_id');
    }
}
