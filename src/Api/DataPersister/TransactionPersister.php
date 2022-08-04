<?php

namespace App\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Transaction\Transaction;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Security;

final class TransactionPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private Security                           $security
    )
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Transaction;
    }

    /**
     * @param Transaction $data
     */
    public function persist($data, array $context = [])
    {
        if (($context['collection_operation_name'] ?? null) === 'post') {
            /** @var User $user */
            $user = $this->security->getUser();
            $data->setUser($user);
            if ($transferTo = $data->getTransferTo()) {
                $transferTo->setUser($user);
            }
        }

        $this->decorated->persist($data);
    }

    /**
     * @param Transaction $data
     */
    public function remove($data, array $context = [])
    {
        $this->decorated->remove($data);
    }
}