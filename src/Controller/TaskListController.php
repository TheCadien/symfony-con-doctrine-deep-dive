<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Entity\User;
use App\Form\ContributorType;
use App\Repository\TaskItemRepository;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route(name: 'tasklist_')]
class TaskListController extends AbstractController
{

    #[Route(path:"/", name:"list", methods:['GET'])]
    public function index(TaskListRepository $taskListRepository, Request $request, UserInterface $user)
    {
        switch ($request->query->get('filter')) {
            case 'own':
                $taskLists = $taskListRepository->findListsOwnedBy($user);
                break;
            case 'contributing':
                $taskLists = $taskListRepository->findListsContributedBy($user);
                break;
            case 'active':
                $taskLists = $taskListRepository->findActive($user);
                break;
            case 'archived':
                $taskLists = $taskListRepository->findArchived($user);
                break;
            default:
                return $this->render('tasks/index_summarized.html.twig', [
                    'lists' => $taskListRepository->findSummarizedTaskListFor($user),
                ]);
        }

        return $this->render('tasks/index.html.twig', [
            'task_lists' => $taskLists,
        ]);
    }


    /**
     * @throws InvalidArgumentException
     */
    #[Route(path: '/', name: 'create', methods: ['POST'])]
    public function create(ManagerRegistry $managerRegistry, ?CacheItemPoolInterface $resultCache, Request $request, UserInterface $user)
    {
        $entityManager = $managerRegistry->getManagerForClass(TaskList::class);
        $list = new TaskList($user, $request->request->get('name'));

        $entityManager->persist($list);
        $entityManager->flush();
        $entityManager->clear();

        if ($resultCache) {
            $resultCache->deleteItems(
                [
                    'frontpage_summarized',
                    'frontpage_owned',
                    'frontpage_contributed',
                    'frontpage_active',
                    'frontpage_archived',
                ]
            );
        }

        return $this->redirectToRoute('tasklist_list');
    }


    #[Route(path: '/', name: 'new', methods: ['GET'])]
    public function showNewTasks(TaskRepository $taskItemRepository)
    {
        $tasks = $taskItemRepository->findTasksCreatedToday();

        return $this->render('tasks/recent.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route(path: '/update/{id}', name: 'item_update', methods: ["POST"])]
    public function update(ManagerRegistry $managerRegistry, Task $taskItem, Request $request)
    {
        $entityManager = $managerRegistry->getManagerForClass(Task::class);

        if ($taskItem->isDone()) {
            $taskItem->reopen();
        } else {
            $taskItem->close();
        }

        $entityManager->flush();
        $entityManager->clear();


        return $this->redirectToRoute('tasklist_show', ['id' => $taskItem->getList()->getId()]);
    }

    #[Route(path:'/archive/{id}/{version}',name: 'archive', methods: ['POST'])]
    public function archive(ManagerRegistry $managerRegistry, $id, $version, Request $request)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $managerRegistry->getManagerForClass(TaskList::class);

        try {
            $taskList = $entityManager->find(TaskList::class, $id, LockMode::OPTIMISTIC, $version);

            $taskList->archive();

            $entityManager->flush();
            $entityManager->clear();
        } catch (OptimisticLockException $optimisticLockException) {
            $this->addFlash('error',
                'Could not update list! ' .
                'Probably someone has changed it during your request. ' .
                'Please check the current version and retry'
            );

            return $this->redirectToRoute('tasklist_show', ['id' => $id]);
        }

        return $this->redirectToRoute('tasklist_show', ['id' => $taskList->getId()]);
    }

    #[Route(path: '/contributors/{id}', name: 'contributors', methods: ['GET', 'POST'])]
    public function contributors(ManagerRegistry $managerRegistry, TaskList $taskList, Request $request)
    {
        $form = $this->createForm(ContributorType::class, null, ['list' => $taskList]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newContributor = $form->get('contributor')->getData();

            $taskList->addContributor($newContributor);

            $entityManager = $managerRegistry->getManagerForClass(Task::class);
            $entityManager->flush();
            $entityManager->clear();

            return $this->redirectToRoute('tasklist_show', ['id' => $taskList->getId()]);
        }

        return $this->render('tasks/contributors.html.twig', [
            'task_list' => $taskList,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(TaskList $taskList)
    {
        return $this->render('tasks/show.html.twig', ['task_list' => $taskList]);
    }

    #[Route(path: '/{id}', name: 'add', methods: ['POST'])]
    public function add(ManagerRegistry $managerRegistry, TaskList $taskList, Request $request)
    {
        $entityManager = $managerRegistry->getManagerForClass(TaskList::class);
        $taskList->addItem($request->request->get('summary'));

        $entityManager->flush();
        $entityManager->clear();

        return $this->redirectToRoute('tasklist_show', ['id' => $taskList->getId()]);
    }
}
