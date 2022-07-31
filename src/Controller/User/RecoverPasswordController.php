<?php

namespace App\Controller\User;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\DataPersister\UserDataPersister;
use App\Entity\User\User;
use App\Model\Dto\Input\User\RecoverPasswordInput;
use App\Model\Dto\Input\User\RecoverPasswordRequestInput;
use App\Repository\User\UserRepository;
use App\Service\Mailer\Mailer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'User / Recover Password')]
#[Route(path: '/recover-password')]
class RecoverPasswordController extends AbstractController
{
    #[Route('/request', methods: ['POST'], defaults: ['_api_respond' => true])]
    #[OA\Post(
        summary: 'Create recover request',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: RecoverPasswordRequestInput::class))),
        responses: [
            new OA\Response(response: 204, description: 'Recover link has been sent via email'),
            new OA\Response(response: 404, description: 'User not found'),
        ]
    )]
    public function request(
        UserRepository              $repository,
        UserDataPersister           $persister,
        Mailer                      $mailer,
        RecoverPasswordRequestInput $input
    ): JsonResponse
    {
        if (!$user = $repository->findOneBy(['email' => $input->email, 'enabled' => true])) {
            throw new NotFoundHttpException('User not found');
        }

        $user->generateToken();
        $persister->persist($user);

        $mailer->sendRecoverPasswordEmail($user);

        return $this->json(null, 204);
    }

    #[Route('/{token}', methods: ['POST'], defaults: ['_api_respond' => true])]
    #[OA\Post(
        summary: 'Set new password',
        parameters: [
            new OA\Parameter(name: 'token', in: 'path', description: 'Password recovery token')
        ],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: RecoverPasswordInput::class))),
        responses: [
            new OA\Response(response: 204, description: 'New password have been set'),
            new OA\Response(response: 404, description: 'Invalid or expired token'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function recover(
        ValidatorInterface   $validator,
        UserDataPersister    $persister,
        RecoverPasswordInput $input,
        User                 $user
    ): JsonResponse
    {
        if ($user->tokenExpired(1800)) {
            throw new NotFoundHttpException('Token expired');
        }

        $user->setPlainPassword($input->password);

        if (count($violations = $validator->validate($user))) {
            throw new ValidationException($violations);
        }

        $user->clearToken();
        $persister->persist($user);

        return $this->json(null, 204);
    }
}