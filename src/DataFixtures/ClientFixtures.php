<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\DataFixtures\WalletFixtures;
use App\DataFixtures\AddressFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $plaintextPassword = 'password';

        $hashedPassword = $this->hasher->hashPassword(
            $client,
            $plaintextPassword
        );

        $client->setEmail('user@mail.com');
        $client->setPassword($hashedPassword);
        $client->setRoles(['ROLE_USER']);
        $client->setCreatedAt(new \DateTimeImmutable());
        $client->setFirstname('John');
        $client->setLastname('Doe');
        $client->setWallet($this->getReference(WalletFixtures::CLIENT_WALLET_REFERENCE));
        $client->setAddress($this->getReference(AddressFixtures::CLIENT_ADDRESS_REFERENCE));
        $client->setIsFullyRegistered(true);

        $manager->persist($client);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array (
            AdminFixtures::class,
            WalletFixtures::class,
            AddressFixtures::class,
        );
    }
}
