<?php

namespace App\Service\Transaction\Importer;

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
        return [];
    }
}