<?php

namespace App\Api\Request\ParamConverter;

use App\Api\Dto\Input\InputInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final class RequestInputConverter implements ParamConverterInterface
{
    public function __construct(
        private SerializerInterface $serializer,
    )
    {
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        try {
            $input = $this->serializer->deserialize($request->getContent(), $class, 'json');
        } catch (Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $request->attributes->set($configuration->getName(), $input);
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass()
            && is_string($configuration->getClass())
            && in_array(InputInterface::class, class_implements($configuration->getClass()));
    }
}