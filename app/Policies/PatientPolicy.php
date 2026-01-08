<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin & Koordinator: View all patients
     * Bidan Desa: View only own patients
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-all-patients') ||
            $user->hasPermissionTo('view-own-patients');
    }

    /**
     * Determine whether the user can view the model.
     * Admin & Koordinator: View all
     * Bidan Desa: Only own patients
     */
    public function view(User $user, Patient $patient): bool
    {
        // Admin & Koordinator can view all
        if ($user->hasPermissionTo('view-all-patients')) {
            return true;
        }

        // Bidan Desa can only view own patients
        if ($user->hasPermissionTo('view-own-patients')) {
            return $patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-patients');
    }

    /**
     * Determine whether the user can update the model.
     * Admin & Koordinator: Update all
     * Bidan Desa: Only own patients
     */
    public function update(User $user, Patient $patient): bool
    {
        // Admin & Koordinator can update all
        if ($user->hasPermissionTo('edit-patients')) {
            return true;
        }

        // Bidan Desa can only edit own patients
        if ($user->hasPermissionTo('view-own-patients')) {
            return $patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete
     */
    public function delete(User $user, Patient $patient): bool
    {
        return $user->hasPermissionTo('delete-patients');
    }

    /**
     * Determine whether the user can restore the model.
     * Only Admin can restore
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $user->hasPermissionTo('delete-patients'); // Same as delete
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only Admin can force delete
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return $user->hasPermissionTo('delete-patients');
    }

    /**
     * Determine whether the user can export patient data.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export-data');
    }
}
