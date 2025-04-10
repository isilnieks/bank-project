<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ClientController;

class ClientControllerTest extends TestCase
{
    public function testControllerExists()
    {
        $this->assertTrue(class_exists(ClientController::class));
    }

    public function testControllerHasGetClientAccountsMethod()
    {
        $this->assertTrue(
            method_exists(ClientController::class, 'getClientAccounts')
        );
    }
}
