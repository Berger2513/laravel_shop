<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function update(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }

    public function delete(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }
    
    public function own(User $user, UserAddress $address)
    {
        return $address->user_id == $user->id;
    }
}
