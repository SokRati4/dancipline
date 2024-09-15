<?php
namespace App\Policies;

use App\Models\TrainingSystem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingSystemPolicy
{
    use HandlesAuthorization;

    public function view(User $user, TrainingSystem $trainingSystem)
    {
        return $user->id === $trainingSystem->user_id;
    }

    public function update(User $user, TrainingSystem $trainingSystem)
    {
        return $user->id === $trainingSystem->user_id;
    }
    public function store(User $user, TrainingSystem $trainingSystem)
    {
        return $user->id === $trainingSystem->user_id;
    }

    public function delete(User $user, TrainingSystem $trainingSystem)
    {
        return $user->id === $trainingSystem->user_id;
    }
}
