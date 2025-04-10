<?php

namespace App\Controller;

use Exception;
use App\DTO\TransferRequestDTO;
use App\Traits\ApiResponseTrait;
use App\Service\TransactionService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/transactions')]
final class TransactionController extends AbstractController
{
    use ApiResponseTrait;

    public function __construct(
        private TransactionService $transactionService,
    ) {}

    #[Route('/transfer', methods: ['POST'])]
    public function transfer(#[MapRequestPayload] TransferRequestDTO $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->transfer(
                $request->fromAccountId,
                $request->toAccountId,
                $request->amount,
                $request->currency
            );

            return $this->successResponse($transaction, 201, ['transaction:read']);
        } catch (Exception $e) {
            return $this->handleApiException($e);
        }
    }
}
