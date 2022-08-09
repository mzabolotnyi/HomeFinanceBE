<?php

namespace App\Api\Serializer\Normalizer;

use App\Api\Serializer\AlreadyCalledTrait;
use App\Entity\Currency\Currency;
use App\Entity\User\User;
use App\Service\Currency\CurrencyRateCalculator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CurrencyNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use AlreadyCalledTrait;

    public function __construct(
        private CurrencyRateCalculator $currencyRateCalculator,
        private Security               $security
    )
    {
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return !$this->alreadyCalled($context) && $data instanceof Currency;
    }

    /**
     * @param Currency $object
     * @param string|null $format
     * @param array $context
     * @return string
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $this->addToCalled($context);
        $normalized = $this->normalizer->normalize($object, $format, $context);

        if (($context['collection_operation_name'] ?? null) === 'get' && ($context['resource_class'] ?? null) === Currency::class) {
            /** @var User $user */
            $user               = $this->security->getUser();
            $currencyDefault    = $user?->getDefaultCurrency();
            $normalized['rate'] = $this->currencyRateCalculator->getRate($object, $currencyDefault);
        }

        return $normalized;
    }
}
