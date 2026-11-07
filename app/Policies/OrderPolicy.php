<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_platform_admin || (bool) $user->business_id;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->is_platform_admin || (int) $user->business_id === (int) $order->business_id;
    }

    public function create(User $user): bool
    {
        return (bool) $user->business_id || $user->is_platform_admin;
    }

    public function update(User $user, Order $order): bool
    {
        return $this->view($user, $order);
    }

    public function delete(User $user, Order $order): bool
    {
        return $this->view($user, $order);
    }
}
