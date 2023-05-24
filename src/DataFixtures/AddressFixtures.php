<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public const CLIENT_ADDRESS_REFERENCE = 'client-address';

    public function load(ObjectManager $manager): void
    {
        $address = new Address();

        $address->setStreet('10 rue au pif');
        $address->setZip('42000');
        $address->setCity('Uneville');

        $manager->persist($address);

        $manager->flush();

        $this->addReference(self::CLIENT_ADDRESS_REFERENCE, $address);
    }
}
