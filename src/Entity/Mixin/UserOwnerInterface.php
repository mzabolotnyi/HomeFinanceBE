<?php

namespace App\Entity\Mixin;

use App\Entity\User\User;

interface UserOwnerInterface
{
    public function getUser(): ?User;
    public function setUser(User $user);
}