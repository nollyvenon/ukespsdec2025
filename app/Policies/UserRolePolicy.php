<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'university_manager', 'recruiter', 'event_hoster']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user is an admin.
     */
    public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user is a recruiter.
     */
    public function isRecruiter(User $user): bool
    {
        return $user->role === 'recruiter';
    }

    /**
     * Determine if the user is a university manager.
     */
    public function isUniversityManager(User $user): bool
    {
        return $user->role === 'university_manager';
    }

    /**
     * Determine if the user is an event hoster.
     */
    public function isEventHoster(User $user): bool
    {
        return $user->role === 'event_hoster';
    }

    /**
     * Determine if the user is a job seeker.
     */
    public function isJobSeeker(User $user): bool
    {
        return $user->role === 'job_seeker';
    }

    /**
     * Determine if the user is a student.
     */
    public function isStudent(User $user): bool
    {
        return $user->role === 'student';
    }
}
