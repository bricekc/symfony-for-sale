<?php

namespace App\Tests\Application;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Group;
use Codeception\Example;

class AvailabilityCest
{
    #[DataProvider('dataProvider')]
    #[Group('available')]
    public function pageIsAvailable(ApplicationTester $I, Example $example): void
    {
        $user = UserFactory::createOne();
        CategoryFactory::createOne();
        AdvertisementFactory::createOne(['owner' => $user->object()]);
        $I->amOnPage('/'.$example['url']);
        $I->seeResponseCodeIsSuccessful();
    }

    protected function dataProvider(): array
    {
        return [
            ['url' => 'advertisement'],
            ['url' => 'advertisement/new'],
            ['url' => 'advertisement/1'],
            ['url' => 'advertisement/1/update'],
            ['url' => 'category'],
            ['url' => 'category/1'],
            ['url' => 'login'],
            ['url' => 'logout'],
            ['url' => 'user/1'],
            ['url' => 'register'],
            ['url' => 'validate/email/1'],
        ];
    }
}
