<?php

namespace App\Entity\Category;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Entity\Mixin\HasActive;
use App\Entity\Mixin\HasName;
use App\Entity\Mixin\HasUser;
use App\Entity\Mixin\UserOwnerInterface;
use App\Repository\Category\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: [
        'get' => [
            'security' => "is_granted('ROLE_ADMIN') or object.getUser() === user"
        ],
        'put' => [
            'security' => "is_granted('ROLE_ADMIN') or object.getUser() === user"
        ],
        'delete' => [
            'security' => "is_granted('ROLE_ADMIN') or object.getUser() === user"
        ]
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiFilter(PropertyFilter::class)]
class Category implements UserOwnerInterface
{
    use HasUser;
    use HasName;
    use HasActive;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $icon = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?bool $income = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?bool $expense = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function isIncome(): ?bool
    {
        return $this->income;
    }

    public function setIncome(bool $income): self
    {
        $this->income = $income;

        return $this;
    }

    public function isExpense(): ?bool
    {
        return $this->expense;
    }

    public function setExpense(bool $expense): self
    {
        $this->expense = $expense;

        return $this;
    }
}
