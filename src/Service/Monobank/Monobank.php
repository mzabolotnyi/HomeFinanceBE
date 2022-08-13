<?php

namespace App\Service\Monobank;

use DateTimeInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Monobank
{
    private ?Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.monobank.ua']);
    }

    public function getStatement(string $token, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $uri      = "/personal/statement/0/{$startDate->getTimestamp()}/{$endDate->getTimestamp()}";
        $response = $this->client->request('GET', $uri, ['headers' => ['X-Token' => $token]]);

        return $this->parseResponse($response);
    }

    private function parseResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}