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

        $cacti = [
            //cactus boule
            0 => [
                'category' => $categories[0],
                'name' => 'Echinopsis subdenudata',
                'price' => 3.20,
                'description' => 'Plante sphérique aplatie, au corps vert démuni d\'épines. Abondante floraison blanche. Une espèce indispensable !',
                'image' => 'echinopsis_subdenudata.jpg',
                'stock' => 10,
            ],
            1 => [
                'category' => $categories[0],
                'name' => 'Echinocactus grusonii',
                'price' => 3.20,
                'description' => 'Le classique "Coussin de belle-mère". Cette plante sphérique aplatie, hérissée de fortes épines jaunes, devient énorme ! Sans aucun doute un des plus beaux cactus.',
                'image' => 'echinocactus_grusonii.jpg',
                'stock' => 10,
            ],
            2 => [
                'category' => $categories[0],
                'name' => 'Astrophytum myriostigma',
                'price' => 4.00,
                'description' => 'Cette forme plus rare du "Bonnet d’Évêque" est une sélection de plantes ne présentant que 4 côtes, pouvant évoluer parfois vers 5 côtes en vieillissant.',
                'image' => 'astrophytum_myriostigma.jpg',
                'stock' => 10,
            ],
            3 => [
                'category' => $categories[0],
                'name' => 'Astrophytum capricorne',
                'price' => 7.50,
                'description' => 'Corps couvert de flocons gris, muni de longues épines liégeuses, grises et recourbées. Les jeunes plantes n\'ont cependant pas d\'épine ! De lumineuses fleurs jaunes au centre rouge se succèdent pendant tout l\'été.',
                'image' => 'astrophytum_capricorne.jpg',
                'stock' => 10,
            ],
            4 => [
                'category' => $categories[0],
                'name' => 'Mammillaria hahniana',
                'price' => 5.20,
                'description' => 'De croissance assez lente, cette mamillaire est une jolie boule blanche poilue, devenant cylindrique en vieillissant. Fleurs pourpres en fin d\'hiver.',
                'image' => 'mammillaria_hahniana.jpg',
                'stock' => 10,
            ],
            // cactus cierge
            5 => [
                'category' => $categories[1],
                'name' => 'Cleistocactus samaipatanus',
                'price' => 9.00,
                'description' => 'Cierge aux tiges assez souples, portant de fines épines jaune pâle, finissant par former une touffe largement étalée. Abondante floraison rouge pendant l\'été. Croissance très vigoureuse !',
                'image' => 'cleistocactus_samaipatanus.jpg',
                'stock' => 10,
            ],
            6 => [
                'category' => $categories[1],
                'name' => 'Cleistocactus strausii',
                'price' => 3.20,
                'description' => 'Une jolie plante atteignant environ 2 mètres, formant des touffes par division de la base, plus rarement par ramification. Dense toison de fines épines et de poils blancs, nombreuses fleurs tubulaires carmin à partir d\'une dizaine d\'années. Demande des arrosages abondants en été.',
                'image' => 'cleistocactus_strausii.jpg',
                'stock' => 10,
            ],
            7 => [
                'category' => $categories[1],
                'name' => 'Myrtillocactus geometrizans',
                'price' => 4.50,
                'description' => 'Cierge arborescent de croissance rapide, produisant de nombreuses branches à partir d\'un mètre de hauteur. Son épiderme bleu ciel et ses épines noires le rendent décoratif. Bon porte-greffe.',
                'image' => 'myrtillocactus_geometrizans.jpg',
                'stock' => 10,
            ],
            8 => [
                'category' => $categories[1],
                'name' => 'Pilosocereus pachycladus',
                'price' => 4.50,
                'description' => 'Ce cactus colonnaire est très décoratif par son épiderme bleu ciel et ses fines épines jaunes. Les tiges sont constituées de 5 à 6 côtes bien renflées.',
                'image' => 'pilosocereus_pachycladus.jpg',
                'stock' => 10,
            ],
            9 => [
                'category' => $categories[1],
                'name' => 'Haageocereus pseudomelanostele',
                'price' => 6.50,
                'description' => 'Un cierge se ramifiant rapidement de la base, pouvant atteindre un mètre de hauteur. Plante entièrement couverte de fines épines jaunes ou marron.',
                'image' => 'haageocereus_pseudomelanostele.jpg',
                'stock' => 10,
            ],
            // cactus à raquettes
            10 => [
                'category' => $categories[2],
                'name' => 'Opuntia ficus-indica',
                'price' => 4.00,
                'description' => 'Le "Figuier de Barbarie" peut dépasser 2 mètres de hauteur. Il possède de gros articles ovales, inermes, atteignant 40 cm. Ses fleurs jaunes au mois de mai sont suivies de gros fruits jaunes comestibles, venant à maturité en fin d\'été. Cette espèce est probablement un hybride sélectionné par les amérindiens bien avant la découverte du Nouveau Monde.',
                'image' => 'opuntia_ficus-indica.jpg',
                'stock' => 10,
            ],
            11 => [
                'category' => $categories[2],
                'name' => 'Consolea moniliformis',
                'price' => 9.00,
                'description' => 'Sorte d\'Opuntia aux raquettes extrêmement allongées. Épiderme gris-vert, ou brunâtre au soleil, surface finement divisée en petits mamelons, petites fleurs jaune orangé.',
                'image' => 'consolea_moniliformis.jpg',
                'stock' => 10,
            ],
            12 => [
                'category' => $categories[2],
                'name' => 'Opuntia cymochila',
                'price' => 7.50,
                'description' => 'Plante étalée formant des tapis. Articles arrondis portant des glochides jaunes et de longues épines brun-jaune. Fleurs jaune pâle. Espèce proche d\'Opuntia phaeacantha, poussant dans les prairies du centre des USA.',
                'image' => 'opuntia_cymochila.jpg',
                'stock' => 10,
            ],
            13 => [
                'category' => $categories[2],
                'name' => 'Opuntia bergeriana',
                'price' => 3.00,
                'description' => 'Articles ovales-allongés gris-vert pâle, pouvant atteindre 20 cm et munis d\'épines grises assez longues. Plante très vigoureuse dépassant 2 mètres de hauteur, et produisant de nombreuses fleurs rouges au printemps.',
                'image' => 'opuntia_bergeriana.jpg',
                'stock' => 10,
            ],
            14 => [
                'category' => $categories[2],
                'name' => 'Opuntia leucotricha',
                'price' => 3.00,
                'description' => 'Plante arborescente pouvant former des sujets de 2 mètres de haut, avec des branches très écartées. Les articles ovales gris-vert portent parfois de longues épines blanches en vieillissant. Fleurs jaunes en mai.',
                'image' => 'opuntia_leucotricha.jpg',
                'stock' => 10,
            ],
            // cactus grimpant
            15 => [
                'category' => $categories[3],
                'name' => 'Hylocereus undatus',
                'price' => 6.00,
                'description' => 'Cierge-liane aux tiges triangulaires très larges, vert clair, de croissance très rapide. Énormes fleurs blanches en été ! C\'est la plante qui fournit les "fruits du Dragon" rouges à pulpe blanche, très prisés en Asie.',
                'image' => 'hylocereus_undatus.jpg',
                'stock' => 10,
            ],
            16 => [
                'category' => $categories[3],
                'name' => 'Selenicereus pteranthus',
                'price' => 6.00,
                'description' => 'Cierge grimpant aux tiges vert violacé, atteignant jusqu\'à 5 cm de diamètre, avec des épines très courtes. Très grande fleur blanche (Ø 30 cm), extérieur brun-rouge, avec des écailles et des soies blanches, non odorante, pendant les nuits d\'été.',
                'image' => 'selenicereus_pteranthus.jpg',
                'stock' => 10,
            ],
            17 => [
                'category' => $categories[3],
                'name' => 'Selenicereus grandiflorus',
                'price' => 6.00,
                'description' => 'Forme très robuste de la Reine de la Nuit. Tiges vert violacé portant de fines épines jaunes. Fleur blanche de 30 cm de diamètre, extérieur brun-jaune avec des soies blanches. Puissante odeur rappelant le narcisse.',
                'image' => 'selenicereus_grandiflorus.jpg',
                'stock' => 10,
            ],
            18 => [
                'category' => $categories[3],
                'name' => 'Selenicereus vagans',
                'price' => 6.00,
                'description' => 'Tiges cylindriques sarmenteuses d\'un cm de diamètre, très épineuses. Nombreuses fleurs blanches de 15 cm de diamètre pendant le mois de juin.',
                'image' => 'selenicereus_vagans.jpg',
                'stock' => 10,
            ],
            19 => [
                'category' => $categories[3],
                'name' => 'Hylocereus polyrhizus',
                'price' => 8.00,
                'description' => 'Plante proche de Hylocereus costaricensis. Tiges triangulaires vert clair, les aréoles portent de courtes épines et des soies blanches. Grandes fleurs blanches. Fruits rouges à la pulpe rouge.',
                'image' => 'hylocereus_polyrhizus.jpg',
                'stock' => 10,
            ],
            // cactus sans épines
            20 => [
                'category' => $categories[4],
                'name' => 'Astrophytum coahuilense',
                'price' => 6.00,
                'description' => 'Cette espèce ressemble à un Astrophytum myriostigma, mais avec un floconnage plus grisâtre et plus épais, et une croissance nettement plus lente. Fleurs jaunes au centre rouge',
                'image' => 'astrophytum_coahuilense.jpg',
                'stock' => 10,
            ],
            21 => [
                'category' => $categories[4],
                'name' => 'Ariocarpus retusus',
                'price' => 8.00,
                'description' => 'Plante aplatie et sans épine, aux mamelons triangulaires lisses. De la laine et des fleurs blanches apparaissent à partir d\'une dizaine d\'années. La croissance la plus soutenue du genre Ariocarpus, bien que restant très lente.',
                'image' => 'ariocarpus_retusus.jpg',
                'stock' => 10,
            ],
            22 => [
                'category' => $categories[4],
                'name' => 'Echinocereus scheeri',
                'price' => 3.60,
                'description' => 'Touffe de tiges vertes à cinq ou six côtes, ressemblant à des cornichons, épines très courtes ou absentes. Fleur rose formant un tube très long.',
                'image' => 'echinocereus_scheeri.jpg',
                'stock' => 10,
            ],
            23 => [
                'category' => $categories[4],
                'name' => 'Ferocactus glaucescens',
                'price' => 3.60,
                'description' => 'Cette forme plus rare du "Bonnet d’Évêque" est une sélection de plantes ne présentant que 4 côtes, pouvant évoluer parfois vers 5 côtes en vieillissant.',
                'image' => 'ferocactus_glaucescens.jpg',
                'stock' => 10,
            ],
            24 => [
                'category' => $categories[4],
                'name' => 'Mammillaria schiedeana',
                'price' => 3.60,
                'description' => 'Très jolie plante poussant lentement en touffe aplatie, Ses épines plumeuses jaunes sont soyeuses au toucher. Les petites fleurs blanches sont suivies de petits fruits rouges décoratifs.',
                'image' => 'mammillaria_schiedeana.jpg',
                'stock' => 10,
            ],
        ];

        foreach ($cacti as $cactus) {
            $product = new Product();

            $product->setCategory($cactus['category'])
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
