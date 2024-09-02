<?php

declare(strict_types=1);

namespace App\Tests\Application\Security;

use App\Factory\UserFactory;
use App\Tests\Support\ApplicationTester;

class AuthenticationCest
{
    public function testLogin(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'admin@example.com',
            'password' => 'test',
        ]);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Connexion');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/login');
        $I->see('Email', 'label');
        $I->see('Mot de passe', 'label');
        $I->fillField('email', 'admin@example.com');
        $I->fillField('password', 'test');
        $I->click('connexion');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/advertisement');
    }

    public function testLogout(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'admin@example.com',
            'password' => 'test',
        ]);
        $I->amLoggedInAs($user->object());
        $I->amOnPage('/advertisement/new');
        $I->click($user->getFirstname());
        $I->seeInCurrentUrl('/advertisement');
    }

    public function testLoginError(ApplicationTester $I): void
    {
        $user = UserFactory::createOne([
            'email' => 'admin@example.com',
            'password' => 'test',
        ]);
        $I->amOnPage('/advertisement');
        $I->seeResponseCodeIsSuccessful();
        $I->click('Connexion');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/login');
        $I->see('Email', 'label');
        $I->see('Mot de passe', 'label');
        $I->fillField('email', 'admin6@example.com');
        $I->fillField('password', 'tests');
        $I->click('connexion');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/login');
    }
}
