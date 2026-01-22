<?php

namespace App\Policies;

use App\Models\KbVisit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KbVisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin & Koordinator can view all, Desa can view own
        return $user->hasPermissionTo('view-kb') || $user->hasPermissionTo('view-all-patients');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KbVisit $kbVisit): bool
    {
        // Admin & Koordinator can view all
        if ($user->hasPermissionTo('view-kb')) {
            return true;
        }

        // Bidan Desa can only view their own records
        if ($user->hasPermissionTo('view-own-patients')) {
            return $kbVisit->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-kb');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KbVisit $kbVisit): bool
    {
        // Admin & Koordinator can edit all
        if ($user->hasPermissionTo('edit-kb')) {
            return true;
        }

        // Bidan Desa can only edit their own records
        if ($user->hasPermissionTo('view-own-patients')) {
            return $kbVisit->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KbVisit $kbVisit): bool
    {
        // Only Admin can delete
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KbVisit $kbVisit): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KbVisit $kbVisit): bool
    {
        return $user->hasRole('Admin');
    }
}
