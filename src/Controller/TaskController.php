<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    #[Route('/tasks', name: 'task_list')]
    public function listAction(ManagerRegistry $doctrine)
    {
        return $this->render('task/list.html.twig', ['tasks' => $doctrine->getRepository(Task::class)->findAll()]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request,ManagerRegistry $doctrine)
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
            //dd($this->getUser());
            $task->setUser($this->getUser());
            //

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, ManagerRegistry $doctrine)
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


    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, ManagerRegistry $doctrine)
    {
        $task->toggle(!$task->getIsDone());
        $doctrine->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, ManagerRegistry $doctrine)
    {
        //$user = $doctrine->getRepository(User::class)->find(User::class, $this->getUser()->getId());
        $role = $this->getUser()->getRoles();
        //dd($role[0]);
        if ($task->getUser() === null && $role[0] == "ROLE_ADMIN"){
            $em = $doctrine->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }else if($task->getUser() === $this->getUser()){
            $em = $doctrine->getManager();
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', 'Vous ne pouvez supprimer que vos taches');
        }


        return $this->redirectToRoute('task_list');
    }

}