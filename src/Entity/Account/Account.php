<?php

namespace App\Entity\Account;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Currency\Currency;
use App\Entity\Mixin\HasActive;
use App\Entity\Mixin\HasName;
use App\Entity\Mixin\HasUser;
use App\Entity\Mixin\UserOwnerInterface;
use App\Repository\Account\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
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
class Account implements UserOwnerInterface
{
    use HasUser;
    use HasName;
    use HasActive;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 7, nullable: true)]
    #[Groups(['read', 'write'])]
    #[Assert\Length(max: 7)]
    private ?string $color = null;

    #[ORM\ManyToMany(targetEntity: Currency::class)]
    #[ORM\JoinTable(name: 'account_currency')]
    #[Groups(['read', 'write'])]
    #[Assert\Count(min: 1)]
    private Collection $currencies;

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, Currency>
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(Currency $currency): self
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies->add($currency);
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): self
    {
        $this->currencies->removeElement($currency);

        return $this;
    }
}
