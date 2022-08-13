<?php

namespace App\Service\Transaction\Importer;

use App\Enum\Currency\CurrencyCode;
use App\Enum\Transaction\TransactionType;
use App\Service\Monobank\Monobank;
use DateTimeImmutable;
use DateTimeInterface;

class MonobankImporter implements TransactionImporterInterface
{
    public function __construct(private Monobank $monobank)
    {
    }

    public function import(array $options, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $token        = $options['token'];
        $items        = $this->monobank->getStatement($token, $startDate, $endDate);
        $transactions = [];

        foreach ($items as $item) {
            $transactions[] = $this->parseItem($item);
        }

        return $transactions;
    }

    private function parseItem(array $item): TransactionModel
    {
        $transaction           = new TransactionModel();
        $transaction->id       = $item['id'];
        $transaction->date     = (new DateTimeImmutable())->setTimestamp($item['time']);
        $transaction->type     = $this->parseType($item);
        $transaction->currency = CurrencyCode::UAH;
        $transaction->amount   = $this->parseAmount($item);
        $transaction->comment  = $item['description'];

        return $transaction;
    }

    private function parseType(array $item): TransactionType
    {
        return $item['amount'] > 0 ? TransactionType::Income : TransactionType::Expense;
    }

    private function parseAmount(array $item): float
    {
        return round(abs($item['amount'] / 100), 2);
    }
}