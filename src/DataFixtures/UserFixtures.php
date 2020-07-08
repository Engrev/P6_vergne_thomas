<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Engrev')
            ->setEmail('tvergne83@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'azerty123'))
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($user);
        $manager->flush();
    }
}
