<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Form\ContributorType;
use App\Repository\TaskItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'tasklist_')]
class TaskListController extends AbstractController
{

    #[Route(path: "/", name: "list", methods: ['GET'])]
    public function index()
    {
        return $this->render('tasks/index.html.twig', [
            'task_lists' => [],
        ]);
    }


    #[Route(path: '/', name: 'create', methods: ['POST'])]
    public function create()
    {

        return $this->redirectToRoute('tasklist_list');
    }


    #[Route(path: '/', name: 'new', methods: ['GET'])]
    public function showNewTasks()
    {
        return $this->render('tasks/recent.html.twig', [
            'tasks' => [],
        ]);
    }

    #[Route(path: '/update/{id}', name: 'item_update', methods: ["POST"])]
    public function update()
    {

        return $this->redirectToRoute('tasklist_show', ['id' => 1]);
    }

    #[Route(path: '/archive/{id}/{version}', name: 'archive', methods: ['POST'])]
    public function archive()
    {
        return $this->redirectToRoute('tasklist_show', ['id' => 1]);
    }

    #[Route(path: '/contributors/{id}', name: 'contributors', methods: ['GET', 'POST'])]
    public function contributors()
    {
        $form = $this->createForm(ContributorType::class, null, ['list' => $taskList]);


        return $this->render('tasks/contributors.html.twig', [
            'task_list' => [],
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(TaskList $taskList)
    {
        return $this->render('tasks/show.html.twig', ['task_list' => $taskList]);
    }

    #[Route(path: '/{id}', name: 'add', methods: ['POST'])]
    public function add()
    {
        return $this->redirectToRoute('tasklist_show', ['id' => 1]);
    }
}
