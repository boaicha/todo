<?php

namespace App\Tests\ti;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * @doesNotPerformAssertions
     */
//    public function testAdminLogin(){
//        $client = static::createClient();
//        $userRepository = static::getContainer()->get(UserRepository::class);
//
//        $user = $userRepository->findOneByEmail("jaja@jaja.com");
//        $client->loginUser($user);
//        //$this->assertTrue(true);
//        return $client;
//
//
//    }

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

//    public function testCreateAction(){
//
//        $client = $this->testAdminLogin();
//
//        $create = $client->request('GET', '/users/create');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        $form = $create->selectButton('Ajouter')->form();
//        $form['user[username]'] = 'userTest';
//        $form['user[password][first]'] = 'password';
//        $form['user[password][second]'] = 'password';
//        $form['user[email]'] = 'userTest@gmail.com';
//        $form['user[role]']->select('ROLE_USER');
//        $client->submit($form);
//
////        $this->assertEquals(302, $client->getResponse()->getStatusCode());
////        $this->assertTrue($client->getResponse()->isRedirect('/users'));
//        $this->assertEquals(302, $client->getResponse()->getStatusCode());
//
//// With this line
//        $this->assertTrue($client->getResponse()->isRedirect());
//
//
//
//    }

//    public function testCreateAction(){
//        $client = $this->testAdminLogin();
//
//        $create = $client->request('GET', '/users/create');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//        $form = $create->selectButton('Ajouter')->form();
//        $form['user[username]'] = 'userTest';
//        $form['user[password][first]'] = 'password';
//        $form['user[password][second]'] = 'password';
//        $form['user[email]'] = 'userTest@gmail.com';
//        $form['user[role]']->select("ROLE_USER"); // Updated line
//        $client->submit($form);
//
//        $this->assertEquals(302, $client->getResponse()->getStatusCode());
//        $this->assertTrue($client->getResponse()->isRedirect());
//    }

    public function testCreateAction()
    {
        $client = static::createClient();
        // Generate a unique email address for each test run
        $email = 'userTest' . uniqid() . '@gmail.com';
        $username = 'userTest' . uniqid();

        // Make sure to replace this with an existing user in your database
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

//    public function testEditAction()
//    {
//
//            self::ensureKernelShutdown();
//            $client = static::createClient();
//
//            // Make sure to replace this with an existing user in your database
//            $existingUserEmail = 'jaja@jaja.com';
//
//            $userRepository = static::getContainer()->get(UserRepository::class);
//            $existingUser = $userRepository->findOneByEmail($existingUserEmail);
//
//            $client->loginUser($existingUser);
//            $crawler = $client->request('GET', '/users/' . $existingUser->getId() . '/edit');
//
//            $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//            // Créez un formulaire de test pour la modification d'un utilisateur et soumettez-le.
//            $form = $crawler->selectButton('Modifier')->form();
//            // Modifiez les champs du formulaire selon vos besoins.
//            $form['user[username]'] = 'utilisateur_modifie';
//            $form['user[password][first]'] = 'nouveau_mot_de_passe';
//            $form['user[password][second]'] = 'nouveau_mot_de_passe';
//            $client->submit($form);
//
//            $this->assertTrue($client->getResponse()->isRedirect('/users'));
//
//            // Chargez à nouveau l'utilisateur depuis la base de données après la modification.
//            $updatedUser = $this->getContainer()->get('doctrine')->getRepository(User::class)->find($existingUser->getId());
//
//            // Vérifiez que le rôle de l'utilisateur a été modifié correctement.
//            $this->assertContains(["ROLE_USER"], $updatedUser->getRoles());
//
//    }

}