<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Auth\Access\Response;

class BlogPostPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin users can create blog posts
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BlogPost $blogPost): bool
    {
        return $user->is_admin || $user->id === $blogPost->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BlogPost $blogPost): bool
    {
        return $user->is_admin || $user->id === $blogPost->user_id;
    }
}