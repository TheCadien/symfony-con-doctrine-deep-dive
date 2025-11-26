<?php

namespace App\DataFixtures\Factories;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

class UserFactory extends PersistentObjectFactory
{

    protected function defaults(): array|callable
    {
       return [
           'email' => self::faker()->safeEmail(),
           'encodedPassword' => self::faker()->password(),
       ];
    }

    public static function class(): string
    {
        return User::class;
    }
}