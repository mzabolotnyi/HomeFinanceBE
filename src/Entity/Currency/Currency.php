<?php

namespace App\Entity\Currency;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\Currency\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    normalizationContext: ['groups' => ['read']],
)]
#[ApiFilter(PropertyFilter::class)]
class Currency
{
    const UAH = 'UAH';
    const USD = 'USD';
    const EUR = 'EUR';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 3, unique: true)]
    #[Groups('read')]
    private ?string $code = null;

    #[ORM\Column(length: 1)]
    #[Groups('read')]
    private ?string $symbol = null;

    #[ORM\OneToMany(mappedBy: 'currency', targetEntity: CurrencyRate::class)]
    #[Groups('internal')]
    private Collection $rates;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection<int, CurrencyRate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(CurrencyRate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setCurrency($this);
        }

        return $this;
    }

    public function removeRate(CurrencyRate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getCurrency() === $this) {
                $rate->setCurrency(null);
            }
        }

        return $this;
    }
}
