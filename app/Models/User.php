<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin()
    {
    return $this->role === 'admin';
    }

    public function archivedSystemStats()
    {
    return $this->hasMany(ArchivedSystemStat::class);
    }
    public function archivedWeeklyStats()
    {
        return $this->hasMany(ArchivedWeeklyStat::class, 'user_id');
    }
    public function competitions()
    {
        return $this->hasMany(Competition::class, 'user_id');
    }
    public function currentSystemStats()
    {
        return $this->hasMany(CurrentSystemStat::class, 'user_id');
    }
    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class, 'user_id');
    }
    public function trainingSystems()
    {
        return $this->hasMany(TrainingSystem::class, 'user_id');
    }
    public function weeklyStats()
{
    return $this->hasMany(WeeklyStat::class, 'user_id');
}

}