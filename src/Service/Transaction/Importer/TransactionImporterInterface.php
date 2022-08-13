<?php

namespace App\Service\Transaction\Importer;

use App\Enum\Account\ImportMethod;
use DateTimeInterface;

interface TransactionImporterInterface
{
    public function supports(ImportMethod $method): bool;

    /**
     * @param array $options
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return TransactionModel[]
     */
    public function import(array $options, DateTimeInterface $startDate, DateTimeInterface $endDate): array;
}