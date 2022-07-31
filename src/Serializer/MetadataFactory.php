<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory as Decorated;

class MetadataFactory extends Decorated
{
    public function getMetadataFor($value): ClassMetadataInterface
    {
        $metadata = parent::getMetadataFor($value);

        if ($metadata->getReflectionClass()->isInterface()) {
            return $metadata;
        }

        foreach ($metadata->getAttributesMetadata() as $attributeMetadata) {

            // hide internal fields
            if (in_array('internal', $attributeMetadata->getGroups())) {
                continue;
            }

            // make all rest fields available for admin
            $attributeMetadata->addGroup('ROLE_ADMIN:read');
            $attributeMetadata->addGroup('ROLE_ADMIN:write');
        }

        return $metadata;
    }
}
