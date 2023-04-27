<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['cactus boule', 'cactus cierge', 'cactus à raquettes', 'cactus grimpant', 'cactus bizarre', 'cactus sans épine'];

        for ($i=0; $i < count($categories); $i++) { 
            $category = new Category();

            $category->setName($categories[$i]);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
