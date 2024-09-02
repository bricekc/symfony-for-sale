<?php

namespace App\Tests\Application\Registration;

use App\Tests\Support\ApplicationTester;

class RegisterCest
{
    public function formRegisterTest(ApplicationTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->amOnPage('/register');
        $I->fillField('Mail', 'mail@mail.com');
        $I->fillField('Prénom', 'AAAAAAA');
        $I->fillField('Nom', 'HHHHHHHHHH');
        $I->fillField('Password', 'Azertyuio10*');
        $I->fillField('Repeat Password', 'Azertyuio10*');
        $I->click("S'inscrire");
        $I->seeEmailIsSent(1);
        $email = $I->grabLastSentEmail();
        $lien = preg_match('/<a\s[^>]*\bhref\s*=\s*([\"])(.*?)\\1[^>]*>/', $email->getBody()->toString(), $matches);
        $I->amOnPage($lien);
    }

    public function wrongRepeatPasswordTest(ApplicationTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->amOnPage('/register');
        $I->fillField('Mail', 'mail@mail.com');
        $I->fillField('Prénom', 'AAAAAAA');
        $I->fillField('Nom', 'HHHHHHHHHH');
        $I->fillField('Password', 'Azertyuio10*');
        $I->fillField('Repeat Password', 'Azertyuio10');
        $I->click("S'inscrire");
        $I->see('The password fields must match.');
    }

    public function wrongComplexityConstraintForPassword(ApplicationTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->amOnPage('/register');
        $I->fillField('Mail', 'mail@mail.com');
        $I->fillField('Prénom', 'AAAAAAA');
        $I->fillField('Nom', 'HHHHHHHHHH');
        $I->fillField('Password', 'Azertyuio10');
        $I->fillField('Repeat Password', 'Azertyuio10');
        $I->click("S'inscrire");
        $I->see('Le mot de passe doit contenir au moins 10 caractères, une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.');
    }

    public function redirectWhenEmailNotVerified(ApplicationTester $I): void
    {
        $I->amOnPage('/register');
        $I->fillField('Mail', 'mail@mail.com');
        $I->fillField('Prénom', 'AAAAAAA');
        $I->fillField('Nom', 'HHHHHHHHHH');
        $I->fillField('Password', 'Azertyuio10*');
        $I->fillField('Repeat Password', 'Azertyuio10*');
        $I->click("S'inscrire");
        $I->seeInCurrentUrl('/validate/email/1');
        $I->amOnPage('/advertisement');
        $I->seeInCurrentUrl('/validate/email/1');
        $I->click('Envoyer à nouveau le mail de confirmation');
        $I->seeInCurrentUrl('/validate/email/1');
    }
}
