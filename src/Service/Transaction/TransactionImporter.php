<?php

namespace App\Service\Transaction;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\User\User;
use App\Repository\Account\AccountRepository;
use App\Repository\Transaction\TransactionRepository;
use App\Service\Transaction\Importer\TransactionImporterInterface;
use App\Service\Transaction\Importer\TransactionModel;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;
use Throwable;

class TransactionImporter
{
    /**
     * @param iterable|TransactionImporterInterface $importers
     * @param AccountRepository $accountRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        private iterable              $importers,
        private AccountRepository     $accountRepository,
        private TransactionRepository $transactionRepository
    )
    {
    }

    public function import(User $user, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $accounts = $this->accountRepository->findForImportTransactions($user);
        $data     = [];

        foreach ($accounts as $account) {

            $accountData = [
                'account' => [
                    'id' => $account->getId(),
                    'name' => $account->getName(),
                ]
            ];

            try {
                $transactions = $this->importByAccount($account, $startDate, $endDate);
            } catch (Throwable $e) {
                $transactions         = [];
                $accountData['error'] = $e->getMessage();
            }

            $accountData['transactions'] = $this->filterTransactions($user, $transactions);
            $data[]                      = $accountData;
        }

        return $data;
    }

    /**
     * @param Account $account
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return TransactionModel[]
     */
    private function importByAccount(Account $account, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        $transactions = [];

        foreach ($this->importers as $importer) {
            $method = $account->getImportMethod();
            if ($method && $importer->supports($method->getSlug())) {
                $transactions = $importer->import($account->getImportParams(), $startDate, $endDate);
                break;
            }
        }

        return $transactions;
    }

    /**
     * @param User $user
     * @param TransactionModel[] $transactions
     * @return TransactionModel[]
     */
    private function filterTransactions(User $user, array $transactions): array
    {
        $filtered = [];

        foreach ($transactions as $transaction) {

            $existingTransaction = $this->transactionRepository->findOneBy(['user' => $user, 'externalId' => $transaction->id]);

            if ($existingTransaction !== null) {
                continue;
            }

            $transaction->category = $this->tryToGetCategory($user, $transaction->comment);

            $filtered[] = $transaction;
        }

        return $filtered;
    }

    private function tryToGetCategory(User $user, string $comment): ?Category
    {
        $existingTransaction = $this->transactionRepository->findOneBy(
            ['user' => $user, 'comment' => $comment],
            ['date' => Criteria::DESC]
        );

        return $existingTransaction?->getCategory();
    }
}