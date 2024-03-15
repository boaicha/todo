<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    #[Route('/tasks', name: 'task_list', methods:['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $doctrine->getRepository(Task::class)->findAll()]);
    }

    #[Route('/tasks/create', name: 'task_create', methods:['GET','POST'])]
    public function createAction(Request $request,ManagerRegistry $doctrine): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            // date de creation de la tache
            $currentDateTime = new \DateTime();
            $task->setCreatedAt($currentDateTime);
            //
            // is done par defaut false a la creation
            $task->toggle(false);
            $task->setUser($this->getUser());
            //

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods:["GET", "POST"])]
    public function editAction(Task $task, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods:["POST", "GET"])]
    public function toggleTaskAction(Task $task, ManagerRegistry $doctrine): Response
    {
        $task->toggle(!$task->getIsDone());
        $doctrine->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ["POST", "GET"])]
    public function deleteTaskAction(Task $task, ManagerRegistry $doctrine): Response
    {
        $role = $this->getUser()->getRoles();
        if ($task->getUser()->getUsername() === "anonyme" && $role[0] == "ROLE_ADMIN"){
            $em = $doctrine->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }else if($task->getUser() === $this->getUser()){
            $em = $doctrine->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else if($task->getUser()->getUsername() === "anonyme" && $role[0] == "ROLE_USER") {
            $this->addFlash('error', 'Vous ne pouvez supprimer que vos taches');
        }


        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/done', name: 'tasks_done', methods: ['GET'])]
    public function doneTasksListAction(TaskRepository $taskRepository): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findBy(['isDone' => true])]);
    }
}

