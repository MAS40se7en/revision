<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Interview $interview): bool
    {
        return $user->id === $interview->application->user_id;
    }
}
