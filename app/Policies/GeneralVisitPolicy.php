<?php

namespace App\Policies;

use App\Models\GeneralVisit;
use App\Models\User;

class GeneralVisitPolicy
{
    /**
     * Determine whether the user can view any models.
     * Admin & Koordinator: View all
     * Bidan Desa: View own patients' visits
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-general-visits');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeneralVisit $generalVisit): bool
    {
        if (!$user->hasPermissionTo('view-general-visits')) {
            return false;
        }

        // Admin & Koordinator (view-all-patients) can view all
        if ($user->hasPermissionTo('view-all-patients')) {
            return true;
        }

        // Bidan Desa: only own patients' visits
        if ($user->hasPermissionTo('view-own-patients')) {
            // Check via patient or child
            if ($generalVisit->patient_id) {
                return $generalVisit->patient?->created_by === $user->id;
            }
            if ($generalVisit->child_id) {
                return $generalVisit->child?->patient?->created_by === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-general-visits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeneralVisit $generalVisit): bool
    {
        if ($user->hasPermissionTo('edit-general-visits')) {
            return true;
        }

        // Bidan Desa can edit own patients' visits
        if ($user->hasPermissionTo('create-general-visits') && $user->hasPermissionTo('view-own-patients')) {
            if ($generalVisit->patient_id) {
                return $generalVisit->patient?->created_by === $user->id;
            }
            if ($generalVisit->child_id) {
                return $generalVisit->child?->patient?->created_by === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete.
     */
    public function delete(User $user, GeneralVisit $generalVisit): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeneralVisit $generalVisit): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeneralVisit $generalVisit): bool
    {
        return $user->hasRole('Admin');
    }
}
