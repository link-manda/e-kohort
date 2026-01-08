<?php

namespace App\Policies;

use App\Models\AncVisit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AncVisitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-all-anc-visits') ||
            $user->hasPermissionTo('view-own-anc-visits');
    }

    /**
     * Determine whether the user can view the model.
     * Admin & Koordinator: View all
     * Bidan Desa: Only own ANC visits
     */
    public function view(User $user, AncVisit $ancVisit): bool
    {
        // Admin & Koordinator can view all
        if ($user->hasPermissionTo('view-all-anc-visits')) {
            return true;
        }

        // Bidan Desa can only view ANC visits of own patients
        if ($user->hasPermissionTo('view-own-anc-visits')) {
            return $ancVisit->pregnancy->patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-anc-visits');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AncVisit $ancVisit): bool
    {
        // Admin & Koordinator can update all
        if ($user->hasPermissionTo('edit-anc-visits')) {
            return true;
        }

        // Bidan Desa can only edit own ANC visits
        if ($user->hasPermissionTo('view-own-anc-visits')) {
            return $ancVisit->pregnancy->patient->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete
     */
    public function delete(User $user, AncVisit $ancVisit): bool
    {
        return $user->hasPermissionTo('delete-anc-visits');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AncVisit $ancVisit): bool
    {
        return $user->hasPermissionTo('delete-anc-visits');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AncVisit $ancVisit): bool
    {
        return $user->hasPermissionTo('delete-anc-visits');
    }
}
