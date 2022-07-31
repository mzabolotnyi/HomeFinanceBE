<?php

namespace App\Controller\User;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\DataPersister\UserDataPersister;
use App\Entity\User\User;
use App\Model\Dto\Input\User\RegisterInput;
use App\Service\Mailer\Mailer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'User / Registration')]
#[Route(path: '/registration')]
class RegistrationController extends AbstractController
{
    #[Route('', methods: ['POST'], defaults: ['_api_respond' => true])]
    #[OA\Post(
        summary: 'Register user',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: RegisterInput::class))),
        responses: [
            new OA\Response(response: 204, description: 'Confirmation email has been sent via email'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(
        ValidatorInterface $validator,
        UserDataPersister  $persister,
        Mailer             $mailer,
        RegisterInput      $input
    ): JsonResponse
    {
        $user = new User();
        $user->setEmail($input->email);
        $user->setName($input->name);
        $user->setPlainPassword($input->password);

        if (count($violations = $validator->validate($user))) {
            throw new ValidationException($violations);
        }

        $user->generateToken();
        $persister->persist($user);

        $mailer->sendRegistrationEmail($user);

        return $this->json(null, 204);
    }

    #[Route('/confirm/{token}', methods: ['POST'], defaults: ['_api_respond' => true])]
    #[OA\Post(
        summary: 'Confirm registration',
        parameters: [
            new OA\Parameter(name: 'token', in: 'path', description: 'Confirmation token')
        ],
        responses: [
            new OA\Response(response: 204, description: 'Registration confirmed'),
            new OA\Response(response: 404, description: 'Invalid token'),
        ]
    )]
    public function confirm(UserDataPersister $persister, User $user): JsonResponse
    {
        $user->clearToken();
        $user->setEnabled(true);
        $persister->persist($user);

        return $this->json(null, 204);
    }
}