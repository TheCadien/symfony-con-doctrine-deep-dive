<?php

namespace App\EventListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreFlushEventArgs;

#[AsDoctrineListener('postFlush')]
class TaskListUpdateListener
{
    public function preFlush(PreFlushEventArgs $eventArgs): void
    {
        $uow = $eventArgs->getObjectManager()->getUnitOfWork();

        $this->handleEntityInsertions($uow->getScheduledEntityInsertions());
        $this->handleEntityUpdates($uow->getScheduledEntityUpdates());
        $this->handleEntityDeletions($uow->getScheduledEntityDeletions());
        $this->handleCollectionUpdates($uow->getScheduledCollectionUpdates());
        $this->handleCollectionDeletions($uow->getScheduledCollectionDeletions());
    }

    private function handleEntityInsertions(array $entityInsertions): void
    {
        if ($entityInsertions === []) {
            return;
        }
        foreach ($entityInsertions as $entityInsertion) {
            dd($entityInsertion);
            // Check if a Task is scheduled for deletion and update TaskList
        }
    }

    private function handleEntityUpdates(array $entityUpdates): void
    {
        if ($entityUpdates === []) {
            return;
        }
        foreach ($entityUpdates as $entityUpdate) {
            dd($entityUpdate);
            // Check if a Task is scheduled for update and update TaskList
        }
    }

    private function handleEntityDeletions(array $entityDeletions): void
    {
        if ($entityDeletions === []) {
            return;
        }
        foreach ($entityDeletions as $entityDeletion) {
            dd($entityDeletion);
            // Check if a Task is scheduled for deletion and update TaskList
        }
    }

    private function handleCollectionUpdates(array $collectionUpdates): void
    {
        if ($collectionUpdates === []) {
            return;
        }
        foreach ($collectionUpdates as $collectionUpdate) {
            // TODO Check, if this works
            $this->handleEntityUpdates($collectionUpdate);
        }
    }

    private function handleCollectionDeletions(array $collectionDeletions): void
    {
        if ($collectionDeletions === []) {
            return;
        }
        foreach ($collectionDeletions as $collectionDeletion) {
            // TODO Check, if this works
            $this->handleEntityDeletions($collectionDeletion);
        }
    }
}