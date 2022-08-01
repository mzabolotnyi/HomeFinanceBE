<?php

namespace App\Entity\Mixin;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

trait HasUser
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}