<?php

namespace App\Service\Transaction\Importer;

use DateTimeInterface;

interface TransactionImporterInterface
{
    /**
     * @param array $options
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return TransactionModel[]
     */
    public function import(array $options, DateTimeInterface $startDate, DateTimeInterface $endDate): array;
}