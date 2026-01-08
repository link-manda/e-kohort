<?php

namespace App\Policies;

use App\Models\Pregnancy;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PregnancyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-all-pregnancies') ||
            $user->hasPermissionTo('view-own-pregnancies');
    }

    /**
     * Determine whether the user can view the model.
     * Admin & Koordinator: View all
     * Bidan Desa: Only own pregnancies (via patient)
     */
    public function view(User $user, Pregnancy $pregnancy): bool
    {
        // Admin & Koordinator can view all
        if ($user->hasPermissionTo('view-all-pregnancies')) {
            return true;
        }

        // Bidan Desa can only view pregnancies of own patients
        if ($user->hasPermissionTo('view-own-pregnancies')) {
            return $pregnancy->patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-pregnancies');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pregnancy $pregnancy): bool
    {
        // Admin & Koordinator can update all
        if ($user->hasPermissionTo('edit-pregnancies')) {
            return true;
        }

        // Bidan Desa can only edit pregnancies of own patients
        if ($user->hasPermissionTo('view-own-pregnancies')) {
            return $pregnancy->patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete
     */
    public function delete(User $user, Pregnancy $pregnancy): bool
    {
        return $user->hasPermissionTo('delete-pregnancies');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pregnancy $pregnancy): bool
    {
        return $user->hasPermissionTo('delete-pregnancies');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pregnancy $pregnancy): bool
    {
        return $user->hasPermissionTo('delete-pregnancies');
    }
}
