<?php

namespace App\Tests\Repository;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserTest extends WebTestCase
{

    public function testFindAllAdmin()
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // gets the special container that allows fetching private services
        $container = self::$container;

        $results = self::$container->get('doctrine')->getRepository(User::class)->findAllAdmin();


        //$results = $this->repo->findAllAdmin();

        if (count($results) > 0) {
            $user = $results[0];
            $this->$this->assertContains('ROLE_ADMIN', $user->getRoles());
        }
    }
}
