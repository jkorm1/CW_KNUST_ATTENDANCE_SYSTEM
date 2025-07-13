<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberPolicy
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
    public function view(User $user, Member $member): bool
    {
        if ($user->isAdmin()) {
            return true;
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
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): bool
    {
        // For now, only admins can update members directly.
        // Leaders manage members through attendance.
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Member $member): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Member $member): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage the member (a general check).
     */
    public function manage(User $user, Member $member): bool
    {
        return true; // Allow all for testing
    }
}
