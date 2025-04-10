<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    private const CURRENCIES = ['USD', 'EUR', 'GBP', 'JPY', 'CHF'];
    private const BALANCES = [
        1000.0000,
        2500.0000,
        5000.0000,
        7500.0000,
        10000.0000,
        15000.0000,
        25000.0000,
        50000.0000
    ];

    public function load(ObjectManager $manager): void
    {
        for ($clientId = 0; $clientId < 10; $clientId++) {
            $accountsPerClient = rand(2, 4);

            for ($j = 0; $j < $accountsPerClient; $j++) {
                $account = new Account();

                $client = $this->getReference('client_' . $clientId, Client::class);
                $account->setClient($client);

                $currency = self::CURRENCIES[array_rand(self::CURRENCIES)];
                $account->setCurrency($currency);

                $balance = self::BALANCES[array_rand(self::BALANCES)];
                $account->setBalance($balance);

                $this->addReference('account_' . $clientId . '_' . $j, $account);

                $manager->persist($account);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
        ];
    }
}
