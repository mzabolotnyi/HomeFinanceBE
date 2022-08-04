<?php

namespace App\Entity\Account;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Mixin\HasName;
use App\Repository\Account\ImportMethodRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImportMethodRepository::class)]
#[ORM\Table(name: 'account_import_method')]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
)]
class ImportMethod
{
    use HasName;

    const MONOBANK   = 'monobank';
    const PRIVATBANK = 'privatbank';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private array $fields = [];

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
