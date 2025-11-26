<?php

namespace App\DataFixtures;

use App\DataFixtures\Factories\TaskFactory;
use App\DataFixtures\Factories\TaskListFactory;
use App\DataFixtures\Factories\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(20);
        UserFactory::createOne(['encodedPassword' => '$2y$13$re7HjDezdgVhne2PO.tkKukMEOQphiWIUKTBueg1umbrNilmzOS6.']);
        TaskListFactory::createMany(20);
        TaskListFactory::createOne(['title' => 'Task List: Foo']);
        TaskFactory::createMany(20);
    }
}
