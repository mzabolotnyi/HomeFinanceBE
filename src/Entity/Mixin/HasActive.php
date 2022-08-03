<?php

namespace App\Entity\Mixin;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasActive
{
    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?bool $active = null;

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}