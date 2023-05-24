<?php

namespace App\DataFixtures;

use App\Entity\Wallet;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class WalletFixtures extends Fixture
{
    public const CLIENT_WALLET_REFERENCE = 'client-wallet';

    public function load(ObjectManager $manager): void
    {
        $wallet = new Wallet();

        $wallet->setAmount(100);

        $manager->persist($wallet);

        $manager->flush();

        $this->addReference(self::CLIENT_WALLET_REFERENCE, $wallet);
    }
}
