<?php

namespace App\DataFixtures\Factories;

use App\Entity\TaskList;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

class TaskListFactory extends PersistentObjectFactory
{

    protected function defaults(): array|callable
    {
       return [
           'title' => self::faker()->sentence(),
           'owner' => UserFactory::randomOrCreate(),
           'archived' => self::faker()->boolean(),
           'created' => self::faker()->dateTime(),
       ];
    }

    public static function class(): string
    {
        return TaskList::class;
    }
}