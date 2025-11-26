<?php

namespace App\DataFixtures\Factories;

use App\Entity\Task;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

class TaskFactory extends PersistentObjectFactory
{

    protected function defaults(): array|callable
    {
       return [
           'taskList' => TaskListFactory::random(),
           'summary' => self::faker()->sentence(),
           'done' => self::faker()->boolean(),
           'created' => self::faker()->dateTime(),
       ];
    }

    public static function class(): string
    {
        return Task::class;
    }
}