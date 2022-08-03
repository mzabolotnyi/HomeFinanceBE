<?php

namespace App\Entity\Mixin;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait HasName
{
    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}