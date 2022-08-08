<?php

namespace App\Api\Serializer;

trait AlreadyCalledTrait
{
    protected function addToCalled(array &$context): void
    {
        $context['called_normalizers'][] = get_class($this);
    }

    protected function alreadyCalled(array $context): bool
    {
        return in_array(get_class($this), ($context['called_normalizers'] ?? []));
    }
}
