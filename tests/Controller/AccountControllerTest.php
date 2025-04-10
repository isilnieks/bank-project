<?php

namespace App\Tests\Controller;

use App\Controller\AccountController;
use App\Entity\Account;
use App\Repository\TransactionRepository;
use PHPUnit\Framework\TestCase;

class AccountControllerTest extends TestCase
{
    public function testControllerExists()
    {
        $this->assertTrue(class_exists(AccountController::class));
    }

    public function testControllerHasGetAccountTransactionsMethod()
    {
        $this->assertTrue(method_exists(AccountController::class, 'getAccountTransactions'));
    }
}
