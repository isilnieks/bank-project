<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api/clients')]
final class ClientController extends AbstractController
{

    #[Route('/{id}/accounts', methods: ['GET'])]
    public function getClientAccounts(Client $client): JsonResponse
    {
        if (!$client) {
            throw new NotFoundHttpException(sprintf('Client not found'));
        }

        return $this->json($client->getAccounts()->toArray(), 200, [], ['groups' => ['account:read']]);
    }
}
