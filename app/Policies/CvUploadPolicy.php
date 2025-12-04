<?php

namespace App\Policies;

use App\Models\CvUpload;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CvUploadPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canUploadCv();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CvUpload $cvUpload): bool
    {
        // User can view their own CV
        if ($user->id === $cvUpload->user_id) {
            return true;
        }

        // Or if the CV is public and they have search permissions
        return $cvUpload->is_public && $user->canSearchCvs();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CvUpload $cvUpload): bool
    {
        return $user->id === $cvUpload->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CvUpload $cvUpload): bool
    {
        return $user->id === $cvUpload->user_id;
    }

    /**
     * Determine whether the user can download the CV file.
     */
    public function download(User $user, CvUpload $cvUpload): bool
    {
        return $user->canDownloadCv($cvUpload);
    }

    /**
     * Determine whether the user can search CVs.
     */
    public function searchCvs(User $user): bool
    {
        return $user->canSearchCvs();
    }

    /**
     * Determine whether the user can upload CVs.
     */
    public function uploadCv(User $user): bool
    {
        return $user->canUploadCv();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CvUpload $cvUpload): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CvUpload $cvUpload): bool
    {
        return false;
    }
}
