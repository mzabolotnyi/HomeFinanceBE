<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/registration")
 * @OA\Tag(name="User / Registration")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        return $this->json(1);
    }
}