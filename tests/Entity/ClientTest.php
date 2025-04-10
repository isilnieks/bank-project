<?php

namespace App\Tests\Entity;

use App\Entity\Client;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\Collection;

final class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetIdReturnsNullByDefault(): void
    {
        $this->assertNull($this->client->getId());
    }

    public function testGetAccountsReturnsEmptyCollectionByDefault(): void
    {
        $accounts = $this->client->getAccounts();
        $this->assertInstanceOf(Collection::class,  $accounts);
        $this->assertCount(0,  $accounts);
    }

    public function testTimestampableEntityMethods(): void
    {
        $this->assertTrue(method_exists($this->client, 'getCreatedAt'));
        $this->assertTrue(method_exists($this->client, 'getUpdatedAt'));
    }
}
