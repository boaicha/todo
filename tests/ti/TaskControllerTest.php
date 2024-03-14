<?php

namespace App\Tests\ti;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskControllerTest extends WebTestCase
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

    public function testListAction()
    {

        $client = $this->testAdminLogin();
        $client->request('GET', '/tasks');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testCreateTask()
    {
        $client = $this->testAdminLogin();

        $crawler = $client->request('POST', '/tasks/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = "test";
        $form['task[content]'] = "test content";

        $client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a été bien été ajoutée.');

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $newTask = $taskRepository->findOneByTitle("test");

        $this->assertInstanceOf(Task::class, $newTask);
    }

    public function testUpdateTask()
    {
        $client = $this->testAdminLogin();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("test");

        $crawler = $client->request('POST', '/tasks/'.$task->getId().'/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = "test";
        $form['task[content]'] = "test content updated";

        $client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été modifiée.');

        $updateTask = $taskRepository->findOneByContent("test content updated");

        $this->assertInstanceOf(Task::class, $updateTask);
    }

    public function testToggleTask()
    {
        $client = $this->testAdminLogin();

        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneByTitle("test");
        $client->request('POST', '/tasks/'.$task->getId().'/toggle'); // Adjust the URL according to your route

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! La tâche test a bien été marquée comme faite.');
    }


}