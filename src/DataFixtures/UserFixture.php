<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin1@test.it');
        $user->setRoles('["ROLE_ADMIN"]');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$cm9Zb09Md0F2Uk01R24zUw$lMbe7qchaWU+qs03QM+MSJzsOvLXZ9MVMjFVMw8bLRw');
        $user->setUsername('admin1');
        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin2@test.it');
        $user->setRoles('["ROLE_ADMIN"]');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$cm9Zb09Md0F2Uk01R24zUw$lMbe7qchaWU+qs03QM+MSJzsOvLXZ9MVMjFVMw8bLRw');
        $user->setUsername('admin2');
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user1@test.it');
        $user->setRoles('["ROLE_USER"]');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$UnJEOE44aGQ4ZUZaNzRQUw$nrqPeaDuLyO+tDH1WIogQyuHDrXIePtwJ/v3mJpoeYc');
        $user->setUsername('user1');
        $manager->persist($user);
        
        $user = new User();
        $user->setEmail('user2@test.it');
        $user->setRoles('["ROLE_ADMIN"]');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$UnJEOE44aGQ4ZUZaNzRQUw$nrqPeaDuLyO+tDH1WIogQyuHDrXIePtwJ/v3mJpoeYc');
        $user->setUsername('user2');
        $manager->persist($user);

        $manager->flush();
    }
}

