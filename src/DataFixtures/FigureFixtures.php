<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Figure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class FigureFixtures
 * @package App\DataFixtures
 */
class FigureFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FigureFixtures constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ObjectManager $manager
     * php bin/console doctrine:fixtures:load --append
     */
    public function load(ObjectManager $manager)
    {
        $figures = [
            0 => ['Mute', 'uploads\photo.jpg'],
            1 => ['Sad', 'uploads\photo.jpg'],
            2 => ['Indy', 'uploads\photo.jpg'],
            3 => ['Stalefish', 'uploads\photo.jpg'],
            4 => ['Tail Grab', 'uploads\photo.jpg'],
            5 => ['Nose Grab', 'uploads\photo.jpg'],
            6 => ['Japan', 'uploads\photo.jpg'],
            7 => ['Seat Belt', 'uploads\photo.jpg'],
            8 => ['Truck Driver', 'uploads\photo.jpg'],
            9 => ['180', 'uploads\animated-snowboard.png'],
            10 => ['360', 'uploads\animated-snowboard.png'],
            11 => ['540', 'uploads\animated-snowboard.png'],
            12 => ['720', 'uploads\animated-snowboard.png'],
            13 => ['900', 'uploads\animated-snowboard.png'],
            14 => ['1080', 'uploads\animated-snowboard.png']
        ];
        $category_1 = $this->entityManager->getRepository(Category::class)->find(1);
        $category_2 = $this->entityManager->getRepository(Category::class)->find(2);

        for ($i=0;$i<15;$i++) {
        //foreach ($figures as $element) {
            $figure = new Figure();
            $figure->setCategory($i <= 8 ? $category_1 : $category_2)
                ->setName($figures[$i][0])
                ->setPicture($figures[$i][1])
                ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ;
            $manager->persist($figure);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}
