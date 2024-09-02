<?php

namespace App\Tests\Application;

use App\Tests\Support\ApplicationTester;

class HomeCest
{
    public function homePageIsAvailable(ApplicationTester $I): void
    {
        $I->amOnPage('/');
        $I->seeInCurrentUrl('/advertisement');
        $I->seeResponseCodeIsSuccessful();
    }
}
