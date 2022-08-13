<?php

namespace App\Service\Privatbank;

use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class Privatbank
{
    private ?Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.privatbank.ua']);
    }

    public function getStatement(
        string            $merchantId,
        string            $merchantPassword,
        string            $cardNumber,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate
    ): array
    {
        $data = '<oper>cmt</oper>'
            . '<wait>0</wait>'
            . '<test>0</test>'
            . '<payment id="">'
            . '<prop name="sd" value="' . $startDate->format('d.m.Y') . '" />'
            . '<prop name="ed" value="' . $endDate->format('d.m.Y') . '" />'
            . '<prop name="card" value="' . $cardNumber . '" />'
            . '</payment>';

        $signature   = sha1(md5($data . $merchantPassword));
        $requestBody = $this->prepareRequestBody($data, $signature, $merchantId);
        $response    = $this->client->request('POST', '/p24api/rest_fiz', [RequestOptions::BODY => $requestBody]);

        $data = $this->parseResponse($response);

        return $data['data']['info']['statements']['statement'] ?: [];
    }

    private function prepareRequestBody(string $data, string $signature, string $merchantId): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<response version="1.0">'
            . '<merchant>'
            . '<id>' . $merchantId . '</id>'
            . '<signature>' . $signature . '</signature>'
            . '</merchant>'
            . '<data>'
            . $data
            . '</data>'
            . '</response>';
    }

    private function parseResponse(ResponseInterface $response): array
    {
        $xml  = new SimpleXMLElement($response->getBody()->getContents());
        $data = json_decode(json_encode($xml), true);

        if (isset($data['data']['error'])) {
            throw new TransferException($data['data']['error']['@attributes']['message']);
        }

        return $data;
    }

}