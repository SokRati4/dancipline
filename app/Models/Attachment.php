<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Attachment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'note_id',
        'file_path',
        'session_id',
    ];
    public $timestamps = false;

    public function trainingNote()
    {
        return $this->belongsTo(TrainingNote::class, 'note_id');
    }

}
