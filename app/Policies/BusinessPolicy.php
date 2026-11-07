<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_platform_admin;
    }

    public function view(User $user, Business $business): bool
    {
        return $user->is_platform_admin || (int) $user->business_id === (int) $business->id;
    }

    public function update(User $user, Business $business): bool
    {
        return $this->view($user, $business);
    }
}
