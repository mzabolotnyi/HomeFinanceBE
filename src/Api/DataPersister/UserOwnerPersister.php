<?php

namespace App\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Mixin\UserOwnerInterface;
use Symfony\Component\Security\Core\Security;

final class UserOwnerPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private Security                           $security
    )
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof UserOwnerInterface;
    }

    /**
     * @param UserOwnerInterface $data
     */
    public function persist($data, array $context = [])
    {
        if (($context['collection_operation_name'] ?? null) === 'post') {
            $data->setUser($this->security->getUser());
        }

        $this->decorated->persist($data);
    }

    /**
     * @param UserOwnerInterface $data
     */
    public function remove($data, array $context = [])
    {
        $this->decorated->remove($data);
    }
}