<?php

namespace App\Tests\tu;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Tests\TestCase;

class UserTest extends TestCase
{

    public function testUserInitisalisation(): void {

        $user = new User();
        $this->assertSame(["ROLE_USER"], $user->getRole());


    }

}