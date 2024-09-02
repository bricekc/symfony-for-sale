<?php

namespace App\Tests\Application\Advertisement;

use App\Factory\AdvertisementFactory;
use App\Tests\Support\ApplicationTester;

class ListCest
{
    public function emptyAdvertisementList(ApplicationTester $I): void
    {
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Toutes les annonces');
        $I->seeNumberOfElements('.list-group-item', 0);
    }

    public function notEmptyAdvertisementList(ApplicationTester $I): void
    {
        AdvertisementFactory::createMany(20);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Toutes les annonces');
        $I->seeNumberOfElements('.list-group-item', 15);
    }
}
