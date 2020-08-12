<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        //ADMIN USER

        $user = new User();
        $user->setName('Çağatay Hakan SÖYLER');
        $user->setEmail('cgtysylr@gmail.com');
        $user->setPassword('123');
        $user->setRoles((array)'ROLE_ADMIN');
        $user->setStatus('1');
        $manager->persist($user);

        //ANOTHER USERS

        $user = new User();
        $user->setName('Doğuhan SÖYLER');
        $user->setEmail('dghn@gmail.com');
        $user->setPassword('456');
        $user->setRoles((array)'ROLE_USER');
        $user->setStatus('0');
        $manager->persist($user);

        $user = new User();
        $user->setName('James ADAM');
        $user->setEmail('james@gmail.com');
        $user->setPassword('789');
        $user->setRoles((array)'ROLE_USER');
        $user->setStatus('0');
        $manager->persist($user);

        $user = new User();
        $user->setName('William BORN');
        $user->setEmail('william@gmail.com');
        $user->setPassword('987');
        $user->setRoles((array)'ROLE_USER');
        $user->setStatus('0');
        $manager->persist($user);

        $manager->flush();
    }


}
