<?php

namespace App\Tests\Application\Advertisement;

use App\Factory\AdvertisementFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class CRUDCest
{
    public function newAdvertisement(ApplicationTester $I): void
    {
        CategoryFactory::createOne();
        $user = UserFactory::createOne();
        $I->amLoggedInAs($user->object());
        $I->amOnPage('/advertisement/new');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('advertisement');
        $I->fillField('title', 'azertyuiopqsdf');
        $I->fillField('description', 'azertyuiopqsdfghjklmwxcvbn');
        $I->fillField('price', '123');
        $I->fillField('location', 'azertyuiop');
        $I->selectOption('category', '1');
        $I->click('Ajouter');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement/1');
        $I->see($user->object()->getFirstname());
    }

    public function newAdvertisementAccessDenied(ApplicationTester $I): void
    {
        CategoryFactory::createOne();
        $I->amOnPage('/advertisement/new');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/login');
    }

    public function showAdvertisement(ApplicationTester $I): void
    {
        $category = CategoryFactory::createOne();
        AdvertisementFactory::createOne([
            'title' => 'azertyuiopqsdf',
            'description' => 'azertyuiopqsdfghjklmwxcvbn',
            'price' => '123',
            'location' => 'azertyuiop',
            'category' => $category,
        ]);

        $I->amOnPage('/advertisement/1');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Hello AdvertisementController!');
        $I->see('azertyuiopqsdf');
        $I->see('azertyuiopqsdfghjklmwxcvbn');
        $I->see('123');
        $I->see('azertyuiop');
        $I->see($category->getName());
    }

    public function editAdvertisement(ApplicationTester $I): void
    {
        $user = UserFactory::createOne();
        AdvertisementFactory::createOne([
            'title' => 'azertyuiopqsdf',
            'description' => 'azertyuiopqsdfghjklmwxcvbn',
            'price' => '123',
            'location' => 'azertyuiop',
            'category' => CategoryFactory::createOne(),
            'owner' => $user->object(),
        ]);

        $I->amLoggedInAs($user->object());
        $I->amOnPage('/advertisement/1/update');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('advertisement');
        $I->fillField('title', 'azertyuiopqsdf');
        $I->fillField('description', 'azertyuiopqsdfghjklmwxcvbn');
        $I->fillField('price', '123');
        $I->fillField('location', 'azertyuiop');
        $I->selectOption('category', '1');
        $I->click('Modifier');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement/1');
    }

    public function deleteAdvertisement(ApplicationTester $I): void
    {
        $user = UserFactory::createOne();
        AdvertisementFactory::createOne([
            'owner' => $user->object(),
        ]);

        $I->amLoggedInAs($user->object());
        $I->amOnPage('/advertisement/1/delete');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('deleteAdvertisement');
        $I->click('Supprimer');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement');
        $I->seeNumberOfElements('.list-group-item', 0);
    }

    public function deleteAdvertisementForbidden(ApplicationTester $I): void
    {
        $userConnected = UserFactory::createOne()->object();
        AdvertisementFactory::createOne([
            'owner' => UserFactory::createOne()->object(),
        ]);

        $I->amLoggedInAs($userConnected);
        $I->amOnPage('/advertisement/1/delete');
        $I->seeResponseCodeIs(403);
    }

    public function editAdvertisementForbidden(ApplicationTester $I): void
    {
        $userConnected = UserFactory::createOne()->object();
        AdvertisementFactory::createOne([
            'owner' => UserFactory::createOne()->object(),
        ]);

        $I->amLoggedInAs($userConnected);
        $I->amOnPage('/advertisement/1/update');
        $I->seeResponseCodeIs(403);
    }
}
