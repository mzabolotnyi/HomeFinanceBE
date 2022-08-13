<?php

namespace App\Service\Transaction\Importer;

use App\Enum\Currency\CurrencyCode;
use App\Enum\Transaction\TransactionType;
use App\Service\Privatbank\Privatbank;
use DateTimeImmutable;
use DateTimeInterface;

class PrivatbankImporter implements TransactionImporterInterface
{
    public function __construct(private Privatbank $privatbank)
    {
    }

    public function import(array $options, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $merchantId       = $options['merchantId'];
        $merchantPassword = $options['merchantPassword'];
        $cardNumber       = $options['cardNumber'];
        $items            = $this->privatbank->getStatement($merchantId, $merchantPassword, $cardNumber, $startDate, $endDate);
        $transactions     = [];

        foreach ($items as $item) {
            $transactions[] = $this->parseItem($item['@attributes']);
        }

        return $transactions;
    }

    private function parseItem(array $item): TransactionModel
    {
        $transaction          = new TransactionModel();
        $transaction->id      = $item['appcode'];
        $transaction->date    = new DateTimeImmutable("{$item['trandate']} {$item['trantime']}");
        $transaction->comment = $item['description'];

        $amountData            = $this->parseAmountData($item);
        $transaction->type     = $amountData['type'];
        $transaction->currency = CurrencyCode::tryFrom($amountData['currency']);
        $transaction->amount   = $amountData['amount'];

        return $transaction;
    }

    private function parseAmountData(array $item): array
    {
        $parts    = explode(' ', $item['cardamount']);
        $amount   = round($parts[0], 2);
        $currency = $parts[1];

        return [
            'amount' => $amount,
            'type' => $amount > 0 ? TransactionType::Income : TransactionType::Expense,
            'currency' => $currency
        ];
    }
}