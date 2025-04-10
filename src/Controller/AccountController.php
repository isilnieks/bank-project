<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/api/accounts')]
final class AccountController extends AbstractController
{
    #[Route('/{id}/transactions', methods: ['GET'])]
    public function getAccountTransactions(
        Account $account,
        TransactionRepository $transactionRepository,
        Request $request
    ): JsonResponse {
        if (!$account) {
            throw new NotFoundHttpException('Account not found');
        }

        $offset = (int)$request->query->get('offset', 0);
        $limit = (int)$request->query->get('limit', 20);

        $transactions = $transactionRepository->findAllByAccount($account, $offset, $limit);

        return $this->json($transactions, 200, [], ['groups' => ['transaction:read']]);
    }
}
