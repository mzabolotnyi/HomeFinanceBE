<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\Hydra\Serializer\ErrorNormalizer;
use App\Entity\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
            Events::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            Events::JWT_EXPIRED => 'onAuthenticationFailure',
            Events::JWT_INVALID => 'onAuthenticationFailure',
            Events::JWT_NOT_FOUND => 'onAuthenticationFailure',
            'gesdinet.refresh_token_not_found' => 'onAuthenticationFailure',
            'gesdinet.refresh_token_failure' => 'onAuthenticationFailure',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $user = $event->getUser();

        if ($user instanceof User && !$user->isEnabled()) {
            throw new PreconditionRequiredHttpException('Need confirm email');
        }
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $data     = $this->normalizer->normalize($event->getException(), ErrorNormalizer::FORMAT);
        $response = new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }
}
