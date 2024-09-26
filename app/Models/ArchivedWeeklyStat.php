<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedWeeklyStat extends Model
{
    use HasFactory;

    protected $table = 'archived_weekly_stats';

    protected $fillable = [
         'user_id',
         'week_start_date',
         'total_training_minutes',
         'completed_sessions_percentage',
         'finals_danced',
         'days_trained',
    ];
    public $timestamps = false;
 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}