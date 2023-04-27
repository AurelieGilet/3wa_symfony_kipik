<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        CategoryRepository $categoryRepository, 
    )
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $this->categoryRepository->findAll();

        $cactusBoule = [
            0 => [
                'category' => $categories[0],
                'name' => "Echinopsis subdenudata",
                'price' => 3.20,
                'description' => 'Plante sphérique aplatie, au corps vert démuni d\'épines. Abondante floraison blanche. Une espèce indispensable !',
                'image' => "images/products/echinopsis_subdenudata.jpg",
                'stock' => 10,
            ],
            1 => [
                'category' => $categories[0],
                'name' => "Echinocactus grusonii",
                'price' => 3.20,
                'description' => 'Le classique "Coussin de belle-mère". Cette plante sphérique aplatie, hérissée de fortes épines jaunes, devient énorme ! Sans aucun doute un des plus beaux cactus.',
                'image' => "images/products/echinocactus_grusonii.jpg",
                'stock' => 10,
            ],
            2 => [
                'category' => $categories[0],
                'name' => "Astrophytum myriostigma",
                'price' => 4.00,
                'description' => 'Cette forme plus rare du "Bonnet d’Évêque" est une sélection de plantes ne présentant que 4 côtes, pouvant évoluer parfois vers 5 côtes en vieillissant.',
                'image' => "images/products/astrophytum_myriostigma.jpg",
                'stock' => 10,
            ],
            3 => [
                'category' => $categories[0],
                'name' => "Astrophytum capricorne",
                'price' => 7.50,
                'description' => 'Corps couvert de flocons gris, muni de longues épines liégeuses, grises et recourbées. Les jeunes plantes n\'ont cependant pas d\'épine ! De lumineuses fleurs jaunes au centre rouge se succèdent pendant tout l\'été.',
                'image' => "images/products/astrophytum_capricorne.jpg",
                'stock' => 10,
            ],
            4 => [
                'category' => $categories[0],
                'name' => "Mammillaria hahniana",
                'price' => 5.20,
                'description' => 'De croissance assez lente, cette mamillaire est une jolie boule blanche poilue, devenant cylindrique en vieillissant. Fleurs pourpres en fin d\'hiver.',
                'image' => "images/products/mammillaria_hahniana.jpg",
                'stock' => 10,
            ],
            
        ];

        foreach ($cactusBoule as $cactus) {
            $product = new Product();

            $product->setCategory($categories[0])
                    ->setName($cactus['name'])
                    ->setPrice($cactus['price'])
                    ->setDescription($cactus['description'])
                    ->setImage($cactus['image'])
                    ->setStock($cactus['stock'])
            ;

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
