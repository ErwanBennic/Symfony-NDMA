<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 1; $i < 2; $i++) {
            $user = new User();
            $user->setUsername('admin' . $i);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
