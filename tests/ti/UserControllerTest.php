<?php

namespace App\Tests\ti;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testAdminLogin($email = 'jaja@jaja.com')
    {
        $client = static::createClient();
        //active le profile
        $client->enableProfiler();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($email);
        $client->loginUser($testUser);

        return $client;
    }

    public function testListAction(){

        $client = $this->testAdminLogin();

        $client->request('GET', '/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }



    public function testCreateAction()
    {
        $client = static::createClient();
        $email = 'userTest' . uniqid() . '@gmail.com';
        $username = 'userTest' . uniqid();

        $existingUserEmail = 'jaja@jaja.com';

        $userRepository = static::getContainer()->get(UserRepository::class);
        $existingUser = $userRepository->findOneByEmail($existingUserEmail);

        $client->loginUser($existingUser);

        $crawler = $client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $username;
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = $email;
        $form['user[role]']->select("ROLE_USER");

        $client->submit($form);

        $this->assertResponseRedirects('/users');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', 'L\'utilisateur a bien été ajouté.');

        $newUser = $userRepository->findOneByEmail($email);
        $this->assertInstanceOf(User::class, $newUser);
    }

    private function getAdminUser(): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        return $userRepository->findOneBy(['username' => 'jaja']); // Recherchez l'utilisateur par son nom d'utilisateur
    }

    public function testEditAction()
    {
        $client = $this->testAdminLogin();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $userEmail = 'userTest@gmail.com';

        $user = $userRepository->findOneByEmail($userEmail);

        $edit = $client->request('GET', '/users/' . $user->getId() . '/edit');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $form = $edit->selectButton('Modifier')->form();
        $form['user[username]'] = 'Updated userTest';
        $form['user[password][first]'] = 'newpassword';
        $form['user[password][second]'] = 'newpassword';
        $form['user[email]'] = 'userTest@gmail.com';
        $form['user[role]']->select('ROLE_USER'); // Updated line

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/users'));

    }

}

