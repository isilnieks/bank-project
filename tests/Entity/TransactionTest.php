<?php

namespace App\Tests\Entity;

use App\Entity\Transaction;
use App\Entity\Account;
use App\Entity\Client;
use PHPUnit\Framework\TestCase;

final class TransactionTest extends TestCase
{
    private Transaction $transaction;
    private Account $fromAccount;
    private Account $toAccount;

    protected function setUp(): void
    {
        $client = new Client();

        $this->fromAccount = new Account();
        $this->fromAccount->setClient($client);
        $this->fromAccount->setCurrency('USD');

        $this->toAccount = new Account();
        $this->toAccount->setClient($client);
        $this->toAccount->setCurrency('EUR');

        $this->transaction = new Transaction();
    }

    public function testGetIdReturnsNullByDefault(): void
    {
        $this->assertNull($this->transaction->getId());
    }

    public function testTimestampableEntityMethods(): void
    {
        $this->assertTrue(method_exists($this->transaction, 'getCreatedAt'));
        $this->assertTrue(method_exists($this->transaction, 'getUpdatedAt'));
    }

    public function testGetFromAccountReturnsSetAccount(): void
    {
        $this->transaction->setFromAccount($this->fromAccount);
        $this->assertSame($this->fromAccount, $this->transaction->getFromAccount());
    }

    public function testSetFromAccountReturnsSelf(): void
    {
        $returnValue = $this->transaction->setFromAccount($this->fromAccount);
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testGetToAccountReturnsSetAccount(): void
    {
        $this->transaction->setToAccount($this->toAccount);
        $this->assertSame($this->toAccount, $this->transaction->getToAccount());
    }

    public function testSetToAccountReturnsSelf(): void
    {
        $returnValue = $this->transaction->setToAccount($this->toAccount);
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testGetFromCurrencyReturnsSetCurrency(): void
    {
        $this->transaction->setFromCurrency('USD');
        $this->assertSame('USD', $this->transaction->getFromCurrency());
    }

    public function testSetFromCurrencyReturnsSelf(): void
    {
        $returnValue = $this->transaction->setFromCurrency('USD');
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testGetExchangeRateReturnsSetRate(): void
    {
        $this->transaction->setExchangeRate(1.2);
        $this->assertSame(1.2, $this->transaction->getExchangeRate());
    }

    public function testSetExchangeRateReturnsSelf(): void
    {
        $returnValue = $this->transaction->setExchangeRate(1.2);
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testExchangeRateCanBeNull(): void
    {
        $this->transaction->setExchangeRate(null);
        $this->assertNull($this->transaction->getExchangeRate());
    }

    public function testGetFromAmountReturnsSetAmount(): void
    {
        $this->transaction->setFromAmount(123.45);
        $this->assertSame(123.45, $this->transaction->getFromAmount());
    }

    public function testSetFromAmountReturnsSelf(): void
    {
        $returnValue = $this->transaction->setFromAmount(100.0);
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testGetToAmountReturnsSetAmount(): void
    {
        $this->transaction->setToAmount(123.45);
        $this->assertSame(123.45, $this->transaction->getToAmount());
    }

    public function testSetToAmountReturnsSelf(): void
    {
        $returnValue = $this->transaction->setToAmount(123.45);
        $this->assertSame($this->transaction, $returnValue);
    }

    public function testGetToCurrencyReturnsSetCurrency(): void
    {
        $this->transaction->setToCurrency('EUR');
        $this->assertSame('EUR', $this->transaction->getToCurrency());
    }

    public function testSetToCurrencyReturnsSelf(): void
    {
        $returnValue = $this->transaction->setToCurrency('EUR');
        $this->assertSame($this->transaction, $returnValue);
    }
}
