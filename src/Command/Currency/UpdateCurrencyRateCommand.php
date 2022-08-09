<?php

namespace App\Command\Currency;

use App\Service\Currency\CurrencyRateUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:currency:rate:update',
    description: 'Update currency rates',
)]
class UpdateCurrencyRateCommand extends Command
{
    public function __construct(private CurrencyRateUpdater $updater)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->updater->updateAll();

        return self::SUCCESS;
    }

}