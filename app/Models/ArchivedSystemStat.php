<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedSystemStat extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'archived_system_stats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'training_system_id',
        'start_date',
        'end_date',
        'completed_sessions',
        'total_sessions',
        'completed_sessions_percentage',
        'total_training_hours',
        'style_la_percentage',
        'style_st_percentage',
        'style_smooth_percentage',
        'training_type_stamina_percentage',
        'training_type_self_percentage',
        'training_type_individual_percentage',
    ];
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'completed_sessions_percentage' => 'decimal:2',
        'total_training_hours' => 'decimal:2',
        'style_la_percentage' => 'decimal:2',
        'style_st_percentage' => 'decimal:2',
        'style_smooth_percentage' => 'decimal:2',
        'training_type_stamina_percentage' => 'decimal:2',
        'training_type_self_percentage' => 'decimal:2',
        'training_type_individual_percentage' => 'decimal:2',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function trainingSystem()
    {
        return $this->belongsTo(TrainingSystem::class);
    }
}