<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->manager = $manager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
//        $user = new User();
//        $user->setUsername('ibrmtksl');
//        $user->setRoles(['ROLE_ADMIN']);
//        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'qweasd123'));
//
//        $this->manager->persist($user);
//        $this->manager->flush();
    }
}
