<?php

namespace App\Tests\Service;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Transaction;
use App\Service\TransactionService;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class TransactionServiceTest extends TestCase
{
    public function testCreateTransactionRecord()
    {
        $client = new Client();

        $fromAccount = new Account();
        $fromAccount->setClient($client);
        $fromAccount->setCurrency('USD');

        $toAccount = new Account();
        $toAccount->setClient($client);
        $toAccount->setCurrency('EUR');

        $reflectionClass = new ReflectionClass(TransactionService::class);
        $method = $reflectionClass->getMethod('createTransactionRecord');
        $method->setAccessible(true);

        $transactionService = $reflectionClass->newInstanceWithoutConstructor();

        $transaction = $method->invoke(
            $transactionService,
            $fromAccount,
            $toAccount,
            'USD',
            'EUR',
            95.994,
            100.123,
            1.0001
        );

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame($fromAccount, $transaction->getFromAccount());
        $this->assertSame($toAccount, $transaction->getToAccount());
        $this->assertEquals('USD', $transaction->getFromCurrency());
        $this->assertEquals('EUR', $transaction->getToCurrency());
        $this->assertEquals(95.99, $transaction->getToAmount());
        $this->assertEquals(100.12, $transaction->getFromAmount());
        $this->assertEquals(1.000100, $transaction->getExchangeRate());

        $transaction2 = $method->invoke(
            $transactionService,
            $fromAccount,
            $toAccount,
            'USD',
            'USD',
            100.00,
            100.00,
            null
        );

        $this->assertNull($transaction2->getExchangeRate());
    }
}
