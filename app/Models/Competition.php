<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $table = 'competitions';

    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'location',
        'type',
        'category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}