<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures
 * @package App\DataFixtures
 */
class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category-fixtures';

    /**
     * @param ObjectManager $manager
     * php bin/console doctrine:fixtures:load --append
     */
    public function load(ObjectManager $manager)
    {
        $categories = [
            0 => ['1-grabs', 'Grabs', "Un grab consiste à attraper la planche avec la main pendant le saut."],
            1 => ['2-rotations', 'Rotations', "On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d'effectuer une rotation horizontale pendant le saut, puis d'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués."],
            2 => ['3-flips', 'Flips', "Un flip est une rotation verticale. On distingue les front flips, rotations en avant et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite et d'ajouter un grab à la rotation."],
            3 => ['4-rotations-desaxees', 'Rotations désaxées', "Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation."],
            4 => ['5-slides', 'Slides', "Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé. On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c'est-à-dire l'avant de la planche sur la barre, ou en tail slide, l'arrière de la planche sur la barre."],
            5 => ['6-one-foot-tricks', 'One foot tricks', "Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n'est pas fixé."]
        ];

        foreach ($categories as $element) {
            $category = new Category();
            $category->setLink($element[0])
                ->setName($element[1])
                ->setDescription($element[2])
            ;
            $manager->persist($category);
        }
        $this->addReference(self::CATEGORY_REFERENCE, $category);

        $manager->flush();
    }
}
