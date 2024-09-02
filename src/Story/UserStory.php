<?php

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public function build(): void
    {
        $this->addToPool('users', UserFactory::createOne([
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]));
        $this->addToPool('users', UserFactory::createOne([
            'email' => 'admin2@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]));
        $this->addToPool('users', UserFactory::createOne([
            'email' => 'user@example.com',
            'roles' => ['ROLE_USER'],
        ]));
        $this->addToPool('users', UserFactory::createOne([
            'email' => 'user2@example.com',
            'roles' => ['ROLE_USER'],
        ]));
        $this->addToPool('users', UserFactory::createMany(10));
        $this->addToPool('unverified users', UserFactory::createMany(4, ['isVerified' => 0]));
    }
}
