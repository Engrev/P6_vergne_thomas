<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user-fixtures';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * php bin/console doctrine:fixtures:load --append
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('username')
            ->setEmail('email')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
            ->setRoles()
            ->setIsActive(true)
            ->setIsVerified(true)
            ->setIsVerifiedAt()
        ;
        $this->addReference(self::USER_REFERENCE, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
