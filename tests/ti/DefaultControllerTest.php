<?php

// todo/tests/ti/DefaultControllerTest.php
namespace App\Tests\ti;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }


    public function testHomePage()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->client->loginUser($userRepository->findOneBy(['email' => 'simoncharbonnier03@gmail.com']));
        $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }
}