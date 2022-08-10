<?php

namespace App\Service\Transaction\Importer;

use App\Enum\Currency\CurrencyCode;
use App\Enum\Transaction\TransactionType;
use DateTimeImmutable;

class TransactionModel
{
    public string            $id;
    public DateTimeImmutable $date;
    public TransactionType   $type;
    public CurrencyCode      $currency;
    public float             $amount;
    public string            $comment;
}