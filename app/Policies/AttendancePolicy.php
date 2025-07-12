<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isCellLeader() || $user->isFoldLeader();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        $member = $attendance->member;
        if (!$member) {
            return false;
        }

        if ($user->isCellLeader()) {
            $ledCell = $user->getLedCell();
            return $ledCell && $member->cell_id === $ledCell->id;
        }

        if ($user->isFoldLeader()) {
            $ledFold = $user->getLedFold();
            return $ledFold && $member->fold_id === $ledFold->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCellLeader() || $user->isFoldLeader();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return $this->view($user, $attendance);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $this->view($user, $attendance);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendance $attendance): bool
    {
        return $this->view($user, $attendance);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendance $attendance): bool
    {
        return $this->view($user, $attendance);
    }
}
