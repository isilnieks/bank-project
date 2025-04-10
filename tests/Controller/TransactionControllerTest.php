<?php

namespace App\Tests\Controller;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use App\Controller\TransactionController;

class TransactionControllerTest extends TestCase
{
    public function testControllerExists()
    {
        $this->assertTrue(class_exists(TransactionController::class));
    }

    public function testControllerHasTransferMethod()
    {
        $this->assertTrue(method_exists(TransactionController::class, 'transfer'));
    }

    public function testControllerConstructor()
    {
        $class = new ReflectionClass(TransactionController::class);
        $constructor = $class->getConstructor();
        $parameters = $constructor->getParameters();

        $this->assertCount(1, $parameters);
        $this->assertEquals('transactionService', $parameters[0]->getName());
    }
}
