<?php

namespace App\Service\Currency;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRateRepository;
use DateTime;
use DateTimeInterface;

class RateCalculator
{
    /** Currency for which rates apply */
    const BASIC_CURRENCY = Currency::UAH;

    public function __construct(private CurrencyRateRepository $rateRepository)
    {
    }

    public function convert($amount, Currency $currencyFrom, Currency $currencyTo, ?DateTimeInterface $date = null): float
    {
        return round($amount * $this->getRate($currencyFrom, $currencyTo, $date), 2);
    }

    public function getRate(Currency $currencyFrom, ?Currency $currencyTo = null, ?DateTimeInterface $date = null): float
    {
        if ($date === null) {
            $date = new DateTime();
        }

        $rate = $this->getLastRate($currencyFrom, $date);

        if ($currencyTo !== null && $currencyTo->getCode() !== self::BASIC_CURRENCY) {
            $rateDefault = $this->getLastRate($currencyTo, $date);
            $rate        = round($rate / $rateDefault, 6);
        }

        return $rate;
    }

    private function getLastRate(Currency $currency, $date): float
    {
        $rateCurrency = $this->rateRepository->findLastByCurrency($currency, $date);

        if ($rateCurrency) {
            $rate = $rateCurrency->getSize() > 0 ? round($rateCurrency->getRate() / $rateCurrency->getSize(), 6) : $rateCurrency->getRate();
        } else {
            $rate = 1.0;
        }

        return $rate;
    }
}