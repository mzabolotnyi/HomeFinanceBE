<?php

namespace App\Api\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class ContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(
        private SerializerContextBuilderInterface $decorated,
        private Security                          $security
    )
    {
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context           = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $context['groups'] = $this->getGroups($context, $normalization);

        return $context;
    }

    private function getGroups(array $context, bool $normalization): array
    {
        $groups   = $context['groups'] ?? [];
        $groups[] = $normalization ? 'read' : 'write';

        if ($user = $this->security->getUser()) {
            foreach ($user->getRoles() as $role) {
                $groups[] = $role;
                $groups[] = $normalization ? "$role:read" : "$role:write";
            }
        }

        return array_unique($groups);
    }
}
