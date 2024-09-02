<?php

declare(strict_types=1);

namespace App\Tests\Application\Advertisement;

use App\Factory\AdvertisementFactory;
use App\Tests\Support\ApplicationTester;

class SearchCest
{
    public function searchByTitle(ApplicationTester $I): void
    {
        AdvertisementFactory::createOne([
            'title' => 'title',
            'description' => 'description',
            'price' => '123',
            'location' => 'azertyuiop',
        ]);
        AdvertisementFactory::createOne([
            'title' => 'test',
            'description' => 'description2',
            'price' => '123',
            'location' => 'azertyuiop',
        ]);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('search', 'title');
        $I->click('search-button');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement?search=title');
        $I->seeNumberOfElements('.list-group-item', 1);
    }

    public function searchByTitleError(ApplicationTester $I): void
    {
        AdvertisementFactory::createOne([
            'title' => 'test',
            'description' => 'description2',
            'price' => '123',
            'location' => 'azertyuiop',
        ]);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('search', 'title');
        $I->click('search-button');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement?search=title');
        $I->seeNumberOfElements('.list-group-item', 0);
    }

    public function searchByDescription(ApplicationTester $I): void
    {
        AdvertisementFactory::createOne([
            'title' => 'title',
            'description' => 'description',
            'price' => '123',
            'location' => 'azertyuiop',
        ]);
        AdvertisementFactory::createOne([
            'title' => 'test',
            'description' => 'test',
            'price' => '123',
            'location' => 'azertyuiop',
        ]);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('search', 'description');
        $I->click('search-button');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement?search=description');
        $I->seeNumberOfElements('.list-group-item', 1);
    }
}
