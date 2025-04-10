<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    private const EXCHANGE_RATES = [
        'USD' => [
            'EUR' => 0.85,
            'GBP' => 0.75,
            'JPY' => 110.0,
            'CHF' => 0.92,
            'USD' => 1.0,
        ],
        'EUR' => [
            'USD' => 1.18,
            'GBP' => 0.88,
            'JPY' => 130.0,
            'CHF' => 1.08,
            'EUR' => 1.0,
        ],
        'GBP' => [
            'USD' => 1.33,
            'EUR' => 1.14,
            'JPY' => 147.0,
            'CHF' => 1.23,
            'GBP' => 1.0,
        ],
        'JPY' => [
            'USD' => 0.0091,
            'EUR' => 0.0077,
            'GBP' => 0.0068,
            'CHF' => 0.0083,
            'JPY' => 1.0,
        ],
        'CHF' => [
            'USD' => 1.09,
            'EUR' => 0.93,
            'GBP' => 0.81,
            'JPY' => 120.0,
            'CHF' => 1.0,
        ],
    ];

    private const TRANSACTION_AMOUNTS = [
        10.00,
        25.00,
        50.00,
        100.00,
        200.00,
        300.00,
        500.00,
        750.00,
        1000.00
    ];

    public function load(ObjectManager $manager): void
    {
        $accountRefs = [];

        for ($clientId = 0; $clientId < 10; $clientId++) {
            for ($j = 0; $j < 4; $j++) {
                $refName = 'account_' . $clientId . '_' . $j;
                if ($this->hasReference($refName, Account::class)) {
                    $accountRefs[] = $refName;
                }
            }
        }

        for ($i = 0; $i < 50; $i++) {
            $transaction = new Transaction();

            $fromAccountIdx = array_rand($accountRefs);
            $toAccountIdx = array_rand($accountRefs);

            while ($fromAccountIdx === $toAccountIdx) {
                $toAccountIdx = array_rand($accountRefs);
            }

            $fromAccount = $this->getReference($accountRefs[$fromAccountIdx], Account::class);
            $toAccount = $this->getReference($accountRefs[$toAccountIdx], Account::class);

            $transaction->setFromAccount($fromAccount);
            $transaction->setToAccount($toAccount);

            $fromCurrency = $fromAccount->getCurrency();
            $toCurrency = $toAccount->getCurrency();

            $transaction->setFromCurrency($fromCurrency);
            $transaction->setToCurrency($toCurrency);

            $fromAmount = self::TRANSACTION_AMOUNTS[array_rand(self::TRANSACTION_AMOUNTS)];
            $transaction->setFromAmount($fromAmount);

            if ($fromCurrency !== $toCurrency) {
                $exchangeRate = self::EXCHANGE_RATES[$fromCurrency][$toCurrency];
                $transaction->setExchangeRate($exchangeRate);
                $toAmount = $fromAmount * $exchangeRate;
            } else {
                $transaction->setExchangeRate(1.0);
                $toAmount = $fromAmount;
            }

            $transaction->setToAmount($toAmount);

            if ($fromAccount->hasSufficientBalance($fromAmount)) {
                $fromAccount->subtractFromBalance($fromAmount);
                $toAccount->addToBalance($toAmount);

                $manager->persist($transaction);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AccountFixtures::class,
        ];
    }
}
