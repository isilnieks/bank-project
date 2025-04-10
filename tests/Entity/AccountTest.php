<?php

namespace App\Tests\Entity;

use App\Entity\Account;
use App\Entity\Client;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\Collection;

final class AccountTest extends TestCase
{
    private Account $account;
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->account = new Account();
    }

    public function testGetIdReturnsNullByDefault(): void
    {
        $this->assertNull($this->account->getId());
    }

    public function testTimestampableEntityMethods(): void
    {
        $this->assertTrue(method_exists($this->account, 'getCreatedAt'));
        $this->assertTrue(method_exists($this->account, 'getUpdatedAt'));
    }

    public function testGetBalanceReturnsZeroByDefault(): void
    {
        $this->assertEquals(0.0, $this->account->getBalance());
    }

    public function testSetBalanceReturnsSelf(): void
    {
        $returnValue = $this->account->setBalance(100);
        $this->assertSame($this->account, $returnValue);
    }

    public function testAddToBalanceReturnsBalanceCorrectly(): void
    {
        $this->account->setBalance(1);
        $this->account->addToBalance(12.345);
        $this->assertEquals(13.345, $this->account->getBalance());
    }

    public function testAddToBalanceReturnsSelf(): void
    {
        $returnValue = $this->account->addToBalance(1);
        $this->assertSame($this->account, $returnValue);
    }

    public function testSubtractFromBalanceReturnsBalanceCorrectly(): void
    {
        $this->account->setBalance(12.345);

        $this->account->subtractFromBalance(1);

        $this->assertEquals(11.345, $this->account->getBalance(), '');
    }

    public function testSubtractFromBalanceReturnsSelf(): void
    {
        $returnValue = $this->account->subtractFromBalance(10.0);
        $this->assertSame($this->account, $returnValue);
    }

    public function testHasSufficientBalanceReturnsTrueWhenBalanceIsSufficient(): void
    {
        $this->account->setBalance(100.0);
        $this->assertTrue($this->account->hasSufficientBalance(100.0));
        $this->assertTrue($this->account->hasSufficientBalance(50.0));
    }

    public function testHasSufficientBalanceReturnsFalseWhenBalanceIsInsufficient(): void
    {
        $this->account->setBalance(100.0);
        $this->assertFalse($this->account->hasSufficientBalance(100.01));
        $this->assertFalse($this->account->hasSufficientBalance(200.0));
    }

    public function testGetFormattedBalanceReturnsCorrectFormat(): void
    {
        $this->account->setBalance(1234.5678);
        $this->assertSame('1234.57', $this->account->getFormattedBalance());
    }

    public function testGetCurrencyReturnsSetCurrency(): void
    {
        $this->account->setCurrency('USD');
        $this->assertSame('USD', $this->account->getCurrency());
    }

    public function testSetCurrencyReturnsSelf(): void
    {
        $returnValue = $this->account->setCurrency('EUR');
        $this->assertSame($this->account, $returnValue);
    }

    public function testGetTransactionsReturnsEmptyCollectionByDefault(): void
    {
        $transactions = $this->account->getTransactions();
        $this->assertInstanceOf(Collection::class, $transactions);
        $this->assertCount(0, $transactions);
    }

    public function testGetClientReturnsSetClient(): void
    {
        $this->account->setClient($this->client);
        $this->assertSame($this->client, $this->account->getClient());
    }

    public function testSetClientReturnsSelf(): void
    {
        $returnValue = $this->account->setClient($this->client);
        $this->assertSame($this->account, $returnValue);
    }
}
