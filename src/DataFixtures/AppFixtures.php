<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {

        $usersData = [
            ['username' => 'jaja', 'email' => 'jaja@jaja.com', 'role' => ["ROLE_ADMIN"]],
            ['username' => 'anonyme', 'email' => 'anonyme@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'userTest', 'email' => 'userTest@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'simon', 'email' => 'simoncharbonnier03@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'john', 'email' => 'johndoe@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'shaun', 'email' => 'shaunwinter@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'martina', 'email' => 'martinasnow@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'jacques', 'email' => 'jaques@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'jean', 'email' => 'jean@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'karim', 'email' => 'karim@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'stephane', 'email' => 'stephane@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'boris', 'email' => 'boris@gmail.com', 'role' => ["ROLE_USER"]],
            ['username' => 'karine', 'email' => 'karine@gmail.com', 'role' => ["ROLE_USER"]],
        ];

        foreach ($usersData as $data) {
            $user = new User();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setRole($data['role']);

            $manager->persist($user);

            // If the username is "Anonyme", store the reference for later use
            if ($data['username'] === 'anonyme') {
                $this->addReference('anonyme_user', $user);
            }
        }

        $taskData = [
            ['title' => 'task 1', 'user' => 'anonyme', 'content' => 'content of task 1', 'created_at' => "01/12/2022"],
            ['title' => 'task 2', 'user' => 'anonyme', 'content' => 'content of task 2', 'created_at' => "24/11/2022"],
            ['title' => 'task 3', 'user' => 'anonyme', 'content' => 'content of task 3', 'created_at' => "02/02/2022"],
            ['title' => 'test', 'user' => 'anonyme', 'content' => 'test', 'created_at' => "01/12/2022"],
            ['title' => 'task 4', 'user' => 'anonyme', 'content' => 'content of task 4', 'created_at' => "23/03/2022"],
        ];

        foreach ($taskData as $data) {
            $task = new Task();
            $task->setTitle($data['title']);
            $task->setContent($data['content']);
            $dateFormat = 'd/m/Y';
            $createdAt = DateTime::createFromFormat($dateFormat, $data['created_at']);
            $task->setCreatedAt($createdAt);

            // Get the user reference by username
            $user = $this->getReference("anonyme_user");
            $task->setUser($user);

            $manager->persist($task);
        }


        $manager->flush();
    }
}
