<?php

namespace App\Policies;

use App\Models\ChildGrowthRecord;
use App\Models\User;

class ChildGrowthRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-growth');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ChildGrowthRecord $record): bool
    {
        if (!$user->hasPermissionTo('view-growth')) {
            return false;
        }

        // Admin & Koordinator can view all
        if ($user->hasPermissionTo('view-all-patients')) {
            return true;
        }

        // Bidan Desa: only own patients' children
        if ($user->hasPermissionTo('view-own-patients')) {
            return $record->child?->patient?->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-growth');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ChildGrowthRecord $record): bool
    {
        if ($user->hasPermissionTo('create-growth') && $user->hasPermissionTo('view-all-patients')) {
            return true;
        }

        // Bidan Desa: only own
        if ($user->hasPermissionTo('create-growth') && $user->hasPermissionTo('view-own-patients')) {
            return $record->child?->patient?->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ChildGrowthRecord $record): bool
    {
        return $user->hasRole('Admin');
    }
}
