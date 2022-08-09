<?php

namespace App\Service\Currency;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRateRepository;
use App\Repository\Currency\CurrencyRepository;
use DateTime;

class CurrencyRateUpdater
{
    public function __construct(
        private CurrencyRepository     $currencyRepository,
        private CurrencyRateRepository $repository,
        private CurrencyRateFetcher    $fetcher,
        private string                 $currencyRatesFrom
    )
    {
    }

    public function updateAll(): void
    {
        $currencies = $this->currencyRepository->findAll();

        foreach ($currencies as $currency) {
            $this->update($currency);
        }
    }

    public function update(Currency $currency): void
    {
        $lastRate = $this->repository->findLastByCurrency($currency);
        $nextDate = $lastRate ? $lastRate->getDate()->modify('+1 day') : new DateTime($this->currencyRatesFrom);
        $now      = new DateTime();

        while ($nextDate <= $now) {
            $this->repository->add($this->fetcher->fetch($currency, $nextDate), true);
            $nextDate->modify('+1 day');
        }
    }
}