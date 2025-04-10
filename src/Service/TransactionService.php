<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;
use InvalidArgumentException;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TransactionService
{
    public function __construct(
        private AccountRepository $accountRepository,
        private ExchangeRateService $exchangeRateService,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     */
    public function transfer(int $fromAccountId, int $toAccountId, float $amount, string $currency): Transaction
    {
        $fromAccount = $this->accountRepository->find($fromAccountId);
        $toAccount = $this->accountRepository->find($toAccountId);

        if (!$fromAccount) {
            throw new NotFoundHttpException('Source account not found');
        }

        if (!$toAccount) {
            throw new NotFoundHttpException('Destination account not found');
        }

        $currency = strtoupper($currency);

        if ($currency !== $toAccount->getCurrency()) {
            throw new InvalidArgumentException(
                sprintf(
                    "Currency of transfer (%s) must match the receiver's account currency (%s)",
                    $currency,
                    $toAccount->getCurrency()
                )
            );
        }

        $toCurrency = $fromAccount->getCurrency();
        $exchangeRate = null;
        $debitAmount = $amount;

        if ($toCurrency !== $currency) {
            $exchangeRate = $this->exchangeRateService->getExchangeRate($currency, $toCurrency);
            $debitAmount = $amount * $exchangeRate;
        }

        if (!$fromAccount->hasSufficientBalance($debitAmount)) {
            throw new InvalidArgumentException('Insufficient funds for this transfer');
        }

        $fromAccount->subtractFromBalance($debitAmount);
        $toAccount->addToBalance($amount);

        $transaction = $this->createTransactionRecord(
            $fromAccount,
            $toAccount,
            $toCurrency,
            $currency,
            $amount,
            $debitAmount,
            $exchangeRate
        );

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }

    private function createTransactionRecord(
        Account $fromAccount,
        Account $toAccount,
        string $fromCurrency,
        string $toCurrency,
        float $toAmount,
        float $fromAmount,
        ?float $exchangeRate = null
    ): Transaction {
        $transaction = new Transaction();
        $transaction->setFromAccount($fromAccount);
        $transaction->setToAccount($toAccount);
        $transaction->setFromCurrency($fromCurrency);
        $transaction->setToCurrency($toCurrency);
        $transaction->setToAmount(round($toAmount, 2));
        $transaction->setFromAmount(round($fromAmount, 2));

        if ($exchangeRate !== null) {
            $transaction->setExchangeRate(round($exchangeRate, 6));
        }
        return $transaction;
    }
}
