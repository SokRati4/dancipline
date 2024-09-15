<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WeeklyStat extends Model
{
    use HasFactory;
    protected $table = 'weekly_stats';

    protected $fillable = [
        'user_id',
        'week_start_date',
        'total_training_minutes',
        'completed_sessions_percentage',
        'finals_danced',
        'days_trained'
    ];
    public $timestamps = false;

    protected $dates = [
        'week_start_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}





