<?php

namespace App\Repository;


use App\Entity\TaskList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskList::class);
    }

    public function findSummarizedTaskListFor(User $user)
    {
        /** Write in DQL */
    }


    public function findListsOwnedBy(User $owner)
    {
        /** Write in DQL */
    }

    public function findListsContributedBy(User $user)
    {
        /** Write with query builder */
    }

    public function findActive(User $owner)
    {
        /** Write with query builder */
    }

    public function findArchived(User $owner)
    {
        /** Write with query builder */
    }
}