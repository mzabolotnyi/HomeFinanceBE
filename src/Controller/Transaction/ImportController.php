<?php

namespace App\Controller\Transaction;

use App\Api\Dto\Input\Transaction\ImportFetchTransactionsInput;
use App\Service\Transaction\TransactionImporter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Tag(name: 'Transaction / Import')]
#[Route(path: '/transactions/import')]
class ImportController extends AbstractController
{
    #[Route('/fetch', methods: ['POST'])]
    #[OA\Post(
        summary: 'Fetch transactions for import',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: ImportFetchTransactionsInput::class))),
        responses: [
            new OA\Response(response: 200, description: '')
        ]
    )]
    public function request(TransactionImporter $importer, ImportFetchTransactionsInput $input): JsonResponse
    {
        $payload = $importer->import($this->getUser(), $input->startDate, $input->endDate);

        return $this->json($payload, 200, [], ['groups' => 'read']);
    }
}