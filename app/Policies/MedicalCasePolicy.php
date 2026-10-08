<?php

namespace App\Policies;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalCasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any cases.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Viewer']);
    }

    /**
     * Determine whether the user can view the case details.
     */
    public function view(User $user, MedicalCase $case): bool
    {
        return in_array($user->role, ['Admin', 'Viewer']);
    }

    /**
     * Determine whether the user can create cases.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the case.
     */
    public function update(User $user, MedicalCase $case): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the case (Admin only).
     */
    public function delete(User $user, MedicalCase $case): bool
    {
        return $user->isAdmin();
    }
}
