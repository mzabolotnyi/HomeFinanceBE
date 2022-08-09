<?php

namespace App\Entity\Account;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Entity\Mixin\HasName;
use App\Enum\Account\ImportMethod as ImportMethodEnum;
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
#[ApiFilter(PropertyFilter::class)]
class ImportMethod
{
    use HasName;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private array $fields = [];

    #[ORM\Column(length: 255, enumType: ImportMethodEnum::class)]
    private ?ImportMethodEnum $slug = null;

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

    public function getSlug(): ?ImportMethodEnum
    {
        return $this->slug;
    }

    public function setSlug(ImportMethodEnum $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
