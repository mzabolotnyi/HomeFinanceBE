<?php

namespace App\Service\Currency;

use App\Entity\Currency\Currency;
use App\Entity\Currency\CurrencyRate;
use App\Enum\Currency\CurrencyCode;
use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

class CurrencyRateFetcher
{
    public function fetch(Currency $currency, DateTimeInterface $date): CurrencyRate
    {
        $code = $currency->getCodeValue();

        if ($code !== CurrencyCode::getBasicValue()) {

            $json = file_get_contents("https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode={$code}&date={$date->format('Ymd')}&json");
            $data = json_decode($json, true);

            if (!isset($data[0]['rate'])) {
                throw new RuntimeException("Can't fetch currency rate for $code on {$date->format('Y-m-d')}");
            }

            $rate = $data[0]['rate'];

        } else {
            $rate = 1;
        }


        return new CurrencyRate($currency, DateTimeImmutable::createFromInterface($date), $rate, 1);
    }
}