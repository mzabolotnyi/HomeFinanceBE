<?php

namespace App\Service\Transaction\Importer;

use App\Entity\Category\Category;
use App\Enum\Currency\CurrencyCode;
use App\Enum\Transaction\TransactionType;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;

class TransactionModel
{
    #[Groups(['read'])]
    public string $id;

    #[Groups(['read'])]
    public DateTimeImmutable $date;

    #[Groups(['read'])]
    public TransactionType $type;

    #[Groups(['read'])]
    public CurrencyCode $currency;

    #[Groups(['read'])]
    public ?Category $category;

    #[Groups(['read'])]
    public float $amount;

    #[Groups(['read'])]
    public string $comment;
}