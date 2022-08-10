<?php

namespace App\Service\Transaction\Importer;

use App\Enum\Currency\CurrencyCode;
use App\Enum\Transaction\TransactionType;
use DateTimeImmutable;
use DateTimeInterface;
use GuzzleHttp\Client;

class MonobankImporter implements TransactionImporterInterface
{
    private ?Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.monobank.ua']);
    }

    public function import(array $options, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $token         = $options['apiKey'] ?? null;
        $uri           = "/personal/statement/0/{$startDate->getTimestamp()}/{$endDate->getTimestamp()}";
        $response      = $this->client->request('GET', $uri, ['headers' => ['X-Token' => $token]]);
        $responseItems = json_decode($response->getBody()->getContents(), true);
        $transactions  = [];

        foreach ($responseItems as $item) {
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