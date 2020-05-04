<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 1; $i < 2; $i++) {
            $user = new User();
            $user->setUsername('admin'.$i);
            $user->setPassword('admin');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
