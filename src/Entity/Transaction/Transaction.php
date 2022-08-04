<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Currency\Currency;
use App\Entity\Mixin\HasUser;
use App\Entity\Mixin\UserOwnerInterface;
use App\Repository\Transaction\TransactionRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
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
class Transaction implements UserOwnerInterface
{
    use HasUser;

    const TYPE_INCOME        = 'income';
    const TYPE_EXPENSE       = 'expense';
    const TYPE_TRANSFER_FROM = 'transfer_from';
    const TYPE_TRANSFER_TO   = 'transfer_to';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?Currency $currency = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?Account $account = null;

    #[ORM\ManyToOne]
    #[Groups(['read', 'write'])]
    private ?Category $category = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['read', 'write'])]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    #[Assert\Length(max: 255)]
    private ?string $comment = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\Choice(choices: [self::TYPE_INCOME, self::TYPE_EXPENSE, self::TYPE_TRANSFER_FROM, self::TYPE_TRANSFER_TO])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    private ?string $externalId = null;

    #[ORM\OneToOne(inversedBy: 'transferFrom', targetEntity: self::class, cascade: ['persist', 'remove'])]
    #[Groups(['read', 'write'])]
    private ?self $transferTo = null;

    #[ORM\OneToOne(mappedBy: 'transferTo', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private ?self $transferFrom = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->type === self::TYPE_TRANSFER_FROM && !$this->transferTo) {
            $context->buildViolation('Transfer to can\'t be empty')
                ->atPath('transferTo')
                ->addViolation();
        }
        
        if ($this->type !== self::TYPE_TRANSFER_FROM && $this->transferTo) {
            $context->buildViolation('Transfer to must be empty')
                ->atPath('transferTo')
                ->addViolation();
        }
        
        if ($this->transferTo && $this->transferTo->getType() !== self::TYPE_TRANSFER_TO) {
            $context->buildViolation('Transfer to invalid type')
                ->atPath('transferTo')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getTransferTo(): ?self
    {
        return $this->transferTo;
    }

    public function setTransferTo(?self $transferTo): self
    {
        $this->transferTo = $transferTo;

        return $this;
    }

    public function getTransferFrom(): ?self
    {
        return $this->transferFrom;
    }

    public function setTransferFrom(?self $transferFrom): self
    {
        // unset the owning side of the relation if necessary
        if ($transferFrom === null && $this->transferFrom !== null) {
            $this->transferFrom->setTransferTo(null);
        }

        // set the owning side of the relation if necessary
        if ($transferFrom !== null && $transferFrom->getTransferTo() !== $this) {
            $transferFrom->setTransferTo($this);
        }

        $this->transferFrom = $transferFrom;

        return $this;
    }
}
