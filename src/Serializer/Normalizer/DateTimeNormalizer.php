<?php

namespace App\Serializer\Normalizer;

use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateTimeNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof DateTimeInterface;
    }

    /**
     * @param DateTimeInterface $object
     * @param string|null $format
     * @param array $context
     * @return string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return $object->format('Y-m-d H:i:s');
    }
}
