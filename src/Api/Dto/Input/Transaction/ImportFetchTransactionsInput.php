<?php

namespace App\Api\Dto\Input\Transaction;

use App\Api\Dto\Input\InputInterface;
use DateTimeImmutable;

class ImportFetchTransactionsInput implements InputInterface
{
    public DateTimeImmutable $startDate;
    public DateTimeImmutable $endDate;
}